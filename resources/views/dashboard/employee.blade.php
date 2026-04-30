@extends('layouts.app')

@section('title', 'My Dashboard')

@section('content')
<div>
    <!-- Welcome Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Good morning, {{ explode(' ', Auth::user()->name)[0] }}!</h1>
        <p class="text-gray-600">
            {{ $employee->position->title ?? 'Employee' }} · {{ $employee->department->name ?? 'N/A' }} · {{ $employee->employee_id ?? 'N/A' }} · {{ \Carbon\Carbon::today()->format('l, F j, Y') }}
        </p>
    </div>

    <!-- Today's Status Card -->
    <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <p class="text-gray-500 text-sm mb-1">Today's Status</p>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                        @if($todayAttendance)
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        @else
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        @endif
                    </div>
                    <div>
                        @if($todayAttendance)
                            <p class="text-gray-900 font-semibold">{{ $todayAttendance->time_in ? 'Checked In' : 'Not Checked In' }}</p>
                            <p class="text-gray-500 text-sm">{{ $todayAttendance->time_in ? \Carbon\Carbon::parse($todayAttendance->time_in)->format('h:i A') : '--:--' }} · {{ $todayAttendance->status === 'late' ? 'Late' : 'On time' }}</p>
                        @else
                            <p class="text-gray-900 font-semibold">Not Checked In</p>
                            <p class="text-gray-500 text-sm">Start your day by clocking in</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-6">
                <div class="text-center">
                    <p class="text-gray-500 text-xs mb-1">Hours Today</p>
                    <p class="text-gray-900 font-semibold">
                        @if($todayAttendance && $todayAttendance->time_in)
                            @php $start = \Carbon\Carbon::parse($todayAttendance->time_in); $end = $todayAttendance->time_out ? \Carbon\Carbon::parse($todayAttendance->time_out) : \Carbon\Carbon::now(); $hours = floor($start->diffInMinutes($end) / 60); $minutes = $start->diffInMinutes($end) % 60; @endphp
                            {{ $hours }}h {{ $minutes }}m
                        @else --h --m @endif
                    </p>
                </div>
                <div class="text-center"><p class="text-gray-500 text-xs mb-1">This Week</p><p class="text-gray-900 font-semibold">{{ $weekHours ?? '--' }}h</p></div>
                <div class="text-center"><p class="text-gray-500 text-xs mb-1">This Month</p><p class="text-gray-900 font-semibold">{{ $monthHours ?? '--' }}h</p></div>
                @if($todayAttendance && !$todayAttendance->time_out)
                    <form action="{{ route('attendance.clock-out') }}" method="POST">@csrf<input type="hidden" name="employee_id" value="{{ $employee->id }}"><button type="submit" class="px-4 py-2 text-white rounded-lg hover:opacity-90 transition font-medium" style="background-color: #0C521C;">Clock Out</button></form>
                @elseif(!$todayAttendance)
                    <form action="{{ route('attendance.clock-in') }}" method="POST">@csrf<input type="hidden" name="employee_id" value="{{ $employee->id }}"><button type="submit" class="px-4 py-2 text-white rounded-lg hover:opacity-90 transition font-medium" style="background-color: #0C521C;">Clock In</button></form>
                @endif
            </div>
        </div>
    </div>

    <!-- Attendance This Month -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm"><p class="text-sm text-gray-600 mb-1">Days Present</p><p class="text-3xl font-semibold text-green-600 text-right">{{ $myAttendanceSummary['present'] ?? 0 }}</p></div>
        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm"><p class="text-sm text-gray-600 mb-1">Days Absent</p><p class="text-3xl font-semibold text-red-500 text-right">{{ $myAttendanceSummary['absent'] ?? 0 }}</p></div>
        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm"><p class="text-sm text-gray-600 mb-1">Late Arrivals</p><p class="text-3xl font-semibold text-yellow-600 text-right">0</p></div>
        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm"><p class="text-sm text-gray-600 mb-1">On Leave</p><p class="text-3xl font-semibold text-blue-500 text-right">{{ $myAttendanceSummary['on_leave'] ?? 0 }}</p></div>
    </div>

    <!-- ALL 4 CARDS IN ONE GRID -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- My Tasks -->
        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4"><h2 class="text-lg font-semibold text-gray-900">My Tasks</h2><a href="{{ route('tasks.my') }}" class="text-sm text-green-600 hover:underline">View All</a></div>
            @if($myTasks->count() > 0)
                <div class="space-y-3">
                    @foreach($myTasks->take(4) as $task)
                        <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors">
                            <div><p class="text-sm font-medium text-gray-900">{{ $task->title }}</p><p class="text-xs text-gray-600">{{ $task->department->name ?? '' }}</p></div>
                            <span class="text-xs px-2 py-1 rounded-full {{ $task->priority === 'urgent' ? 'bg-red-100 text-red-700' : ($task->priority === 'high' ? 'bg-orange-100 text-orange-700' : ($task->priority === 'medium' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700')) }}">{{ ucfirst($task->priority) }}</span>
                        </div>
                    @endforeach
                </div>
            @else <p class="text-gray-500 text-center py-8">No pending tasks</p> @endif
        </div>

        <!-- My Attendance This Month -->
        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">My Attendance This Month</h2>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 bg-green-50 rounded-lg"><div><p class="text-sm text-green-700 font-medium">Present</p><p class="text-2xl font-semibold text-green-900">{{ $myAttendanceSummary['present'] ?? 0 }}</p></div><div class="w-12 h-12 rounded-full bg-green-200 flex items-center justify-center"><span class="text-lg font-semibold text-green-700">{{ $myAttendanceSummary['present'] ?? 0 }}</span></div></div>
                <div class="flex items-center justify-between p-4 bg-yellow-50 rounded-lg"><div><p class="text-sm text-yellow-700 font-medium">On Leave</p><p class="text-2xl font-semibold text-yellow-900">{{ $myAttendanceSummary['on_leave'] ?? 0 }}</p></div><div class="w-12 h-12 rounded-full bg-yellow-200 flex items-center justify-center"><span class="text-lg font-semibold text-yellow-700">{{ $myAttendanceSummary['on_leave'] ?? 0 }}</span></div></div>
                <div class="flex items-center justify-between p-4 bg-red-50 rounded-lg"><div><p class="text-sm text-red-700 font-medium">Absent</p><p class="text-2xl font-semibold text-red-900">{{ $myAttendanceSummary['absent'] ?? 0 }}</p></div><div class="w-12 h-12 rounded-full bg-red-200 flex items-center justify-center"><span class="text-lg font-semibold text-red-700">{{ $myAttendanceSummary['absent'] ?? 0 }}</span></div></div>
            </div>
            @php $totalDays = ($myAttendanceSummary['present'] ?? 0) + ($myAttendanceSummary['absent'] ?? 0) + ($myAttendanceSummary['on_leave'] ?? 0); $attendanceRate = $totalDays > 0 ? round(($myAttendanceSummary['present'] ?? 0) / $totalDays * 100) : 0; @endphp
            <div class="mt-4 pt-4 border-t border-gray-200"><p class="text-sm text-gray-600">Attendance Rate: <span class="font-semibold text-green-600">{{ $attendanceRate }}%</span></p></div>
        </div>

        <!-- Leave Balance -->
        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4"><h2 class="text-lg font-semibold text-gray-900">Leave Balance</h2><a href="{{ route('leaves.my') }}" class="text-sm text-green-600 hover:underline">Apply</a></div>
            <div class="space-y-4">
                @foreach([['type' => 'Annual Leave', 'remaining' => 20 - ($leaveRequests->where('type', 'annual')->where('status', 'approved')->sum('duration_days') ?? 0), 'total' => 20, 'color' => '#eab308'],['type' => 'Sick Leave', 'remaining' => 10 - ($leaveRequests->where('type', 'sick')->where('status', 'approved')->sum('duration_days') ?? 0), 'total' => 10, 'color' => '#ef4444'],['type' => 'Personal Leave', 'remaining' => 5 - ($leaveRequests->where('type', 'personal')->where('status', 'approved')->sum('duration_days') ?? 0), 'total' => 5, 'color' => '#3b82f6']] as $leave)
                    @php $pct = $leave['total'] > 0 ? round((max(0, $leave['remaining']) / $leave['total']) * 100) : 0; @endphp
                    <div>
                        <div class="flex items-center justify-between mb-2"><span class="text-sm text-gray-900">{{ $leave['type'] }}</span><span class="text-sm font-semibold">{{ max(0, $leave['remaining']) }}/{{ $leave['total'] }} days</span></div>
                        <div class="w-full bg-gray-200 rounded-full h-2"><div class="h-2 rounded-full" style="width: {{ $pct }}%; background-color: {{ $leave['color'] }}"></div></div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Recent Payslips -->
        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4"><h2 class="text-lg font-semibold text-gray-900">Recent Payslips</h2><a href="{{ route('payslips.my') }}" class="text-sm text-green-600 hover:underline">View All</a></div>
            <div class="space-y-3">
                @forelse($recentPayslips ?? [] as $slip)
                    <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors">
                        <div class="flex items-center gap-3"><div class="w-9 h-9 rounded-lg bg-green-100 flex items-center justify-center"><svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg></div><div><p class="text-sm font-medium text-gray-900">{{ $slip->period_start->format('F Y') }}</p><p class="text-xs text-gray-600">{{ $slip->period_start->format('M d, Y') }}</p></div></div>
                        <div class="text-right"><p class="text-sm font-semibold text-green-600">${{ number_format($slip->net_salary) }}</p><span class="text-xs text-green-600 bg-green-100 px-2 py-0.5 rounded-full">Paid</span></div>
                    </div>
                @empty <p class="text-gray-500 text-center py-8">No payslips yet</p> @endforelse
            </div>
        </div>
    </div>
</div>
@endsection