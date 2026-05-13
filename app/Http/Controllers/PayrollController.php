<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $query = Payroll::with('employee')
            ->orderBy('created_at', 'desc');
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }
        
        $payrolls = $query->paginate(15);
        $employees = Employee::where('status', 'active')->orderBy('first_name')->get();
        
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
        $employees = Employee::where('status', 'active')
            ->orderBy('first_name')
            ->get();
        return view('payroll.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'base_salary' => 'required|numeric|min:0',
            'days_worked' => 'required|integer|min:0|max:13',
            'regular_ot_hours' => 'nullable|numeric|min:0',
            'regular_ot_amount' => 'nullable|numeric|min:0',
            'rest_day_ot_hours' => 'nullable|numeric|min:0',
            'rest_day_ot_amount' => 'nullable|numeric|min:0',
            'holiday_ot_hours' => 'nullable|numeric|min:0',
            'holiday_ot_amount' => 'nullable|numeric|min:0',
            'nsd_hours' => 'nullable|numeric|min:0',
            'nsd_amount' => 'nullable|numeric|min:0',
            'rice_subsidy' => 'nullable|numeric|min:0',
            'clothing_allowance' => 'nullable|numeric|min:0',
            'transport_allowance' => 'nullable|numeric|min:0',
            'tardiness_deduction' => 'nullable|numeric|min:0',
            'sss_loan' => 'nullable|numeric|min:0',
            'pagibig_loan' => 'nullable|numeric|min:0',
            'sss_contribution' => 'nullable|numeric|min:0',
            'sss_wisp' => 'nullable|numeric|min:0',
            'philhealth_contribution' => 'nullable|numeric|min:0',
            'pagibig_contribution' => 'nullable|numeric|min:0',
            'withholding_tax' => 'nullable|numeric|min:0',
            'gross_pay' => 'nullable|numeric|min:0',
            'net_salary' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'status' => 'nullable|string',
            'pay_period' => 'nullable|string',
        ]);

        // Calculate rates
        $baseSalary = $validated['base_salary'];
        $dailyRate = $baseSalary / 26;
        $hourlyRate = $dailyRate / 8;

        // Set period dates based on pay_period
        $now = Carbon::now();
        if (($request->pay_period ?? '1-15') === '1-15') {
            $periodStart = $now->copy()->startOfMonth();
            $periodEnd = $now->copy()->startOfMonth()->addDays(14);
        } else {
            $periodStart = $now->copy()->startOfMonth()->addDays(15);
            $periodEnd = $now->copy()->endOfMonth();
        }

        // Generate payroll code
        $validated['payroll_code'] = Payroll::generatePayrollCode();
        $validated['status'] = $validated['status'] ?? 'pending';
        $validated['period_start'] = $periodStart;
        $validated['period_end'] = $periodEnd;
        $validated['daily_rate'] = $dailyRate;
        $validated['hourly_rate'] = $hourlyRate;
        $validated['base_salary'] = $baseSalary;
        $validated['overtime_hours'] = ($validated['regular_ot_hours'] ?? 0) + ($validated['rest_day_ot_hours'] ?? 0) + ($validated['holiday_ot_hours'] ?? 0);
        $validated['overtime_pay'] = ($validated['regular_ot_amount'] ?? 0) + ($validated['rest_day_ot_amount'] ?? 0) + ($validated['holiday_ot_amount'] ?? 0);
        $validated['bonus'] = 0;
        $validated['payment_method'] = $request->payment_method ?? 'bank_transfer';
        
        // Set default 0 for nullable fields
        $fields = [
            'regular_ot_hours', 'regular_ot_amount', 'rest_day_ot_hours', 'rest_day_ot_amount',
            'holiday_ot_hours', 'holiday_ot_amount', 'nsd_hours', 'nsd_amount',
            'rice_subsidy', 'clothing_allowance', 'transport_allowance',
            'tardiness_deduction', 'sss_loan', 'pagibig_loan',
            'sss_contribution', 'sss_wisp', 'philhealth_contribution', 'pagibig_contribution',
            'withholding_tax', 'gross_pay', 'net_salary', 'tax', 'deductions'
        ];
        foreach ($fields as $field) {
            $validated[$field] = $validated[$field] ?? 0;
        }

        Payroll::create($validated);

        return redirect()->route('payroll.index')
            ->with('success', 'Payslip generated successfully.');
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
            'status' => 'nullable|in:draft,pending,approved,paid',
        ]);

        if (isset($validated['status']) && !auth()->user()->isAdmin()) {
            unset($validated['status']);
        }

        $payroll->update($validated);

        return redirect()->route('payroll.index')
            ->with('success', 'Payroll updated successfully.');
    }

    public function destroy(Payroll $payroll)
    {
        $payroll->delete();
        return redirect()->route('payroll.index')
            ->with('success', 'Payroll deleted.');
    }

    public function myPayslips()
    {
        $user = auth()->user();
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