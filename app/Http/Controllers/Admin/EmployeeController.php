<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = User::withCount(['assignedJobs as total_jobs'])
            ->withCount(['assignedJobs as completed_jobs' => function ($q) {
                $q->where('status', 'completed');
            }])
            ->orderBy('name')
            ->get();

        return view('admin.employees.index', compact('employees'));
    }

    public function create()
    {
        return view('admin.employees.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:admin,manager,tech',
            'hourly_rate' => 'nullable|numeric|min:0',
            'color' => 'nullable|string|max:7',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = $request->boolean('is_active', true);

        $employee = User::create($validated);

        return redirect()->route('admin.employees.show', $employee)
            ->with('success', 'Employee created successfully.');
    }

    public function show(User $employee)
    {
        $employee->load(['assignedJobs' => function ($q) {
            $q->with(['customer', 'service'])
              ->orderByDesc('scheduled_date')
              ->limit(10);
        }]);

        return view('admin.employees.show', compact('employee'));
    }

    public function edit(User $employee)
    {
        return view('admin.employees.edit', compact('employee'));
    }

    public function update(Request $request, User $employee)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $employee->id,
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:admin,manager,tech',
            'hourly_rate' => 'nullable|numeric|min:0',
            'color' => 'nullable|string|max:7',
            'is_active' => 'boolean',
        ]);

        $employee->update($validated);

        return redirect()->route('admin.employees.show', $employee)
            ->with('success', 'Employee updated successfully.');
    }

    public function destroy(User $employee)
    {
        $employee->update(['is_active' => false]);

        return redirect()->route('admin.employees.index')
            ->with('success', 'Employee deactivated successfully.');
    }
}
