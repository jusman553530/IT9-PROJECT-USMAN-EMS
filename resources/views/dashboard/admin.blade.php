@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div>
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Admin Dashboard</h1>
        <p class="text-gray-600">Welcome back, {{ Auth::user()->name }}! Here's your company overview.</p>
    </div>

    <!-- My Attendance Card -->
    @if($employee)
    <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">My Today's Attendance</h3>
        @if($todayAttendance)
            <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                <div class="flex items-center gap-6">
                    <div>
                        <p class="text-sm text-gray-600">Clock In</p>
                        <p class="text-xl font-semibold">{{ $todayAttendance->time_in ? \Carbon\Carbon::parse($todayAttendance->time_in)->format('h:i A') : '--:--' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Clock Out</p>
                        <p class="text-xl font-semibold">{{ $todayAttendance->time_out ? \Carbon\Carbon::parse($todayAttendance->time_out)->format('h:i A') : '--:--' }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <span class="px-3 py-1 rounded-full text-sm font-medium
                        {{ $todayAttendance->status === 'present' ? 'bg-green-100 text-green-700' : 
                           ($todayAttendance->status === 'late' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                        {{ ucfirst($todayAttendance->status) }}
                    </span>
                    @if(!$todayAttendance->time_out)
                        <form action="{{ route('attendance.clock-out') }}" method="POST">
                            @csrf
                            <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                            <button type="submit" class="px-4 py-2 text-white rounded-lg" style="background-color: #0C521C;">Clock Out</button>
                        </form>
                    @endif
                </div>
            </div>
        @else
            <div class="flex justify-between items-center">
                <p class="text-gray-500">No attendance recorded for today.</p>
                <form action="{{ route('attendance.clock-in') }}" method="POST">
                    @csrf
                    <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                    <button type="submit" class="px-4 py-2 text-white rounded-lg" style="background-color: #0C521C;">Clock In</button>
                </form>
            </div>
        @endif
    </div>
    @endif

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <span class="text-sm text-gray-600">Total Employees</span>
            </div>
            <p class="text-3xl font-semibold text-gray-900">{{ $totalEmployees }}</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-lg bg-emerald-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <span class="text-sm text-gray-600">Departments</span>
            </div>
            <p class="text-3xl font-semibold text-gray-900">{{ $totalDepartments }}</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-lg bg-teal-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <span class="text-sm text-gray-600">Attendance Today</span>
            </div>
            <p class="text-3xl font-semibold text-gray-900">{{ $attendanceRate }}%</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"></path>
                    </svg>
                </div>
                <span class="text-sm text-gray-600">Pending Tasks</span>
            </div>
            <p class="text-3xl font-semibold text-gray-900">{{ $pendingTasks }}</p>
            @if($overdueTasks > 0)
                <p class="text-sm text-red-600 mt-1">{{ $overdueTasks }} overdue</p>
            @endif
        </div>
    </div>

    <!-- Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Today's Attendance Overview -->
        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Today's Attendance Overview</h2>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 bg-green-50 rounded-lg">
                    <div>
                        <p class="text-sm text-green-700 font-medium">Present</p>
                        <p class="text-2xl font-semibold text-green-900">{{ $presentCount }}</p>
                    </div>
                    <div class="w-16 h-16 rounded-full bg-green-200 flex items-center justify-center">
                        <span class="text-lg font-semibold text-green-700">{{ $presentPercentage }}%</span>
                    </div>
                </div>
                <div class="flex items-center justify-between p-4 bg-yellow-50 rounded-lg">
                    <div>
                        <p class="text-sm text-yellow-700 font-medium">On Leave</p>
                        <p class="text-2xl font-semibold text-yellow-900">{{ $leaveCount }}</p>
                    </div>
                    <div class="w-16 h-16 rounded-full bg-yellow-200 flex items-center justify-center">
                        <span class="text-lg font-semibold text-yellow-700">{{ $leavePercentage }}%</span>
                    </div>
                </div>
                <div class="flex items-center justify-between p-4 bg-red-50 rounded-lg">
                    <div>
                        <p class="text-sm text-red-700 font-medium">Absent</p>
                        <p class="text-2xl font-semibold text-red-900">{{ $absentCount }}</p>
                    </div>
                    <div class="w-16 h-16 rounded-full bg-red-200 flex items-center justify-center">
                        <span class="text-lg font-semibold text-red-700">{{ $absentPercentage }}%</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payroll Summary -->
        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Payroll Summary</h2>
            <div class="space-y-4">
                <div class="flex justify-between p-4 bg-gray-50 rounded-lg">
                    <div>
                        <p class="text-sm text-gray-600">Pending Payroll</p>
                        <p class="text-2xl font-semibold text-gray-900">${{ number_format($pendingPayroll, 2) }}</p>
                    </div>
                </div>
                <div class="flex justify-between p-4 bg-gray-50 rounded-lg">
                    <div>
                        <p class="text-sm text-gray-600">Paid This Month</p>
                        <p class="text-2xl font-semibold text-green-700">${{ number_format($paidThisMonth, 2) }}</p>
                    </div>
                </div>
            </div>
            <a href="{{ route('payroll.index') }}" class="mt-4 inline-block text-sm text-green-600 hover:text-green-700">View Payroll →</a>
        </div>
    </div>

        <!-- Recent Activity & Employees -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
        <!-- Recent Activities -->
        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Recent Activities</h2>
            <div class="space-y-4">
                @forelse($recentActivities ?? [] as $activity)
                <div class="flex items-start gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="w-2 h-2 rounded-full bg-green-600 mt-2"></div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-900">{{ $activity['action'] }}</p>
                        <p class="text-xs text-gray-600">{{ $activity['name'] }} • {{ $activity['time'] }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <p class="text-gray-500">No recent activities</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Recently Added Employees -->
        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Recently Added Employees</h2>
            <div class="space-y-3">
                @foreach($recentEmployees as $emp)
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                        <div class="w-10 h-10 rounded-full bg-green-100 text-green-700 flex items-center justify-center font-semibold text-sm">
                            {{ substr($emp->first_name, 0, 1) }}{{ substr($emp->last_name, 0, 1) }}
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">{{ $emp->full_name }}</p>
                            <p class="text-xs text-gray-600">{{ $emp->department->name ?? 'N/A' }} • {{ $emp->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
            <a href="{{ route('employees.index') }}" class="mt-4 inline-block text-sm text-green-600 hover:text-green-700">View All Employees →</a>
        </div>
    </div>
</div>
@endsection