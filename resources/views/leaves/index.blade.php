@extends('layouts.app')

@section('title', 'Leave Requests')

@section('content')
<div>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Leave Requests</h1>
            <p class="text-gray-600">Manage employee leave requests</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">{{ session('success') }}</div>
    @endif

    <!-- Stats Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl border border-yellow-200 shadow-sm overflow-hidden">
            <div class="p-5">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 rounded-lg bg-yellow-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="text-sm font-medium text-yellow-600 bg-yellow-50 px-3 py-1 rounded-full">Pending</span>
                </div>
                <p class="text-3xl font-semibold text-gray-900 text-right">{{ $stats['pending'] }}</p>
            </div>
            <div class="bg-yellow-50 px-5 py-2 border-t border-yellow-100"><p class="text-xs text-yellow-700 font-medium">Awaiting decision</p></div>
        </div>
        <div class="bg-white rounded-xl border border-green-200 shadow-sm overflow-hidden">
            <div class="p-5">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="text-sm font-medium text-green-600 bg-green-50 px-3 py-1 rounded-full">Approved</span>
                </div>
                <p class="text-3xl font-semibold text-gray-900 text-right">{{ $stats['approved'] }}</p>
            </div>
            <div class="bg-green-50 px-5 py-2 border-t border-green-100"><p class="text-xs text-green-700 font-medium">Leave approved</p></div>
        </div>
        <div class="bg-white rounded-xl border border-red-200 shadow-sm overflow-hidden">
            <div class="p-5">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 rounded-lg bg-red-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="text-sm font-medium text-red-600 bg-red-50 px-3 py-1 rounded-full">Rejected</span>
                </div>
                <p class="text-3xl font-semibold text-gray-900 text-right">{{ $stats['rejected'] }}</p>
            </div>
            <div class="bg-red-50 px-5 py-2 border-t border-red-100"><p class="text-xs text-red-700 font-medium">Leave denied</p></div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 mb-6">
        <form method="GET" action="{{ route('leaves.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full px-3 py-2 border rounded-lg"><option value="">All</option><option value="pending">Pending</option><option value="approved">Approved</option><option value="rejected">Rejected</option></select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                <select name="type" class="w-full px-3 py-2 border rounded-lg"><option value="">All</option><option value="sick">Sick</option><option value="vacation">Vacation</option><option value="personal">Personal</option></select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Employee</label>
                <select name="employee_id" class="w-full px-3 py-2 border rounded-lg"><option value="">All</option>@foreach($employees as $emp)<option value="{{ $emp->id }}">{{ $emp->full_name }}</option>@endforeach</select>
            </div>
            <div class="flex items-end gap-2"><button type="submit" class="px-4 py-2 text-white rounded-lg" style="background-color: #0C521C;">Filter</button><a href="{{ route('leaves.index') }}" class="px-4 py-2 border rounded-lg">Clear</a></div>
        </form>
    </div>

    <!-- Leave Requests Table -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b"><tr><th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Employee</th><th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Type</th><th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Dates</th><th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Duration</th><th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Status</th><th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase">Actions</th></tr></thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($leaveRequests as $leave)
                        <tr class="hover:bg-gray-50 {{ $leave->status === 'pending' ? 'bg-yellow-50' : '' }}">
                            <td class="px-6 py-4"><div class="flex items-center gap-3"><div class="w-8 h-8 rounded-full bg-green-100 text-green-700 flex items-center justify-center font-semibold text-xs">{{ substr($leave->employee->first_name ?? 'U', 0, 1) }}{{ substr($leave->employee->last_name ?? '', 0, 1) }}</div><div><p class="text-sm font-medium">{{ $leave->employee->full_name ?? 'N/A' }}</p><p class="text-xs text-gray-500">{{ $leave->employee->department->name ?? '' }}</p></div></div></td>
                            <td class="px-6 py-4"><span class="px-2 py-1 text-xs rounded-full {{ $leave->type === 'sick' ? 'bg-red-100 text-red-700' : ($leave->type === 'vacation' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700') }}">{{ ucfirst($leave->type) }}</span></td>
                            <td class="px-6 py-4 text-sm">{{ $leave->start_date->format('M d') }} - {{ $leave->end_date->format('M d, Y') }}</td>
                            <td class="px-6 py-4 text-sm">{{ $leave->duration_days }} day(s)</td>
                            <td class="px-6 py-4"><span class="px-2 py-1 text-xs rounded-full {{ $leave->status === 'approved' ? 'bg-green-100 text-green-700' : ($leave->status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">{{ ucfirst($leave->status) }}</span></td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('leaves.show', $leave) }}" class="text-blue-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg></a>
                                    @if($leave->status === 'pending')
                                        <form action="{{ route('leaves.approve', $leave) }}" method="POST" class="inline">@csrf<button class="text-green-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"></path></svg></button></form>
                                        <button onclick="showRejectModal({{ $leave->id }})" class="text-red-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"></path></svg></button>
                                    @endif
                                    <form action="{{ route('leaves.destroy', $leave) }}" method="POST" onsubmit="return confirm('Delete?')" class="inline">@csrf @method('DELETE')<button class="text-gray-400 hover:text-red-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button></form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-16 text-center text-gray-500">No leave requests found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($leaveRequests->hasPages())<div class="px-6 py-4 border-t">{{ $leaveRequests->links() }}</div>@endif
    </div>
</div>

<div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6">
        <div class="flex justify-between items-center mb-4"><h3 class="text-lg font-semibold">Reject Leave</h3><button onclick="document.getElementById('rejectModal').classList.add('hidden')" class="text-gray-400">&times;</button></div>
        <form id="rejectForm" method="POST">@csrf<div><label class="block text-sm mb-2">Reason *</label><textarea name="admin_notes" rows="3" required class="w-full px-3 py-2 border rounded-lg"></textarea></div><div class="flex justify-end gap-3 mt-6"><button type="button" onclick="document.getElementById('rejectModal').classList.add('hidden')" class="px-4 py-2 border rounded-lg">Cancel</button><button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg">Reject</button></div></form>
    </div>
</div>

<script>function showRejectModal(id){document.getElementById('rejectForm').action='/leaves/'+id+'/reject';document.getElementById('rejectModal').classList.remove('hidden');}</script>
@endsection