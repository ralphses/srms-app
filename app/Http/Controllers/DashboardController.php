<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\SchoolSession;
use App\Utils\Utils;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($request->role !== $user->role) {
            return redirect()->route('login');
        }

        $role = $user->role;

        $semesters = Utils::SEMESTERS;
        $sessions = SchoolSession::all('name', 'id');

        switch ($role) {
            case Utils::ROLE_ADMIN:
                return view('dashboard.admin.index');

            case Utils::ROLE_LECTURER:
                $lecturerId = $user->profile()?->id;
                $courses = Course::with('department')
                    ->whereHas('lecturers', function ($q) use ($lecturerId) {
                        $q->where('lecturers.id', $lecturerId);
                    })->get(['id', 'code']);

                return view('dashboard.lecturer.index', compact('semesters', 'courses', 'sessions'));

            case Utils::ROLE_STUDENT:
                $levels = Utils::LEVELS;
                $sessions = $user->profile()->getSessions();
                return view('dashboard.student.index', compact('semesters', 'sessions', 'levels'));

            default:
                return redirect()->route('login');
        }
    }


    public function profile(Request $request)
    {
        $user = $request->user();

        if ($request->role !== $user->role) {
            return redirect()->route('login');
        }

        return view('dashboard.shared.profile', [
            'profile' => $user->profile(),
        ]);
    }

}
