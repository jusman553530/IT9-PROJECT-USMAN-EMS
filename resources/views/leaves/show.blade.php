@extends('layouts.app')

@section('title', 'Leave Request Details')

@section('content')
<div>
    <div class="mb-8 flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">Leave Request Details</h1>
        <a href="{{ route('leaves.index') }}" class="px-4 py-2 border rounded-lg text-gray-700 hover:bg-gray-50">Back to List</a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm text-gray-600">Employee</p>
                <p class="text-lg font-semibold">{{ $leave->employee->full_name ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Department</p>
                <p class="text-lg">{{ $leave->employee->department->name ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Leave Type</p>
                <span class="px-2 py-1 text-sm rounded-full bg-blue-100 text-blue-700">{{ ucfirst($leave->type) }}</span>
            </div>
            <div>
                <p class="text-sm text-gray-600">Status</p>
                <span class="px-2 py-1 text-sm rounded-full 
                    {{ $leave->status === 'approved' ? 'bg-green-100 text-green-700' : 
                       ($leave->status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                    {{ ucfirst($leave->status) }}
                </span>
            </div>
            <div>
                <p class="text-sm text-gray-600">Date Range</p>
                <p class="text-lg">{{ $leave->start_date->format('M d, Y') }} - {{ $leave->end_date->format('M d, Y') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Duration</p>
                <p class="text-lg">{{ $leave->duration_days }} day(s)</p>
            </div>
            <div class="md:col-span-2">
                <p class="text-sm text-gray-600">Reason</p>
                <p class="mt-1">{{ $leave->reason }}</p>
            </div>
            @if($leave->admin_notes)
            <div class="md:col-span-2">
                <p class="text-sm text-gray-600">Admin Notes</p>
                <p class="mt-1">{{ $leave->admin_notes }}</p>
            </div>
            @endif
        </div>

        @if($leave->status === 'pending')
        <div class="flex gap-3 mt-8 pt-6 border-t">
            <form action="{{ route('leaves.approve', $leave) }}" method="POST">
                @csrf
                <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Approve</button>
            </form>
            <form action="{{ route('leaves.reject', $leave) }}" method="POST" class="flex gap-3">
                @csrf
                <input type="text" name="admin_notes" placeholder="Reason for rejection..." required class="px-3 py-2 border rounded-lg">
                <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Reject</button>
            </form>
        </div>
        @endif
    </div>
</div>
@endsection