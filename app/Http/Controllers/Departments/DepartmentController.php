<?php

namespace App\Http\Controllers\Departments;

use App\Domains\Departments\Models\Department;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::withCount('categories', 'reports')->get();

        return Inertia::render('Departments/Index', [
            'departments' => $departments,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:departments',
            'description' => 'nullable|string',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);

        Department::create($validated);

        return back()->with('success', 'Dinas berhasil ditambahkan.');
    }

    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:departments,code,' . $department->id,
            'description' => 'nullable|string',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);

        $department->update($validated);

        return back()->with('success', 'Dinas berhasil diperbarui.');
    }

    public function destroy(Department $department)
    {
        $department->delete();

        return back()->with('success', 'Dinas berhasil dihapus.');
    }
}
