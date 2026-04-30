<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $query = Payroll::with('employee')
            ->orderBy('created_at', 'desc');
        
        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Period filter
        if ($request->filled('month')) {
            $query->whereMonth('period_start', $request->month)
                  ->whereYear('period_start', $request->year ?? date('Y'));
        }
        
        // Employee filter
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }
        
        $payrolls = $query->paginate(5);
        $employees = Employee::where('status', 'active')->orderBy('first_name')->get();
        
        // Statistics
        $stats = [
            'total_payroll' => Payroll::sum('net_salary'),
            'this_month' => Payroll::whereMonth('period_start', date('m'))
                ->whereYear('period_start', date('Y'))
                ->sum('net_salary'),
            'pending' => Payroll::where('status', 'pending')->count(),
            'paid' => Payroll::where('status', 'paid')->count(),
        ];
        
        return view('payroll.index', compact('payrolls', 'employees', 'stats'));
    }

    public function create()
    {
        $employees = Employee::where('status', 'active')->orderBy('first_name')->get();
        return view('payroll.create', compact('employees'));
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'employee_id' => 'required|exists:employees,id',
        'period_start' => 'required|date',
        'period_end' => 'required|date|after:period_start',
        'base_salary' => 'required|numeric|min:0',
        'overtime_hours' => 'nullable|numeric|min:0',
        'overtime_pay' => 'nullable|numeric|min:0',
        'bonus' => 'nullable|numeric|min:0',
        'deductions' => 'nullable|numeric|min:0',
        'tax' => 'nullable|numeric|min:0',
        'payment_date' => 'nullable|date',
        'payment_method' => 'nullable|string|max:50',
        'notes' => 'nullable|string',
    ]);

    $validated['payroll_code'] = Payroll::generatePayrollCode();
    $validated['status'] = 'pending';
    
    // Divide base salary by 2 for semi-monthly payroll
    $validated['base_salary'] = $validated['base_salary'] / 2;
    
    // Recalculate tax based on halved salary
    $validated['tax'] = $validated['base_salary'] * 0.10;
    $validated['deductions'] = 500 + ($validated['base_salary'] * 0.05); // Health insurance + Provident fund
    
    // Calculate net salary
    $totalEarnings = $validated['base_salary'] + 
                    ($validated['overtime_pay'] ?? 0) + 
                    ($validated['bonus'] ?? 0);
    $totalDeductions = ($validated['deductions'] ?? 0) + ($validated['tax'] ?? 0);
    $validated['net_salary'] = $totalEarnings - $totalDeductions;

    Payroll::create($validated);

    return redirect()->route('payroll.index')
        ->with('success', 'Payroll created and sent for approval.');
}
    public function show(Payroll $payroll)
    {
        $payroll->load('employee.department', 'employee.position');
        return view('payroll.show', compact('payroll'));
    }

    public function edit(Payroll $payroll)
    {
        $employees = Employee::where('status', 'active')->orderBy('first_name')->get();
        return view('payroll.edit', compact('payroll', 'employees'));
    }

   public function update(Request $request, Payroll $payroll)
{
    $validated = $request->validate([
        'employee_id' => 'required|exists:employees,id',
        'period_start' => 'required|date',
        'period_end' => 'required|date|after:period_start',
        'base_salary' => 'required|numeric|min:0',
        'overtime_hours' => 'nullable|numeric|min:0',
        'overtime_pay' => 'nullable|numeric|min:0',
        'bonus' => 'nullable|numeric|min:0',
        'deductions' => 'nullable|numeric|min:0',
        'tax' => 'nullable|numeric|min:0',
        'status' => 'nullable|in:draft,pending,approved,paid', // ADD THIS
        'payment_date' => 'nullable|date',
        'payment_method' => 'nullable|string|max:50',
        'notes' => 'nullable|string',
    ]);

    // Only admin can change status
    if (isset($validated['status']) && !auth()->user()->isAdmin()) {
        unset($validated['status']);
    }

    // Recalculate net salary
    $totalEarnings = $validated['base_salary'] + 
                    ($validated['overtime_pay'] ?? 0) + 
                    ($validated['bonus'] ?? 0);
    $totalDeductions = ($validated['deductions'] ?? 0) + ($validated['tax'] ?? 0);
    $validated['net_salary'] = $totalEarnings - $totalDeductions;

    $payroll->update($validated);

    return redirect()->route('payroll.index')
        ->with('success', 'Payroll updated successfully.');
}
    public function destroy(Payroll $payroll)
    {
        $payroll->delete();

        return redirect()->route('payroll.index')
            ->with('success', 'Payroll deleted successfully.');
    }

    // Bulk generate payroll
    public function bulkGenerate(Request $request)
    {
        $validated = $request->validate([
            'period_start' => 'required|date',
            'period_end' => 'required|date|after:period_start',
            'employee_ids' => 'required|array',
            'employee_ids.*' => 'exists:employees,id',
        ]);

        foreach ($validated['employee_ids'] as $employeeId) {
            $employee = Employee::find($employeeId);
            
            // Check if payroll already exists for this period
            $exists = Payroll::where('employee_id', $employeeId)
                ->where('period_start', $validated['period_start'])
                ->exists();
                
            if (!$exists) {
                Payroll::create([
                    'employee_id' => $employeeId,
                    'payroll_code' => Payroll::generatePayrollCode(),
                    'period_start' => $validated['period_start'],
                    'period_end' => $validated['period_end'],
                    'base_salary' => $employee->salary,
                    'net_salary' => $employee->salary,
                    'status' => 'draft',
                ]);
            }
        }

        return redirect()->route('payroll.index')
            ->with('success', 'Bulk payroll generated successfully.');
    }

    public function myPayslips()
{
    $user = Auth::user();
    $employee = Employee::where('email', $user->email)->first();
    
    if (!$employee) {
        return redirect()->route('dashboard');
    }
    
    $payslips = Payroll::where('employee_id', $employee->id)
        ->orderBy('period_start', 'desc')
        ->get();
    
    return view('payroll.my-payslips', compact('payslips', 'employee'));
}
}