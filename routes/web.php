<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\ProblemReportController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\Admin\ProblemReportController as AdminProblemReportController;
use Illuminate\Support\Facades\Route;




Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// All authenticated routes
Route::middleware('auth')->group(function () {
    
    // ===== EVERYONE (All Roles) =====
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/', fn() => redirect()->route('dashboard'));

    Route::get('/my-profile', [EmployeeController::class, 'myProfile'])->name('profile.show');
    Route::get('/my-profile/edit', [EmployeeController::class, 'editProfile'])->name('profile.edit');
    Route::put('/my-profile/update', [EmployeeController::class, 'updateProfile'])->name('profile.update');
    
    Route::post('/clock-in', [AttendanceController::class, 'clockIn'])->name('attendance.clock-in');
    Route::post('/clock-out', [AttendanceController::class, 'clockOut'])->name('attendance.clock-out');
    
    Route::get('/my-tasks', [TaskController::class, 'myTasks'])->name('tasks.my');
    Route::post('/tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.status');
    
    Route::get('/my-attendance', [AttendanceController::class, 'myAttendance'])->name('attendance.my');
    
    Route::get('/my-leaves', [LeaveController::class, 'myLeaves'])->name('leaves.my');
    Route::post('/leaves', [LeaveController::class, 'store'])->name('leaves.store');
    
    Route::get('/my-payslips', [PayrollController::class, 'myPayslips'])->name('payslips.my');
    
    Route::get('/report-problem', [ProblemReportController::class, 'index'])->name('reports.index');
    Route::post('/report-problem', [ProblemReportController::class, 'store'])->name('reports.store');
    Route::get('/report-problem/{report}', [ProblemReportController::class, 'show'])->name('reports.show');
    
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    // ===== ADMIN + ACCOUNTANT ROUTES =====
    Route::resource('payroll', PayrollController::class);
    Route::post('payroll/bulk/generate', [PayrollController::class, 'bulkGenerate'])->name('payroll.bulk-generate');
    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
    Route::get('/employees/{employee}', [EmployeeController::class, 'show'])->name('employees.show');
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
    Route::get('/departments/{department}', [DepartmentController::class, 'show'])->name('departments.show');
    Route::resource('expenses', ExpenseController::class)->except(['edit', 'update', 'destroy']);
    Route::post('expenses/{expense}/approve', [ExpenseController::class, 'approve'])->name('expenses.approve');
    Route::post('expenses/{expense}/reject', [ExpenseController::class, 'reject'])->name('expenses.reject');
    
    // ===== ADMIN ONLY =====
    Route::resource('employees', EmployeeController::class)->except(['index', 'show']);
    Route::resource('departments', DepartmentController::class)->except(['index', 'show']);
    Route::post('/positions', [PositionController::class, 'store'])->name('positions.store');
    Route::put('/positions/{position}', [PositionController::class, 'update'])->name('positions.update');
    Route::delete('/positions/{position}', [PositionController::class, 'destroy'])->name('positions.destroy');
    Route::resource('attendance', AttendanceController::class)->except(['index']);
    Route::get('attendance/bulk/create', [AttendanceController::class, 'bulkCreate'])->name('attendance.bulk-create');
    Route::post('attendance/bulk/store', [AttendanceController::class, 'bulkStore'])->name('attendance.bulk-store');
    Route::resource('tasks', TaskController::class);
    Route::resource('leaves', LeaveController::class)->except(['edit', 'update']);
    Route::post('leaves/{leave}/approve', [LeaveController::class, 'approve'])->name('leaves.approve');
    Route::post('leaves/{leave}/reject', [LeaveController::class, 'reject'])->name('leaves.reject');
    Route::get('/admin/reports', [AdminProblemReportController::class, 'index'])->name('admin.reports.index');
    Route::get('/admin/reports/{report}', [AdminProblemReportController::class, 'show'])->name('admin.reports.show');
    Route::post('/admin/reports/{report}/respond', [AdminProblemReportController::class, 'respond'])->name('admin.reports.respond');
    Route::post('/admin/reports/{report}/status', [AdminProblemReportController::class, 'updateStatus'])->name('admin.reports.status');
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('admin.activity-logs');
});