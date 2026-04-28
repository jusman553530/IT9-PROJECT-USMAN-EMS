<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class EmployeeDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $employee = Employee::where('email', $user->email)->first();
        $today = Carbon::today();
        
        // ONLY THE EMPLOYEE'S OWN ATTENDANCE
        $todayAttendance = null;
        if ($employee) {
            $todayAttendance = Attendance::where('employee_id', $employee->id)
                ->whereDate('date', $today)
                ->first();
        }
        
        // My tasks
        // My tasks
$myTasks = collect();
if ($employee) {
    $myTasks = Task::where(function($query) use ($employee) {
            $query->where('assigned_to', $employee->id)
                  ->orWhere('department_id', $employee->department_id);
        })
        ->whereIn('status', ['pending', 'in_progress'])
        ->orderBy('due_date')
        ->take(5)
        ->get();
}
        
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
        
// Add these before return view
$weekStart = Carbon::now()->startOfWeek();
$weekHours = Attendance::where('employee_id', $employee->id)
    ->whereBetween('date', [$weekStart, Carbon::today()])
    ->whereNotNull('time_in')
    ->get()
    ->sum(function($a) {
        if ($a->time_in && $a->time_out) {
            return Carbon::parse($a->time_in)->diffInHours(Carbon::parse($a->time_out));
        }
        return 0;
    });

$monthStart = Carbon::now()->startOfMonth();
$monthHours = Attendance::where('employee_id', $employee->id)
    ->whereBetween('date', [$monthStart, Carbon::today()])
    ->whereNotNull('time_in')
    ->get()
    ->sum(function($a) {
        if ($a->time_in && $a->time_out) {
            return Carbon::parse($a->time_in)->diffInHours(Carbon::parse($a->time_out));
        }
        return 0;
    });

$leaveRequests = \App\Models\LeaveRequest::where('employee_id', $employee->id)->get();
$recentPayslips = \App\Models\Payroll::where('employee_id', $employee->id)
    ->orderBy('period_start', 'desc')
    ->take(3)
    ->get();

return view('dashboard.employee', compact(
    'employee', 'todayAttendance', 'myTasks', 'myAttendanceSummary',
    'weekHours', 'monthHours', 'leaveRequests', 'recentPayslips'
));
    }
}