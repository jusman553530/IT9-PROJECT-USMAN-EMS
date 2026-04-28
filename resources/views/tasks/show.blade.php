@extends('layouts.app')

@section('title', 'Task Details')

@section('content')
<div>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $task->title }}</h1>
            <p class="text-gray-600">{{ $task->task_code }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('tasks.edit', $task) }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                Edit Task
            </a>
            <a href="{{ route('tasks.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                Back to List
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Details -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Description</h2>
                <p class="text-gray-600">{{ $task->description ?? 'No description provided.' }}</p>
            </div>

            @if($task->notes)
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Notes</h2>
                <p class="text-gray-600">{{ $task->notes }}</p>
            </div>
            @endif
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-6">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Details</h2>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-600">Status</p>
                        <span class="inline-block mt-1 px-3 py-1 text-sm rounded-full
                            {{ $task->status === 'completed' ? 'bg-green-100 text-green-700' : 
                               ($task->status === 'in_progress' ? 'bg-blue-100 text-blue-700' : 
                               ($task->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700')) }}">
                            {{ str_replace('_', ' ', ucfirst($task->status)) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Priority</p>
                        <span class="inline-block mt-1 px-3 py-1 text-sm rounded-full
                            {{ $task->priority === 'urgent' ? 'bg-red-100 text-red-700' : 
                               ($task->priority === 'high' ? 'bg-orange-100 text-orange-700' : 
                               ($task->priority === 'medium' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700')) }}">
                            {{ ucfirst($task->priority) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Department</p>
                        <p class="font-medium text-gray-900">{{ $task->department->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Assigned To</p>
                        <p class="font-medium text-gray-900">{{ $task->assignedEmployee->full_name ?? 'Entire Department' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Assigned By</p>
                        <p class="font-medium text-gray-900">{{ $task->assignedBy->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Due Date</p>
                        <p class="font-medium {{ $task->isOverdue() ? 'text-red-600' : 'text-gray-900' }}">
                            {{ $task->due_date ? $task->due_date->format('M d, Y') : 'No deadline' }}
                            @if($task->isOverdue())
                                <span class="text-xs text-red-500 ml-1">(Overdue)</span>
                            @endif
                        </p>
                    </div>
                    @if($task->completed_at)
                    <div>
                        <p class="text-sm text-gray-600">Completed At</p>
                        <p class="font-medium text-gray-900">{{ $task->completed_at->format('M d, Y h:i A') }}</p>
                    </div>
                    @endif
                    <div>
                        <p class="text-sm text-gray-600">Created</p>
                        <p class="font-medium text-gray-900">{{ $task->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection