<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Payroll;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AccountantDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $employee = Employee::where('email', $user->email)->first();
        $today = Carbon::today();
        
        // ONLY THE ACCOUNTANT'S OWN ATTENDANCE
        $todayAttendance = null;
        if ($employee) {
            $todayAttendance = Attendance::where('employee_id', $employee->id)
                ->whereDate('date', $today)
                ->first();
        }
        
        // Payroll stats
        $pendingPayroll = Payroll::where('status', 'pending')->count();
        $pendingAmount = Payroll::where('status', 'pending')->sum('net_salary');
        $paidThisMonth = Payroll::where('status', 'paid')
            ->whereMonth('payment_date', date('m'))
            ->sum('net_salary');
        $totalPayroll = Payroll::whereMonth('created_at', date('m'))->sum('net_salary');
        
        $recentPayrolls = Payroll::with('employee')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        // My attendance summary for this month
        $myAttendanceSummary = [];
        if ($employee) {
            $thisMonth = Carbon::today();
            $myAttendanceSummary = [
                'present' => Attendance::where('employee_id', $employee->id)
                    ->whereMonth('date', $thisMonth->month)
                    ->whereYear('date', $thisMonth->year)
                    ->whereIn('status', ['present', 'late'])->count(),
                'absent' => Attendance::where('employee_id', $employee->id)
                    ->whereMonth('date', $thisMonth->month)
                    ->whereYear('date', $thisMonth->year)
                    ->where('status', 'absent')->count(),
                'on_leave' => Attendance::where('employee_id', $employee->id)
                    ->whereMonth('date', $thisMonth->month)
                    ->whereYear('date', $thisMonth->year)
                    ->where('status', 'on_leave')->count(),
            ];
        }
        
        return view('dashboard.accountant', compact(
            'employee', 'todayAttendance', 'pendingPayroll', 'pendingAmount',
            'paidThisMonth', 'totalPayroll', 'recentPayrolls', 'myAttendanceSummary'
        ));
    }
}