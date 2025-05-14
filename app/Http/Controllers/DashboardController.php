<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
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
            Utils::ROLE_ADMIN => view('dashboard.admin'),
            Utils::ROLE_LECTURER => view('dashboard.lecturer'),
            Utils::ROLE_STUDENT => view('dashboard.student'),
            default => view('login')
        };
    }

    public function profile(Request $request) {

        $user = $request->user();

        if ($request->role !== $user->role) {
            return redirect()->route('login');
        }

        return $user->profile();
    }
}
