<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use App\Models\Position;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::with(['department', 'position']);
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('employee_id', 'like', "%{$search}%");
            });
        }
        
        // Department filter
        if ($request->filled('department')) {
            $query->where('department_id', $request->department);
        }
        
        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $employees = $query->orderBy('created_at', 'desc')->paginate(5);
        $departments = Department::orderBy('name')->get();
        
        return view('employees.index', compact('employees', 'departments'));
    }

    public function create()
    {
        $departments = Department::orderBy('name')->get();
        $positions = Position::orderBy('title')->get();
        
        return view('employees.create', compact('departments', 'positions'));
    }

   public function store(Request $request)
{
    $validated = $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|email|unique:employees',
        'phone' => 'required|string|max:20',
        'date_of_birth' => 'required|date|before:today',
        'gender' => 'required|in:male,female,other',
        'address' => 'required|string',
        'department_id' => 'required|exists:departments,id',
        'position_id' => 'required|exists:positions,id',
        'hire_date' => 'required|date',
        'salary' => 'required|numeric|min:0',
        'role' => 'required|in:admin,head_department,accountant,team_leader,employee',
        'password' => 'required|string|min:6',
    ]);

    // Generate employee ID
    $validated['employee_id'] = 'EMP' . str_pad(Employee::count() + 1, 5, '0', STR_PAD_LEFT);
    $validated['status'] = 'active';

    // Create Employee
    $employee = Employee::create($validated);

    // Create User account
    User::create([
        'name' => $employee->first_name . ' ' . $employee->last_name,
        'email' => $employee->email,
        'password' => Hash::make($validated['password']),
        'role' => $validated['role'],
        'department_id' => in_array($validated['role'], ['head_department', 'team_leader']) 
            ? $employee->department_id 
            : null,
    ]);

    return redirect()->route('departments.show', $validated['department_id'])
        ->with('success', 'Employee added successfully.');
}

    public function show(Employee $employee)
    {
        $employee->load(['department', 'position']);
        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        $departments = Department::orderBy('name')->get();
        $positions = Position::orderBy('title')->get();
        
        return view('employees.edit', compact('employee', 'departments', 'positions'));
    }

    public function update(Request $request, Employee $employee)
{
    $validated = $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|email|unique:employees,email,' . $employee->id,
        'phone' => 'required|string|max:20',
        'date_of_birth' => 'required|date|before:today',
        'gender' => 'required|in:male,female,other',
        'address' => 'required|string',
        'department_id' => 'required|exists:departments,id',
        'position_id' => 'required|exists:positions,id',
        'hire_date' => 'required|date',
        'salary' => 'required|numeric|min:0',
        'status' => 'required|in:active,inactive,on_leave',
        'role' => 'required|in:admin,head_department,accountant,team_leader,employee',
        'password' => 'nullable|string|min:6',
    ]);

    $employee->update($validated);

    // Update or create User account
    $user = User::where('email', $employee->email)->first();
    
    if ($user) {
        $userData = [
            'name' => $employee->first_name . ' ' . $employee->last_name,
            'email' => $employee->email,
            'role' => $validated['role'],
            'department_id' => in_array($validated['role'], ['head_department', 'team_leader']) 
                ? $employee->department_id 
                : null,
        ];
        
        if (!empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        }
        
        $user->update($userData);
    }

    return redirect()->route('employees.index')
        ->with('success', 'Employee updated successfully.');
}
    public function destroy(Employee $employee)
    {
        $employee->delete();

        return redirect()->route('employees.index')
            ->with('success', 'Employee deleted successfully.');
    }
    public function myProfile()
{
    $user = Auth::user();
    $employee = Employee::where('email', $user->email)
        ->with(['department', 'position'])
        ->first();
    
    if (!$employee) {
        return redirect()->route('dashboard')->with('error', 'Employee record not found.');
    }
    
    return view('profile.show', compact('employee'));
}

public function editProfile()
{
    $user = Auth::user();
    $employee = Employee::where('email', $user->email)->first();
    
    if (!$employee) {
        return redirect()->route('dashboard')->with('error', 'Employee record not found.');
    }
    
    return view('profile.edit', compact('employee'));
}

public function updateProfile(Request $request)
{
    $user = Auth::user();
    $employee = Employee::where('email', $user->email)->first();
    
    $validated = $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'phone' => 'required|string|max:20',
        'address' => 'required|string',
        'password' => 'nullable|string|min:6|confirmed',
    ]);

    $employee->update($validated);

    // Update user name
    $user->update([
        'name' => $validated['first_name'] . ' ' . $validated['last_name'],
    ]);

    // Update password if provided
    if (!empty($validated['password'])) {
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);
    }

    return redirect()->route('profile.show')
        ->with('success', 'Profile updated successfully.');
}
}