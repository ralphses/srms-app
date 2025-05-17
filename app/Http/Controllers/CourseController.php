<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Department;
use App\Models\Lecturer;
use App\Utils\Utils;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Course::with('department');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('program_type')) {
            $query->where('program_type', $request->program_type);
        }

        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        if ($request->filled('lecturer')) {
            $query->whereHas('lecturers', function ($q) use ($request) {
                $q->where('lecturer_id', $request->lecturer);
            });
        }

        $courses = $query->orderBy('level')->paginate(10)->withQueryString();

        return view('dashboard.admin.course', [
            'courses' => $courses,
            'departments' => Department::all(),
            'programTypes' => [Utils::PROGRAM_TYPE_DEGREE, Utils::PROGRAM_TYPE_DIPLOMA]
        ]);
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.admin.add-course', [
            'departments' => Department::all(),
            'programTypes' => [Utils::PROGRAM_TYPE_DEGREE, Utils::PROGRAM_TYPE_DIPLOMA], // Or from Utils::PROGRAM_TYPES
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:courses,code',
            'unit' => 'required|numeric|min:1',
            'level' => ['integer', 'required', Rule::in(Utils::LEVELS)],
            'semester' => 'required|in:first,second',
            'program_type' => 'required|string',
            'department_id' => 'required|exists:departments,id',
        ]);

        Course::create($request->only([
            'name', 'code', 'unit', 'level', 'semester', 'program_type', 'department_id'
        ]));

        return redirect()->route('courses.index', ['role' => auth()->user()->role])
            ->with('success', 'Course created successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        //
    }
}
