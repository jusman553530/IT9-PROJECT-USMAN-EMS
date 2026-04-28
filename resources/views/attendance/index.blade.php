@extends('layouts.app')

@section('title', 'Attendance')

@section('content')
<div>
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Attendance</h1>
        <p class="text-gray-600">Track and manage employee attendance</p>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
        <!-- Present Card -->
        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <span class="text-sm text-gray-600">Present</span>
            </div>
            <p class="text-3xl font-semibold text-gray-900 mb-1">{{ $stats['present'] }}</p>
            <p class="text-sm text-green-600">
                {{ $stats['total'] > 0 ? round(($stats['present'] / $stats['total']) * 100) : 0 }}% of total
            </p>
        </div>

        <!-- Late Card -->
        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-lg bg-yellow-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <span class="text-sm text-gray-600">Late</span>
            </div>
            <p class="text-3xl font-semibold text-gray-900 mb-1">{{ $stats['late'] }}</p>
            <p class="text-sm text-yellow-600">
                {{ $stats['total'] > 0 ? round(($stats['late'] / $stats['total']) * 100) : 0 }}% of total
            </p>
        </div>

        <!-- Absent Card -->
        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <span class="text-sm text-gray-600">Absent</span>
            </div>
            <p class="text-3xl font-semibold text-gray-900 mb-1">{{ $stats['absent'] }}</p>
            <p class="text-sm text-red-600">
                {{ $stats['total'] > 0 ? round(($stats['absent'] / $stats['total']) * 100) : 0 }}% of total
            </p>
        </div>
    </div>

    <!-- Today's Attendance -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <h2 class="text-lg font-semibold text-gray-900">
                    Today's Attendance - {{ date('F j, Y') }}
                </h2>
            </div>
        </div>

        <!-- Desktop Table -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="text-left px-6 py-4 text-sm font-medium text-gray-600">Employee</th>
                        <th class="text-left px-6 py-4 text-sm font-medium text-gray-600">Department</th>
                        <th class="text-left px-6 py-4 text-sm font-medium text-gray-600">Check-in Time</th>
                        <th class="text-left px-6 py-4 text-sm font-medium text-gray-600">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $index => $attendance)
                        @php
                            $statusColors = [
                                'present' => 'bg-green-100 text-green-700',
                                'late' => 'bg-yellow-100 text-yellow-700',
                                'absent' => 'bg-red-100 text-red-700',
                                'half_day' => 'bg-orange-100 text-orange-700',
                                'on_leave' => 'bg-blue-100 text-blue-700',
                            ];
                            $colorClass = $statusColors[$attendance->status] ?? 'bg-gray-100 text-gray-700';
                        @endphp
                        <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }} border-b border-gray-200 last:border-b-0 hover:bg-gray-100 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                                        <span class="text-sm font-semibold text-green-700">
                                            {{ substr($attendance->employee->first_name ?? 'U', 0, 1) }}{{ substr($attendance->employee->last_name ?? '', 0, 1) }}
                                        </span>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900">
                                        {{ $attendance->employee->full_name ?? 'N/A' }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $attendance->employee->department->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $attendance->time_in ? \Carbon\Carbon::parse($attendance->time_in)->format('h:i A') : '-' }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs capitalize {{ $colorClass }}">
                                    @if($attendance->status === 'present')
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    @elseif($attendance->status === 'late')
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    @elseif($attendance->status === 'absent')
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    @endif
                                    {{ $attendance->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-lg font-medium">No attendance records for today</p>
                                <p class="text-sm mt-2">Mark attendance to get started.</p>
                                <a href="{{ route('attendance.create') }}" 
                                   class="mt-4 inline-block px-4 py-2 text-white rounded-lg hover:opacity-90" 
                                   style="background-color: #0C521C;">
                                    Mark Attendance
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards -->
        <div class="md:hidden">
            @forelse($attendances as $attendance)
                @php
                    $statusColors = [
                        'present' => 'bg-green-100 text-green-700',
                        'late' => 'bg-yellow-100 text-yellow-700',
                        'absent' => 'bg-red-100 text-red-700',
                        'half_day' => 'bg-orange-100 text-orange-700',
                        'on_leave' => 'bg-blue-100 text-blue-700',
                    ];
                    $colorClass = $statusColors[$attendance->status] ?? 'bg-gray-100 text-gray-700';
                @endphp
                <div class="p-4 border-b border-gray-200 last:border-b-0">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                                <span class="text-sm font-semibold text-green-700">
                                    {{ substr($attendance->employee->first_name ?? 'U', 0, 1) }}{{ substr($attendance->employee->last_name ?? '', 0, 1) }}
                                </span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $attendance->employee->full_name ?? 'N/A' }}</p>
                                <p class="text-sm text-gray-600">{{ $attendance->employee->department->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs capitalize {{ $colorClass }}">
                            @if($attendance->status === 'present')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @elseif($attendance->status === 'late')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @elseif($attendance->status === 'absent')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @endif
                            {{ $attendance->status }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-600 ml-13">
                        Check-in: {{ $attendance->time_in ? \Carbon\Carbon::parse($attendance->time_in)->format('h:i A') : '-' }}
                    </p>
                </div>
            @empty
                <div class="p-8 text-center text-gray-500">
                    <p>No attendance records for today</p>
                    <a href="{{ route('attendance.create') }}" 
                       class="mt-4 inline-block px-4 py-2 text-white rounded-lg hover:opacity-90" 
                       style="background-color: #0C521C;">
                        Mark Attendance
                    </a>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Pagination -->
    @if($attendances->hasPages())
        <div class="mt-6">
            {{ $attendances->links() }}
        </div>
    @endif
</div>
@endsection