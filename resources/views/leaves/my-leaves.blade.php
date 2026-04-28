@extends('layouts.app')

@section('title', 'My Leave Requests')

@section('content')
<div>
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Leave Requests</h1>
            <p class="text-gray-600">Apply for leave and track your request history</p>
        </div>
        <button onclick="document.getElementById('applyLeaveModal').classList.remove('hidden')" 
                class="px-6 py-3 text-white rounded-lg hover:opacity-90 transition flex items-center gap-2 w-fit"
                style="background-color: #0C521C;">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Apply for Leave
        </button>
    </div>

    <!-- Leave Balance Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        @php
            $totalLeave = 20; $usedAnnual = $leaveRequests->where('type', 'annual')->where('status', 'approved')->sum('duration_days');
            $totalSick = 10; $usedSick = $leaveRequests->where('type', 'sick')->where('status', 'approved')->sum('duration_days');
            $totalPersonal = 5; $usedPersonal = $leaveRequests->where('type', 'personal')->where('status', 'approved')->sum('duration_days');
        @endphp
        
        <!-- Annual Leave -->
        <div class="bg-white rounded-xl border border-yellow-200 p-5 shadow-sm">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-lg bg-yellow-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="4"/><path d="M12 2v2m0 16v2M4.93 4.93l1.41 1.41m11.32 11.32l1.41 1.41M2 12h2m16 0h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900">Annual Leave</p>
                    <p class="text-xs text-gray-600">{{ $usedAnnual }} used · {{ $totalLeave - $usedAnnual }} remaining</p>
                </div>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                <div class="h-2 rounded-full bg-yellow-500" style="width: {{ $totalLeave > 0 ? round((($totalLeave - $usedAnnual) / $totalLeave) * 100) : 0 }}%"></div>
            </div>
            <p class="text-xs text-gray-500 text-right">{{ $totalLeave - $usedAnnual }} / {{ $totalLeave }} days left</p>
        </div>

        <!-- Sick Leave -->
        <div class="bg-white rounded-xl border border-red-200 p-5 shadow-sm">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-lg bg-red-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900">Sick Leave</p>
                    <p class="text-xs text-gray-600">{{ $usedSick }} used · {{ $totalSick - $usedSick }} remaining</p>
                </div>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                <div class="h-2 rounded-full bg-red-500" style="width: {{ $totalSick > 0 ? round((($totalSick - $usedSick) / $totalSick) * 100) : 0 }}%"></div>
            </div>
            <p class="text-xs text-gray-500 text-right">{{ $totalSick - $usedSick }} / {{ $totalSick }} days left</p>
        </div>

        <!-- Personal Leave -->
        <div class="bg-white rounded-xl border border-blue-200 p-5 shadow-sm">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M12 22s8-4.5 8-11.5A8 8 0 0 0 4 10.5C4 17.5 12 22 12 22z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900">Personal Leave</p>
                    <p class="text-xs text-gray-600">{{ $usedPersonal }} used · {{ $totalPersonal - $usedPersonal }} remaining</p>
                </div>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                <div class="h-2 rounded-full bg-blue-500" style="width: {{ $totalPersonal > 0 ? round((($totalPersonal - $usedPersonal) / $totalPersonal) * 100) : 0 }}%"></div>
            </div>
            <p class="text-xs text-gray-500 text-right">{{ $totalPersonal - $usedPersonal }} / {{ $totalPersonal }} days left</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">{{ session('success') }}</div>
    @endif

    <!-- Leave History -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Leave History</h2>
        </div>

        <!-- Desktop Table -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="text-left px-5 py-3 text-sm text-gray-600">ID</th>
                        <th class="text-left px-5 py-3 text-sm text-gray-600">Type</th>
                        <th class="text-left px-5 py-3 text-sm text-gray-600">Period</th>
                        <th class="text-center px-5 py-3 text-sm text-gray-600">Days</th>
                        <th class="text-left px-5 py-3 text-sm text-gray-600">Reason</th>
                        <th class="text-left px-5 py-3 text-sm text-gray-600">Applied On</th>
                        <th class="text-center px-5 py-3 text-sm text-gray-600">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaveRequests as $index => $leave)
                        <tr class="border-b border-gray-200 last:border-b-0 hover:bg-gray-50 transition-colors {{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50/50' }}">
                            <td class="px-5 py-4 text-sm text-gray-500">LR{{ str_pad($leave->id, 3, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-5 py-4">
                                <span class="text-xs px-2 py-1 rounded-full 
                                    {{ $leave->type === 'annual' ? 'bg-yellow-50 text-yellow-700' : 
                                       ($leave->type === 'sick' ? 'bg-red-50 text-red-700' : 'bg-blue-50 text-blue-700') }}">
                                    {{ $leave->type === 'annual' ? 'Annual Leave' : ($leave->type === 'sick' ? 'Sick Leave' : ucfirst($leave->type)) }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-sm text-gray-900">{{ $leave->start_date->format('M d, Y') }} – {{ $leave->end_date->format('M d, Y') }}</td>
                            <td class="px-5 py-4 text-sm text-gray-900 text-center">{{ $leave->duration_days }}</td>
                            <td class="px-5 py-4 text-sm text-gray-600">{{ $leave->reason }}</td>
                            <td class="px-5 py-4 text-sm text-gray-600">{{ $leave->created_at->format('M d, Y') }}</td>
                            <td class="px-5 py-4 text-center">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs capitalize
                                    {{ $leave->status === 'approved' ? 'bg-green-100 text-green-700' : 
                                       ($leave->status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                    @if($leave->status === 'approved')
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    @elseif($leave->status === 'pending')
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    @else
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    @endif
                                    {{ $leave->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-16 text-center text-gray-500">
                                No leave requests yet. Apply for your first leave!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards -->
        <div class="md:hidden">
            @forelse($leaveRequests as $index => $leave)
                <div class="p-4 border-b border-gray-200 last:border-b-0 {{ $index % 2 === 0 ? '' : 'bg-gray-50/50' }}">
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <span class="text-xs px-2 py-0.5 rounded-full 
                                {{ $leave->type === 'annual' ? 'bg-yellow-50 text-yellow-700' : 
                                   ($leave->type === 'sick' ? 'bg-red-50 text-red-700' : 'bg-blue-50 text-blue-700') }}">
                                {{ ucfirst($leave->type) }}
                            </span>
                            <span class="text-xs text-gray-500 ml-2">LR{{ str_pad($leave->id, 3, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs capitalize
                            {{ $leave->status === 'approved' ? 'bg-green-100 text-green-700' : 
                               ($leave->status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                            {{ $leave->status }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-900">{{ $leave->start_date->format('M d') }} – {{ $leave->end_date->format('M d, Y') }}</p>
                    <p class="text-xs text-gray-600">{{ $leave->reason }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $leave->duration_days }} day(s) · Applied: {{ $leave->created_at->format('M d') }}</p>
                </div>
            @empty
                <div class="p-8 text-center text-gray-500">No leave requests yet.</div>
            @endforelse
        </div>
    </div>

    @if($leaveRequests->hasPages())
        <div class="mt-6">{{ $leaveRequests->links() }}</div>
    @endif
</div>

<!-- Apply Leave Modal -->
<div id="applyLeaveModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full p-6">
        <div class="flex items-center gap-2 mb-5">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
            <h2 class="text-lg font-semibold text-gray-900">New Leave Application</h2>
        </div>
        <form action="{{ route('leaves.store') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @csrf
            <input type="hidden" name="employee_id" value="{{ $employee->id }}">
            <div>
                <label class="block text-sm text-gray-700 mb-2">Leave Type</label>
                <select name="type" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-green-600" required>
                    <option value="annual">Annual Leave</option>
                    <option value="sick">Sick Leave</option>
                    <option value="personal">Personal Leave</option>
                </select>
            </div>
            <div></div>
            <div>
                <label class="block text-sm text-gray-700 mb-2">From Date</label>
                <input type="date" name="start_date" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-green-600" required>
            </div>
            <div>
                <label class="block text-sm text-gray-700 mb-2">To Date</label>
                <input type="date" name="end_date" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-green-600" required>
            </div>
            <div class="sm:col-span-2">
                <label class="block text-sm text-gray-700 mb-2">Reason</label>
                <input type="text" name="reason" placeholder="Brief reason..." class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-green-600" required>
            </div>
            <div class="sm:col-span-2 flex gap-3 justify-end mt-2">
                <button type="button" onclick="document.getElementById('applyLeaveModal').classList.add('hidden')" class="px-5 py-2.5 border rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
                <button type="submit" class="px-5 py-2.5 text-white rounded-lg hover:opacity-90" style="background-color: #0C521C;">Submit Request</button>
            </div>
        </form>
    </div>
</div>
@endsection