<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Utils\Utils;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request) {
        $role = $request->role;
        $requestRole = $request->user()->role;

        if ($role !== $requestRole) {
            return redirect(route('login'));
        }

        return match ($role) {
            Utils::ROLE_ADMIN    => view('dashboard.admin'),
            Utils::ROLE_LECTURER => view('dashboard.lecturer'),
            Utils::ROLE_STUDENT  => view('dashboard.student'),
        };


    }
}
