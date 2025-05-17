<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseRegistration;
use App\Models\SchoolSession;
use App\Utils\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Throwable;

class CourseRegistrationController extends Controller
{
    public function create(Request $request)
    {
        $user = $request->user();

        // Authorization check
        if (!in_array($user->role, [Utils::ROLE_STUDENT, Utils::ROLE_LECTURER])) {
            return redirect()->route('login')->with('error', 'Unauthorized access.');
        }

        // Validate session and semester
        $validated = $request->validate([
            'school_session' => ['required', Rule::exists('school_sessions', 'id')],
            'semester'       => ['required', Rule::in(array_keys(Utils::SEMESTERS))],
        ]);

        // Fetch user profile
        $profile = $user->profile(); // handles both student or lecturer via dynamic relation

        // Load course options
        $availableCourses = Course::where([
            'department'    => $profile->department,
            'level'         => $profile->next_level,
            'program_type'  => $profile->program_type,
        ])->get();

        return view('dashboard.student.course-registration', [
            'session'          => SchoolSession::find($validated['school_session']),
            'semester'         => $validated['semester'],
            'department'       => $profile->department,
            'nextLevel'        => $profile->next_level,
            'availableCourses' => $availableCourses,
        ]);
    }

    public function submit(Request $request)
    {
        $user = $request->user();
        $profile = $user->profile();

        $validated = $request->validate([
            'semester'          => ['required', Rule::in(array_keys(Utils::SEMESTERS))],
            'session'           => ['required', Rule::exists('school_sessions', 'name')],
            'selected_courses'  => ['required'],
        ]);

        $selectedCourses = collect(json_decode($validated['selected_courses'], true))
            ->pluck('id')
            ->toArray();

        DB::beginTransaction();

        try {
            $session = SchoolSession::where('name', $validated['session'])->firstOrFail();

            $data = [
                'student_id'        => $profile->id,
                'semester'          => $validated['semester'],
                'level'             => $profile->next_level,
                'school_session_id' => $session->id,
            ];

            $registration = CourseRegistration::updateOrCreate(
                $data,
                $data // same data for update
            );

            $registration->courses()->sync($selectedCourses);

            DB::commit();

            return redirect()->route('dashboard', ['role' => $user->role])
                ->with('success', 'Registration successful.');

        } catch (Throwable $e) {
            DB::rollBack();

            return redirect()->route('dashboard', ['role' => $user->role])
                ->with('error', 'Registration failed: ' . $e->getMessage());
        }
    }


}
