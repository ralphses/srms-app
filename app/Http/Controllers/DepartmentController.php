<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Department::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $departments = $query->orderBy('name')->paginate(10)->withQueryString();

        return view('dashboard.admin.departments', compact('departments'));
    }

    public function create() {
        return view('dashboard.admin.add-department');
    }


    public function store(Request $request) {

        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:departments,code',
        ]);

        Department::create($request->only('name', 'code'));

        return redirect()
            ->route('departments.index', ['role' => auth()->user()->role])
            ->with('success', 'Department created successfully.');
    }


}
