<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Lecturer;
use App\Models\SchoolSession;
use App\Models\Student;
use App\Models\User;
use App\Utils\Utils;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Authorization check
        if (!in_array($user->role, [Utils::ROLE_ADMIN, Utils::ROLE_LECTURER])) {
            Auth::logout();
            Session::flush();
            return redirect()->route('login')->with('error', 'Unauthorized access.');
        }

        // Filters
        $filters = [
            'department' => $request->input('department'),
            'program_type' => $request->input('program_type'),
            'school_session_id' => $request->input('school_session_id'),
            'search' => $request->input('search'),
            'course' => $request->input('course'),
        ];

        // Common data
        $departments = Department::query()
            ->distinct()
            ->orderBy('name')
            ->get(['id', 'name']);

        $programTypes = [Utils::PROGRAM_TYPE_DEGREE, Utils::PROGRAM_TYPE_DIPLOMA];
        $schoolSessions = SchoolSession::all();

        // Query
        $studentsQuery = $user->role === Utils::ROLE_ADMIN
            ? $this->fetchAdminStudents($filters)
            : $this->fetchLecturerStudents($user, $filters);

        $students = $studentsQuery->paginate(10)->withQueryString();

        return view('dashboard.admin.students', compact('students', 'departments', 'programTypes', 'schoolSessions'));
    }

    public function create(Request $request)
    {
        $departments = Department::query()
            ->distinct()
            ->orderBy('name')
            ->get(['id', 'name']);

        $schoolSessions = SchoolSession::all();

        $programTypes = [Utils::PROGRAM_TYPE_DEGREE, Utils::PROGRAM_TYPE_DIPLOMA];

        return view('dashboard.admin.add-student', compact('departments', 'schoolSessions', 'programTypes'));
    }

    public function store(Request $request)
    {
        $user = $request->user();

        // Authorization check
        if ($user->role != Utils::ROLE_ADMIN) {
            return redirect()->route('login')->with('error', 'Unauthorized access.');
        }

        $validated = $request->validate([
            'department' => ['required', Rule::exists('departments', 'id')],
            'school_session_id' => ['required', Rule::exists('school_sessions', 'id')],
            'name' => ['required'],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'current_level' => [Rule::in(Utils::LEVELS)],
            'program_type' => ['required', Rule::in([Utils::PROGRAM_TYPE_DIPLOMA, Utils::PROGRAM_TYPE_DEGREE])],
        ]);

        try {
            DB::beginTransaction();

            // Create user
            $studentUser = User::query()->create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make(Utils::defaultPassword()),
                'role' => Utils::ROLE_STUDENT,
            ]);

            // Create student
            Student::query()->create([
                'user_id' => $studentUser->id,
                'department_id' => $validated['department'],
                'matric_no' => $this->generateMatricNumber(),
                'current_level' => $validated['current_level'],
                'next_level' => intval($validated['current_level']) + 1,
                'program_type' => $validated['program_type'],
                'school_session_id' => $validated['school_session_id'],
            ]);

            DB::commit();

            return redirect()->route('dashboard', ['role' => $user->role])->with('success', 'Student created successfully.');
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Failed to create student');
        }
    }


    private function fetchLecturerStudents(User $user, array $filters = []): Builder
    {
        $profile = $user->profile();

        $query = Student::query()
            ->where('current_level', $profile->level)
            ->where('program_type', $profile->program_type)
            ->where('department_id', $profile->department->id)
            ->with(['user:id,name,email', 'session:id,name']);

        return $this->applyStudentFilters($query, $filters);
    }

    private function fetchAdminStudents(array $filters = []): Builder
    {
        $query = Student::query()
            ->with(['user:id,name,email', 'session:id,name']);

        return $this->applyStudentFilters($query, $filters);
    }

    private function applyStudentFilters(Builder $query, array $filters): Builder
    {
        return $query
            ->when(!empty($filters['department']), fn($q) => $q->where('department_id', $filters['department']))
            ->when(!empty($filters['program_type']), fn($q) => $q->where('program_type', $filters['program_type']))
            ->when(!empty($filters['school_session_id']), fn($q) => $q->where('school_session_id', $filters['school_session_id']))
            ->when(!empty($filters['search']), function ($q) use ($filters) {
                $search = '%' . $filters['search'] . '%';
                $q->where(function ($q) use ($search) {
                    $q->whereHas('user', function ($sub) use ($search) {
                        $sub->where('name', 'like', $search)
                            ->orWhere('email', 'like', $search);
                    })->orWhere('matric_no', 'like', $search);
                });
            })
            ->when(!empty($filters['course_id']), function ($q) use ($filters) {
                $q->whereHas('courseRegistrations.courses', function ($sub) use ($filters) {
                    $sub->where('id', $filters['course_id']);
                });
            });
    }




    private function generateMatricNumber()
    {
        $year = date('Y'); // Get the current year, e.g., 2025
        $nextCount = Student::count() + 1;

        // Total matric number must be 10 characters
        // Year takes 4, nextCount takes variable length, rest filled with zeros
        $suffixLength = 10 - strlen($year);
        $matricSuffix = str_pad($nextCount, $suffixLength, '0', STR_PAD_LEFT);

        return $year . $matricSuffix;
    }

}
