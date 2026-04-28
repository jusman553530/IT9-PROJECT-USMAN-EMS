<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index(Request $request)
{
    $query = Attendance::with('employee')
        ->orderBy('date', 'desc')
        ->orderBy('time_in', 'desc');
    
    // Date filter
    if ($request->filled('date')) {
        $query->whereDate('date', $request->date);
    } else {
        $query->whereDate('date', today());
    }
    
    // Employee filter
    if ($request->filled('employee_id')) {
        $query->where('employee_id', $request->employee_id);
    }
    
    // Status filter
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }
    
    $attendances = $query->paginate(15);
    $employees = Employee::where('status', 'active')->orderBy('first_name')->get();
    
    // Statistics - Fix this part
    $stats = [
        'total' => Attendance::whereDate('date', today())->count(),
        'present' => Attendance::whereDate('date', today())->where('status', 'present')->count(),
        'absent' => Attendance::whereDate('date', today())->where('status', 'absent')->count(),
        'late' => Attendance::whereDate('date', today())->where('status', 'late')->count(),
        'on_leave' => Attendance::whereDate('date', today())->where('status', 'on_leave')->count(),
    ];
    
    return view('attendance.index', compact('attendances', 'employees', 'stats'));
}
    public function create()
    {
        $employees = Employee::where('status', 'active')->orderBy('first_name')->get();
        return view('attendance.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'time_in' => 'nullable|date_format:H:i',
            'time_out' => 'nullable|date_format:H:i|after:time_in',
            'status' => 'required|in:present,absent,late,half_day,on_leave',
            'notes' => 'nullable|string|max:255',
        ]);

        // Check if attendance already exists
        $exists = Attendance::where('employee_id', $validated['employee_id'])
            ->whereDate('date', $validated['date'])
            ->exists();
            
        if ($exists) {
            return back()->withErrors(['employee_id' => 'Attendance already marked for this employee on selected date.'])
                         ->withInput();
        }

        Attendance::create($validated);

        return redirect()->route('attendance.index')
            ->with('success', 'Attendance marked successfully.');
    }

    public function show(Attendance $attendance)
    {
        $attendance->load('employee');
        return view('attendance.show', compact('attendance'));
    }

    public function edit(Attendance $attendance)
    {
        $employees = Employee::where('status', 'active')->orderBy('first_name')->get();
        return view('attendance.edit', compact('attendance', 'employees'));
    }

    public function update(Request $request, Attendance $attendance)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'time_in' => 'nullable|date_format:H:i',
            'time_out' => 'nullable|date_format:H:i|after:time_in',
            'status' => 'required|in:present,absent,late,half_day,on_leave',
            'notes' => 'nullable|string|max:255',
        ]);

        $attendance->update($validated);

        return redirect()->route('attendance.index')
            ->with('success', 'Attendance updated successfully.');
    }

    public function destroy(Attendance $attendance)
    {
        $attendance->delete();

        return redirect()->route('attendance.index')
            ->with('success', 'Attendance deleted successfully.');
    }

    // Quick clock in/out
    public function clockIn(Request $request)
    {
        $employee = Employee::findOrFail($request->employee_id);
        
        $attendance = Attendance::firstOrCreate(
            [
                'employee_id' => $employee->id,
                'date' => today(),
            ],
            [
                'time_in' => now()->format('H:i'),
                'status' => 'present'
            ]
        );

        return back()->with('success', 'Clocked in successfully.');
    }

    public function clockOut(Request $request)
    {
        $attendance = Attendance::where('employee_id', $request->employee_id)
            ->whereDate('date', today())
            ->first();

        if ($attendance) {
            $attendance->update(['time_out' => now()->format('H:i')]);
        }

        return back()->with('success', 'Clocked out successfully.');
    }

    // Bulk attendance marking
    public function bulkCreate()
    {
        $employees = Employee::where('status', 'active')->orderBy('first_name')->get();
        return view('attendance.bulk', compact('employees'));
    }

    public function bulkStore(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'employee_ids' => 'required|array',
            'employee_ids.*' => 'exists:employees,id',
            'status' => 'required|in:present,absent,late,half_day,on_leave',
            'time_in' => 'nullable|date_format:H:i',
        ]);

        foreach ($validated['employee_ids'] as $employeeId) {
            Attendance::updateOrCreate(
                [
                    'employee_id' => $employeeId,
                    'date' => $validated['date'],
                ],
                [
                    'time_in' => $validated['time_in'] ?? null,
                    'status' => $validated['status'],
                ]
            );
        }

        return redirect()->route('attendance.index')
            ->with('success', 'Bulk attendance marked successfully.');
    }

    public function myAttendance()
{
    $user = Auth::user();
    $employee = Employee::where('email', $user->email)->first();
    
    if (!$employee) {
        return redirect()->route('dashboard');
    }
    
    $attendances = Attendance::where('employee_id', $employee->id)
        ->orderBy('date', 'desc')
        ->paginate(15);
    
    return view('attendance.my', compact('attendances', 'employee'));
}
}