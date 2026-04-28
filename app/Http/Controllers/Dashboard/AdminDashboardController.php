<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Attendance;
use App\Models\Task;
use App\Models\Payroll;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $employee = Employee::where('email', $user->email)->first();
        $today = Carbon::today();
        
        $todayAttendance = null;
        if ($employee) {
            $todayAttendance = Attendance::where('employee_id', $employee->id)
                ->whereDate('date', $today)
                ->first();
        }
        
        // Company-wide stats
        $totalEmployees = Employee::count();
        $totalDepartments = Department::count();
        
        $presentCount = Attendance::whereDate('date', $today)
            ->whereIn('status', ['present', 'late'])->count();
        $leaveCount = Attendance::whereDate('date', $today)
            ->where('status', 'on_leave')->count();
        $absentCount = Attendance::whereDate('date', $today)
            ->where('status', 'absent')->count();
        
        $presentPercentage = $totalEmployees > 0 ? round(($presentCount / $totalEmployees) * 100) : 0;
        $leavePercentage = $totalEmployees > 0 ? round(($leaveCount / $totalEmployees) * 100) : 0;
        $absentPercentage = $totalEmployees > 0 ? round(($absentCount / $totalEmployees) * 100) : 0;
        $attendanceRate = $presentPercentage;
        
        $pendingPayroll = Payroll::where('status', 'pending')->sum('net_salary');
        $paidThisMonth = Payroll::where('status', 'paid')
            ->whereMonth('payment_date', date('m'))
            ->sum('net_salary');
        
        $pendingTasks = Task::where('status', 'pending')->count();
        $overdueTasks = Task::where('status', '!=', 'completed')
            ->whereDate('due_date', '<', now())->count();
        
        // Recent activities
        $recentActivities = $this->getRecentActivities();
        
        // Recent employees
        $recentEmployees = Employee::with('department')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        return view('dashboard.admin', compact(
            'totalEmployees', 'totalDepartments', 'attendanceRate',
            'presentCount', 'presentPercentage', 'leaveCount', 'leavePercentage',
            'absentCount', 'absentPercentage', 'employee', 'todayAttendance',
            'pendingPayroll', 'paidThisMonth', 'recentEmployees',
            'pendingTasks', 'overdueTasks', 'recentActivities'
        ));
    }
    
    private function getRecentActivities()
    {
        $activities = [];
        
        $recentEmployees = Employee::orderBy('created_at', 'desc')->take(3)->get();
        foreach ($recentEmployees as $emp) {
            $activities[] = [
                'action' => 'New employee onboarded',
                'name' => $emp->full_name,
                'time' => $emp->created_at->diffForHumans()
            ];
        }
        
        $recentDepts = Department::orderBy('created_at', 'desc')->take(2)->get();
        foreach ($recentDepts as $dept) {
            $activities[] = [
                'action' => 'Department created',
                'name' => $dept->name,
                'time' => $dept->created_at->diffForHumans()
            ];
        }
        
        return $activities;
    }
}