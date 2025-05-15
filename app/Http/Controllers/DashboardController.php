<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
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

        return match ($user->role) {
            Utils::ROLE_ADMIN => view('dashboard.admin.index'),
            Utils::ROLE_LECTURER => view('dashboard.lecturer'),
            Utils::ROLE_STUDENT => (function () {
                $semesters = Utils::SEMESTERS;
                $sessions = SchoolSession::all('name', 'id');
                return view('dashboard.student.index', compact('semesters', 'sessions'));
            })(),
            default => redirect()->route('login'),
        };
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
