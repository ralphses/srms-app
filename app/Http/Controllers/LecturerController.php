<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Department;
use App\Models\Lecturer;
use App\Models\User;
use App\Utils\Utils;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class LecturerController extends Controller
{
    public function index(Request $request)
    {
        $query = Lecturer::with(['user', 'department']);

        if ($request->filled('department')) {
            $query->where('department_id', $request->department);
        }

        if ($request->filled('program_type')) {
            $query->where('program_type', $request->program_type);
        }

        if ($search = $request->input('search')) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            });
        }

        return view('dashboard.admin.lecturers', [
            'lecturers' => $query->paginate(10)->withQueryString(),
            'departments' => Department::all(),
            'programTypes' => [Utils::PROGRAM_TYPE_DIPLOMA, Utils::PROGRAM_TYPE_DEGREE],
            'courses' => Course::query()->get(['id', 'name']),
        ]);
    }

    public function create()
    {
        return view('dashboard.admin.add-lecturer', [
            'departments' => Department::all(),
            'levels' => Utils::LEVELS,
            'programTypes' => [Utils::PROGRAM_TYPE_DIPLOMA, Utils::PROGRAM_TYPE_DEGREE],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'department_id' => 'required|exists:departments,id',
            'level' => ['integer', 'required', Rule::in(Utils::LEVELS)],
            'program_type' => ['required', Rule::in([Utils::PROGRAM_TYPE_DIPLOMA, Utils::PROGRAM_TYPE_DEGREE])],
        ]);

        try {
            DB::beginTransaction();

            $user = User::query()->create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make(Utils::defaultPassword()),
                'role' => Utils::ROLE_LECTURER,
            ]);

            Lecturer::query()->create([
                'user_id' => $user->id,
                'staff_id' => Utils::generateStaffId($user->role),
                'department_id' => $validated['department_id'],
                'level' => $validated['level'],
                'program_type' => $validated['program_type'],
            ]);

            DB::commit();
            return redirect()->route('lecturers.index', ['role' => auth()->user()->role])
                ->with('success', 'Lecturer created successfully.');
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception);
            return redirect()->route('lecturers.index', ['role' => auth()->user()->role])
                ->with('error', 'Something went wrong.');
        }

    }

    public function viewCourses(Request $request)
    {
        $lecturerId = $request->lecturer ?? false;

        if (!$lecturerId) {
            return redirect()->route('lecturers.index', ['role' => $request->user()->role]);
        }
        return redirect(route('courses.index', ['role' => $request->user()->role, 'lecturer' => $lecturerId]));
    }

    public function viewStudents(Lecturer $lecturer)
    {
        $students = $lecturer->courses()
            ->with(['courseRegistrations.student.user'])
            ->get()
            ->pluck('courseRegistrations')
            ->flatten()
            ->pluck('student')
            ->unique('id')
            ->filter()
            ->values();

        return view('dashboard.admin.lecturers-students', [
            'lecturer' => $lecturer,
            'students' => $students,
        ]);
    }



    public function assignCourse(Request $request)
    {
        $request->validate([
            'courses' => 'required|array',
            'courses.*' => 'exists:courses,id',
        ]);

        $lecturer = Lecturer::findOrFail($request->lecturer);
        $coursesToAssign = $request->input('courses', []);

        $existingCourseIds = $lecturer->courses()->pluck('courses.id')->toArray();
        $newCourses = array_diff($coursesToAssign, $existingCourseIds);

        if (empty($newCourses)) {
            return redirect()->to(route('lecturers.index', ['role' => auth()->user()->role]))->with('error', 'All selected courses are already assigned to this lecturer.');
        }

        DB::transaction(function () use ($lecturer, $newCourses) {
            $lecturer->courses()->attach($newCourses);
        });

        return redirect()->to(route('lecturers.index', ['role' => auth()->user()->role]))->with('success', 'Selected courses assigned successfully.');
    }


    public function show(Lecturer $lecturer)
    {
        return view('dashboard.admin.lecturers-show', [
            'lecturer' => $lecturer->load('user', 'department', 'courses'),
        ]);
    }

    public function edit(Lecturer $lecturer)
    {
        return view('dashboard.admin.lecturers-edit', [
            'lecturer' => $lecturer->load('user'),
            'departments' => Department::all(),
            'programTypes' => [Utils::PROGRAM_TYPE_DIPLOMA, Utils::PROGRAM_TYPE_DEGREE],
        ]);
    }
}
