<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Utils\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Show login form
    public function showLoginForm()
    {
        return view('login');
    }

    // Handle login request
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();

            if (Auth::user()->first_sign_in) {
                return redirect()->route('password.update', ['role' => Auth::user()->role])->with('error', 'Please update your password');
            }

            // Redirect based on role
            $role = Auth::user()->role;
            return match ($role) {
                Utils::ROLE_ADMIN    => redirect()->route('dashboard', ['role' => Utils::ROLE_ADMIN]),
                Utils::ROLE_LECTURER => redirect()->route('dashboard', ['role' => Utils::ROLE_LECTURER]),
                Utils::ROLE_STUDENT  => redirect()->route('dashboard', ['role' => Utils::ROLE_STUDENT]),
            };
        }

        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ]);
    }

    // Show registration form
    public function showRegistrationForm()
    {
        return view('register');
    }

    // Handle registration
    public function register(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:6'],
            'role'     => ['required', 'in:admin,lecturer,student'],
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        Auth::login($user);

        return match ($user->role) {
            Utils::ROLE_ADMIN    => redirect()->route('dashboard', ['role' => Utils::ROLE_ADMIN]),
            Utils::ROLE_LECTURER => redirect()->route('dashboard', ['role' => Utils::ROLE_LECTURER]),
            Utils::ROLE_STUDENT  => redirect()->route('dashboard', ['role' => Utils::ROLE_STUDENT]),
        };
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function showPasswordUpdateForm(Request $request)
    {
        if (Auth::check()) {
            return view('dashboard.shared.update-password');
        }
        return redirect()->route('login');
    }

    public function updatePassword(Request $request)
    {
        if (Auth::check()) {
            $validated = $request->validate([
                'password' => ['required', 'confirmed', 'min:6'],
            ]);

            User::query()->find(Auth::id())->update([
                'password' => Hash::make($validated['password']),
                'first_sign_in' => false,
            ]);

            return redirect()->route('dashboard', ['role' => Auth::user()->role]);
        }
        return redirect()->route('login');
    }
}
