@extends('layouts.app')

@section('title', 'My Tasks')

@section('content')
<div>
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">My Tasks</h1>
        <p class="text-gray-600">View and manage your assigned tasks</p>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total</p>
                    <p class="text-xl font-semibold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-yellow-200 p-4 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-yellow-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-yellow-600">Pending</p>
                    <p class="text-xl font-semibold text-yellow-700">{{ $stats['pending'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-green-200 p-4 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-green-600">Completed</p>
                    <p class="text-xl font-semibold text-green-700">{{ $stats['completed'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-red-200 p-4 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-red-600">Overdue</p>
                    <p class="text-xl font-semibold text-red-700">{{ $stats['overdue'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tasks Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse($tasks as $task)
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow group {{ $task->isOverdue() ? 'border-red-300 ring-1 ring-red-100' : '' }}">
                <div class="p-5 border-b border-gray-100">
                    <div class="flex items-start justify-between mb-3">
                        <span class="text-xs font-mono text-gray-500 bg-gray-100 px-2 py-1 rounded">{{ $task->task_code ?? 'N/A' }}</span>
                        @if($task->status !== 'completed')
                            <form action="{{ route('tasks.status', $task) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="completed">
                                <button type="submit" class="text-xs text-green-600 hover:text-green-800 font-medium px-2 py-1 border border-green-300 rounded-lg hover:bg-green-50">
                                    Mark Done
                                </button>
                            </form>
                        @endif
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">{{ $task->title }}</h3>
                    <p class="text-sm text-gray-600 line-clamp-2 mb-3">{{ Str::limit($task->description, 80) ?? 'No description' }}</p>
                    
                    <div class="flex flex-wrap gap-2">
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs rounded-full
                            {{ $task->priority === 'urgent' ? 'bg-red-100 text-red-700' : 
                               ($task->priority === 'high' ? 'bg-orange-100 text-orange-700' : 
                               ($task->priority === 'medium' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700')) }}">
                            {{ ucfirst($task->priority) }}
                        </span>
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs rounded-full
                            {{ $task->status === 'completed' ? 'bg-green-100 text-green-700' : 
                               ($task->status === 'in_progress' ? 'bg-blue-100 text-blue-700' : 'bg-yellow-100 text-yellow-700') }}">
                            {{ str_replace('_', ' ', ucfirst($task->status)) }}
                        </span>
                    </div>
                </div>
                
                <div class="px-5 py-3 bg-gray-50 rounded-b-xl flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        @if($task->assignedBy)
                            <span class="text-xs text-gray-500">By: <span class="font-medium text-gray-700">{{ $task->assignedBy->name }}</span></span>
                        @endif
                    </div>
                    <div class="flex items-center gap-2 text-xs text-gray-500">
                        @if($task->due_date)
                            <span class="{{ $task->isOverdue() ? 'text-red-600 font-medium' : '' }}">{{ $task->due_date->format('M d, Y') }}</span>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="bg-white rounded-xl border border-gray-200 p-16 text-center">
                    <svg class="w-20 h-20 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No tasks assigned</h3>
                    <p class="text-gray-500">You're all caught up!</p>
                </div>
            </div>
        @endforelse
    </div>

    @if($tasks->hasPages())
        <div class="mt-8">{{ $tasks->links() }}</div>
    @endif
</div>
@endsection