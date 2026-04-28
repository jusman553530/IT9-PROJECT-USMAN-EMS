@extends('layouts.app')

@section('title', 'Report Details')

@section('content')
<div>
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Report #{{ $report->id }}</h1>
        <a href="{{ route('reports.index') }}" class="px-4 py-2 border rounded-lg text-gray-700 hover:bg-gray-50">Back to List</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ $report->title }}</h2>
                <p class="text-gray-600 whitespace-pre-line">{{ $report->description }}</p>
            </div>

            @if($report->admin_response)
            <div class="bg-white rounded-xl border border-green-200 p-6 shadow-sm">
                <h3 class="text-sm font-semibold text-green-700 mb-2">Admin Response</h3>
                <p class="text-gray-600">{{ $report->admin_response }}</p>
            </div>
            @endif
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">Details</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-xs text-gray-500">Status</p>
                        <span class="px-2 py-1 text-xs rounded-full
                            {{ $report->status === 'open' ? 'bg-yellow-100 text-yellow-700' : 
                               ($report->status === 'in_progress' ? 'bg-blue-100 text-blue-700' : 
                               ($report->status === 'resolved' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700')) }}">
                            {{ str_replace('_', ' ', ucfirst($report->status)) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Type</p>
                        <p class="text-sm font-medium">{{ ucfirst($report->type) }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Priority</p>
                        <span class="px-2 py-1 text-xs rounded-full
                            {{ $report->priority === 'urgent' ? 'bg-red-100 text-red-700' : 
                               ($report->priority === 'high' ? 'bg-orange-100 text-orange-700' : 'bg-gray-100 text-gray-600') }}">
                            {{ ucfirst($report->priority) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Reported By</p>
                        <p class="text-sm font-medium">{{ $report->employee->full_name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Reported On</p>
                        <p class="text-sm">{{ $report->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                    @if($report->resolved_at)
                    <div>
                        <p class="text-xs text-gray-500">Resolved On</p>
                        <p class="text-sm">{{ $report->resolved_at->format('M d, Y h:i A') }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection