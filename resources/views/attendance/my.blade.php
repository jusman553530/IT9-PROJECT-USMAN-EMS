@extends('layouts.app')

@section('title', 'My Attendance')

@section('content')
<div>
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">My Attendance</h1>
        <p class="text-gray-600">View your attendance history</p>
    </div>

    <!-- Stats Cards -->
    @php
        $thisMonth = \Carbon\Carbon::today();
        $presentCount = $attendances->where('status', 'present')->count() + $attendances->where('status', 'late')->count();
        $absentCount = $attendances->where('status', 'absent')->count();
        $leaveCount = $attendances->where('status', 'on_leave')->count();
        $totalDays = $attendances->count();
        $rate = $totalDays > 0 ? round(($presentCount / $totalDays) * 100) : 0;
    @endphp

    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
            <p class="text-sm text-gray-600 mb-1">Days Present</p>
            <p class="text-3xl font-semibold text-green-600 text-right">{{ $presentCount }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
            <p class="text-sm text-gray-600 mb-1">Days Absent</p>
            <p class="text-3xl font-semibold text-red-500 text-right">{{ $absentCount }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
            <p class="text-sm text-gray-600 mb-1">On Leave</p>
            <p class="text-3xl font-semibold text-blue-500 text-right">{{ $leaveCount }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
            <p class="text-sm text-gray-600 mb-1">Attendance Rate</p>
            <p class="text-3xl font-semibold text-green-600 text-right">{{ $rate }}%</p>
        </div>
    </div>

    <!-- Attendance Table -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Day</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Time In</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Time Out</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Hours</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($attendances as $attendance)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $attendance->date->format('M d, Y') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $attendance->date->format('l') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $attendance->time_in ? \Carbon\Carbon::parse($attendance->time_in)->format('h:i A') : '--:--' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $attendance->time_out ? \Carbon\Carbon::parse($attendance->time_out)->format('h:i A') : '--:--' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                @if($attendance->time_in && $attendance->time_out)
                                    @php
                                        $hours = floor(\Carbon\Carbon::parse($attendance->time_in)->diffInMinutes(\Carbon\Carbon::parse($attendance->time_out)) / 60);
                                        $mins = \Carbon\Carbon::parse($attendance->time_in)->diffInMinutes(\Carbon\Carbon::parse($attendance->time_out)) % 60;
                                    @endphp
                                    {{ $hours }}h {{ $mins }}m
                                @else
                                    --
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs rounded-full 
                                    {{ $attendance->status === 'present' ? 'bg-green-100 text-green-700' : 
                                       ($attendance->status === 'late' ? 'bg-yellow-100 text-yellow-700' : 
                                       ($attendance->status === 'absent' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700')) }}">
                                    {{ ucfirst(str_replace('_', ' ', $attendance->status)) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-lg font-medium">No attendance records found</p>
                                <p class="text-sm mt-2">Your attendance will appear here once you start clocking in.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($attendances->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $attendances->links() }}
            </div>
        @endif
    </div>
</div>
@endsection