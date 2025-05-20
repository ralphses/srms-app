<?php

namespace App\Http\Controllers;

use App\Models\SchoolSession;
use App\Utils\Utils;
use Illuminate\Container\Attributes\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SchoolSessionController extends Controller
{
    /**
     * Display a listing of school sessions, with optional search.
     */
    public function index(Request $request)
    {
        $query = SchoolSession::query();

        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%$search%");
        }

        $schoolSessions = $query->orderBy('name')->paginate(10)->withQueryString();

        return view('dashboard.admin.sessions', [
            'schoolSessions' => $schoolSessions,
        ]);
    }

    /**
     * Show the form for creating a new school session.
     */
    public function create()
    {
        return view('dashboard.admin.add-session');
    }

    /**
     * Store a newly created school session in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:school_sessions,name'],
            'first_semester_start_date' => ['required', 'date'],
            'second_semester_start_date' => ['required', 'date', 'after_or_equal:first_semester_start_date'],
        ]);

        $newSession = new SchoolSession($validated);
        $newSession->current_semester = $newSession->currentSemester();
        $newSession->save();

        return redirect()->route('sessions.index', ['role' => $request->user()->role])
            ->with('success', 'School session created successfully.');
    }

    public function show(Request $request)
    {
        try {
            $sessionId = $request->sessionId;
            $session = SchoolSession::findOrFail($sessionId);
            return view('dashboard.admin.update-session', ['session' => $session]);
        }catch (ModelNotFoundException $exception){
            \Illuminate\Support\Facades\Log::error($exception->getMessage());
            return back()->withErrors("School session not found");
        }
    }

    public function update(Request $request)
    {
        $sessionId = $request->sessionId;

        $validated = $request->validate([
            'name' => ['required'],
            'first_semester_start_date' => ['required', 'date'],
            'second_semester_start_date' => ['required', 'date', 'after_or_equal:first_semester_start_date'],
            'current_semester' => ['required', Rule::in(array_keys(Utils::SEMESTERS))],
        ]);

        try {
            $session = SchoolSession::findOrFail($sessionId);
            $session->update($validated);
            return redirect()->route('sessions.index', ['role' => $request->user()->role]);
        }catch (ModelNotFoundException $exception){
            \Illuminate\Support\Facades\Log::error($exception->getMessage());
            return back()->withErrors("School session not found");
        }
    }

}
