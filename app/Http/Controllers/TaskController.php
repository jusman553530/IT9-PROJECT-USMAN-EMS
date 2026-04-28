<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::with(['department', 'assignedEmployee', 'assignedBy'])
            ->orderBy('created_at', 'desc');
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        
        // Filter by department
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }
        
        // Filter by assigned employee
        if ($request->filled('employee_id')) {
            $query->where('assigned_to', $request->employee_id);
        }
        
        $tasks = $query->paginate(15);
        $departments = Department::orderBy('name')->get();
        $employees = Employee::where('status', 'active')->orderBy('first_name')->get();
        
        // Statistics
        $stats = [
            'total' => Task::count(),
            'pending' => Task::where('status', 'pending')->count(),
            'in_progress' => Task::where('status', 'in_progress')->count(),
            'completed' => Task::where('status', 'completed')->count(),
            'overdue' => Task::where('status', '!=', 'completed')
                ->whereDate('due_date', '<', now())
                ->count(),
        ];
        
        return view('tasks.index', compact('tasks', 'departments', 'employees', 'stats'));
    }

    public function create()
    {
        $departments = Department::orderBy('name')->get();
        $employees = Employee::where('status', 'active')->orderBy('first_name')->get();
        
        return view('tasks.create', compact('departments', 'employees'));
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'department_id' => 'required|exists:departments,id',
        'priority' => 'required|in:low,medium,high,urgent',
        'due_date' => 'nullable|date|after:today',
        'notes' => 'nullable|string',
    ]);

    $validated['task_code'] = Task::generateTaskCode();
    $validated['assigned_by'] = Auth::id();
    $validated['status'] = 'pending';

    Task::create($validated);

    return redirect()->route('tasks.index')
        ->with('success', 'Task assigned to department successfully.');
}
    public function show(Task $task)
    {
        $task->load(['department', 'assignedEmployee', 'assignedBy']);
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $departments = Department::orderBy('name')->get();
        $employees = Employee::where('status', 'active')->orderBy('first_name')->get();
        
        return view('tasks.edit', compact('task', 'departments', 'employees'));
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'department_id' => 'nullable|exists:departments,id',
            'assigned_to' => 'nullable|exists:employees,id',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'due_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        // Set completed_at if status changed to completed
        if ($validated['status'] === 'completed' && $task->status !== 'completed') {
            $validated['completed_at'] = now();
        }

        $task->update($validated);

        return redirect()->route('tasks.index')
            ->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('tasks.index')
            ->with('success', 'Task deleted successfully.');
    }

    // Get employees by department (AJAX)
    public function getEmployeesByDepartment($departmentId)
    {
        $employees = Employee::where('department_id', $departmentId)
            ->where('status', 'active')
            ->orderBy('first_name')
            ->get(['id', 'first_name', 'last_name', 'employee_id']);
        
        return response()->json($employees);
    }

    // My Tasks (for employees)
    public function myTasks()
{
    $user = Auth::user();
    $employee = Employee::where('email', $user->email)->first();
    
    if (!$employee) {
        return redirect()->route('dashboard')->with('error', 'Employee record not found.');
    }
    
    // Get tasks for employee's department OR assigned directly to them
    $tasks = Task::where(function($query) use ($employee) {
            $query->where('assigned_to', $employee->id)
                  ->orWhere('department_id', $employee->department_id);
        })
        ->with(['department', 'assignedBy'])
        ->orderBy('created_at', 'desc')
        ->paginate(15);
    
    $stats = [
        'total' => Task::where(function($q) use ($employee) {
            $q->where('assigned_to', $employee->id)
              ->orWhere('department_id', $employee->department_id);
        })->count(),
        'pending' => Task::where(function($q) use ($employee) {
            $q->where('assigned_to', $employee->id)
              ->orWhere('department_id', $employee->department_id);
        })->where('status', 'pending')->count(),
        'completed' => Task::where(function($q) use ($employee) {
            $q->where('assigned_to', $employee->id)
              ->orWhere('department_id', $employee->department_id);
        })->where('status', 'completed')->count(),
        'overdue' => Task::where(function($q) use ($employee) {
            $q->where('assigned_to', $employee->id)
              ->orWhere('department_id', $employee->department_id);
        })->where('status', '!=', 'completed')
          ->whereDate('due_date', '<', now())->count(),
    ];
    
    return view('tasks.my-tasks', compact('tasks', 'employee', 'stats'));
}

    // Update task status (quick update)
    public function updateStatus(Request $request, Task $task)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled',
        ]);

        if ($validated['status'] === 'completed' && $task->status !== 'completed') {
            $task->completed_at = now();
        }

        $task->status = $validated['status'];
        $task->save();

        return back()->with('success', 'Task status updated.');
    }
}