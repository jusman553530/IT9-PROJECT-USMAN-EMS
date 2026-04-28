<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    public function index(Request $request)
    {
        $query = LeaveRequest::with(['employee', 'approvedBy'])
            ->orderBy('created_at', 'desc');
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }
        
        $leaveRequests = $query->paginate(15);
        $employees = Employee::where('status', 'active')->orderBy('first_name')->get();
        
        $stats = [
            'pending' => LeaveRequest::where('status', 'pending')->count(),
            'approved' => LeaveRequest::where('status', 'approved')->whereMonth('created_at', date('m'))->count(),
            'rejected' => LeaveRequest::where('status', 'rejected')->whereMonth('created_at', date('m'))->count(),
        ];
        
        return view('leaves.index', compact('leaveRequests', 'employees', 'stats'));
    }

    public function create()
    {
        $employees = Employee::where('status', 'active')->orderBy('first_name')->get();
        return view('leaves.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'type' => 'required|in:sick,vacation,personal,maternity,paternity,other',
            'reason' => 'required|string|max:500',
        ]);

        LeaveRequest::create($validated);

        return redirect()->route('leaves.index')
            ->with('success', 'Leave request submitted successfully.');
    }

    public function show(LeaveRequest $leave)
    {
        $leave->load(['employee.department', 'approvedBy']);
        return view('leaves.show', compact('leave'));
    }

    public function approve(LeaveRequest $leave)
    {
        $leave->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
        ]);
        
        // Update employee status to on_leave if leave period includes today
        $employee = $leave->employee;
        if ($employee && now()->between($leave->start_date, $leave->end_date)) {
            $employee->update(['status' => 'on_leave']);
        }

        return back()->with('success', 'Leave request approved.');
    }

    public function reject(Request $request, LeaveRequest $leave)
    {
        $validated = $request->validate([
            'admin_notes' => 'required|string|max:255',
        ]);
        
        $leave->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'admin_notes' => $validated['admin_notes'],
        ]);

        return back()->with('success', 'Leave request rejected.');
    }

    public function destroy(LeaveRequest $leave)
    {
        $leave->delete();
        return redirect()->route('leaves.index')
            ->with('success', 'Leave request deleted.');
    }

    // Employee: My leave requests
    public function myLeaves()
    {
        $user = Auth::user();
        $employee = Employee::where('email', $user->email)->first();
        
        if (!$employee) {
            return redirect()->route('dashboard');
        }
        
        $leaveRequests = LeaveRequest::where('employee_id', $employee->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('leaves.my-leaves', compact('leaveRequests', 'employee'));
    }
}