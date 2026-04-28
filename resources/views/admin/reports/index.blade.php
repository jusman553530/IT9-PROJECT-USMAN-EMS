@extends('layouts.app')

@section('title', 'Problem Reports')

@section('content')
<div>
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Problem Reports</h1>
        <p class="text-gray-600">Manage employee reported issues</p>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">{{ session('success') }}</div>
    @endif

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
            <p class="text-sm text-gray-600">Total</p>
            <p class="text-2xl font-semibold text-gray-900">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-yellow-50 rounded-xl border border-yellow-200 p-4 shadow-sm">
            <p class="text-sm text-yellow-600">Open</p>
            <p class="text-2xl font-semibold text-yellow-700">{{ $stats['open'] }}</p>
        </div>
        <div class="bg-blue-50 rounded-xl border border-blue-200 p-4 shadow-sm">
            <p class="text-sm text-blue-600">In Progress</p>
            <p class="text-2xl font-semibold text-blue-700">{{ $stats['in_progress'] }}</p>
        </div>
        <div class="bg-green-50 rounded-xl border border-green-200 p-4 shadow-sm">
            <p class="text-sm text-green-600">Resolved</p>
            <p class="text-2xl font-semibold text-green-700">{{ $stats['resolved'] }}</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <select name="status" class="w-full px-3 py-2 border rounded-lg">
                    <option value="">All Status</option>
                    <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                </select>
            </div>
            <div>
                <select name="priority" class="w-full px-3 py-2 border rounded-lg">
                    <option value="">All Priority</option>
                    <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="px-4 py-2 text-white rounded-lg" style="background-color: #0C521C;">Filter</button>
                <a href="{{ route('admin.reports.index') }}" class="px-4 py-2 border rounded-lg">Clear</a>
            </div>
        </form>
    </div>

    <!-- Reports Table -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Employee</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Priority</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($reports as $report)
                        <tr class="hover:bg-gray-50 {{ $report->status === 'open' && $report->priority === 'urgent' ? 'bg-red-50' : '' }}">
                            <td class="px-6 py-4 text-sm text-gray-500">#{{ $report->id }}</td>
                            <td class="px-6 py-4 text-sm">{{ $report->employee->full_name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $report->title }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ ucfirst($report->type) }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs rounded-full
                                    {{ $report->priority === 'urgent' ? 'bg-red-100 text-red-700' : 
                                       ($report->priority === 'high' ? 'bg-orange-100 text-orange-700' : 'bg-gray-100 text-gray-600') }}">
                                    {{ ucfirst($report->priority) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs rounded-full
                                    {{ $report->status === 'open' ? 'bg-yellow-100 text-yellow-700' : 
                                       ($report->status === 'in_progress' ? 'bg-blue-100 text-blue-700' : 
                                       ($report->status === 'resolved' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700')) }}">
                                    {{ str_replace('_', ' ', ucfirst($report->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $report->created_at->format('M d') }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.reports.show', $report) }}" class="text-blue-600 hover:text-blue-800">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-500">No problem reports found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($reports->hasPages())
            <div class="px-6 py-4 border-t">{{ $reports->links() }}</div>
        @endif
    </div>
</div>
@endsection