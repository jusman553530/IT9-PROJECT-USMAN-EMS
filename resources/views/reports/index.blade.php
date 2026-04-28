@extends('layouts.app')

@section('title', 'Report a Problem')

@section('content')
<div>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Report a Problem</h1>
            <p class="text-gray-600">Submit and track your reported issues</p>
        </div>
        <a href="{{ route('reports.create') }}" class="px-4 py-2 text-white rounded-lg hover:opacity-90 flex items-center gap-2" style="background-color: #0C521C;">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Report Problem
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse($reports as $report)
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                <div class="p-5 border-b border-gray-100">
                    <div class="flex items-start justify-between mb-3">
                        <span class="text-xs text-gray-500">#{{ $report->id }}</span>
                        <span class="px-2 py-1 text-xs rounded-full
                            {{ $report->status === 'open' ? 'bg-yellow-100 text-yellow-700' : 
                               ($report->status === 'in_progress' ? 'bg-blue-100 text-blue-700' : 
                               ($report->status === 'resolved' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700')) }}">
                            {{ str_replace('_', ' ', ucfirst($report->status)) }}
                        </span>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">{{ $report->title }}</h3>
                    <p class="text-sm text-gray-600 line-clamp-2">{{ Str::limit($report->description, 100) }}</p>
                    <div class="flex gap-2 mt-3">
                        <span class="text-xs px-2 py-1 rounded-full bg-gray-100 text-gray-600">{{ ucfirst($report->type) }}</span>
                        <span class="text-xs px-2 py-1 rounded-full
                            {{ $report->priority === 'urgent' ? 'bg-red-100 text-red-700' : 
                               ($report->priority === 'high' ? 'bg-orange-100 text-orange-700' : 'bg-gray-100 text-gray-600') }}">
                            {{ ucfirst($report->priority) }}
                        </span>
                    </div>
                </div>
                <div class="px-5 py-3 bg-gray-50 rounded-b-xl">
                    <p class="text-xs text-gray-500">Reported {{ $report->created_at->diffForHumans() }}</p>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-16">
                <p class="text-gray-500">No reports submitted yet.</p>
            </div>
        @endforelse
    </div>

    @if($reports->hasPages())
        <div class="mt-8">{{ $reports->links() }}</div>
    @endif
</div>
@endsection