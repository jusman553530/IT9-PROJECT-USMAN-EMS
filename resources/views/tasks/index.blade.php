@extends('layouts.app')

@section('title', 'Tasks')

@section('content')
<div>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div><h1 class="text-3xl font-bold text-gray-900 mb-2">Tasks</h1><p class="text-gray-600">Manage and assign work to departments and employees</p></div>
        <a href="{{ route('tasks.create') }}" class="px-4 py-2 text-white rounded-lg hover:opacity-90 transition flex items-center gap-2 w-fit" style="background-color: #0C521C;"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>Create Task</a>
    </div>

    @if(session('success'))<div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">{{ session('success') }}</div>@endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm"><div class="flex items-center gap-3 mb-2"><div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center"><svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg></div><span class="text-sm text-gray-600">Total</span></div><p class="text-xl font-semibold text-gray-900 text-right">{{ $stats['total'] ?? 0 }}</p></div>
        <div class="bg-white rounded-xl border border-yellow-200 p-4 shadow-sm"><div class="flex items-center gap-3 mb-2"><div class="w-10 h-10 rounded-lg bg-yellow-100 flex items-center justify-center"><svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div><span class="text-sm text-gray-600">Pending</span></div><p class="text-xl font-semibold text-yellow-700 text-right">{{ $stats['pending'] ?? 0 }}</p></div>
        <div class="bg-white rounded-xl border border-green-200 p-4 shadow-sm"><div class="flex items-center gap-3 mb-2"><div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center"><svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div><span class="text-sm text-gray-600">Completed</span></div><p class="text-xl font-semibold text-green-700 text-right">{{ $stats['completed'] ?? 0 }}</p></div>
        <div class="bg-white rounded-xl border border-red-200 p-4 shadow-sm"><div class="flex items-center gap-3 mb-2"><div class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center"><svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg></div><span class="text-sm text-gray-600">Overdue</span></div><p class="text-xl font-semibold text-red-700 text-right">{{ $stats['overdue'] ?? 0 }}</p></div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Department</label><select name="department_id" class="w-full px-3 py-2 border rounded-lg"><option value="">All</option>@foreach($departments as $dept)<option value="{{ $dept->id }}">{{ $dept->name }}</option>@endforeach</select></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Status</label><select name="status" class="w-full px-3 py-2 border rounded-lg"><option value="">All</option><option value="pending">Pending</option><option value="in_progress">In Progress</option><option value="completed">Completed</option></select></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Priority</label><select name="priority" class="w-full px-3 py-2 border rounded-lg"><option value="">All</option><option value="low">Low</option><option value="medium">Medium</option><option value="high">High</option><option value="urgent">Urgent</option></select></div>
            <div class="flex items-end gap-2"><button type="submit" class="px-4 py-2 text-white rounded-lg" style="background-color: #0C521C;">Filter</button><a href="{{ route('tasks.index') }}" class="px-4 py-2 border rounded-lg">Clear</a></div>
        </form>
    </div>

    <!-- Tasks Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse($tasks as $task)
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md {{ $task->isOverdue() ? 'border-red-300 ring-1 ring-red-100' : '' }}">
                <div class="p-5 border-b border-gray-100">
                    <div class="flex items-start justify-between mb-3"><span class="text-xs font-mono text-gray-500 bg-gray-100 px-2 py-1 rounded">{{ $task->task_code }}</span><div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity"><a href="{{ route('tasks.show', $task) }}" class="text-blue-600 p-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg></a><a href="{{ route('tasks.edit', $task) }}" class="text-green-600 p-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></a><form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Delete?')" class="inline">@csrf @method('DELETE')<button class="text-red-600 p-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button></form></div></div>
                    <h3 class="font-semibold text-gray-900 mb-2">{{ $task->title }}</h3>
                    <p class="text-sm text-gray-600 mb-3">{{ Str::limit($task->description, 80) ?? 'No description' }}</p>
                    <div class="flex flex-wrap gap-2"><span class="px-2 py-1 text-xs rounded-full {{ $task->priority === 'urgent' ? 'bg-red-100 text-red-700' : ($task->priority === 'high' ? 'bg-orange-100 text-orange-700' : ($task->priority === 'medium' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700')) }}">{{ ucfirst($task->priority) }}</span><span class="px-2 py-1 text-xs rounded-full {{ $task->status === 'completed' ? 'bg-green-100 text-green-700' : ($task->status === 'in_progress' ? 'bg-blue-100 text-blue-700' : 'bg-yellow-100 text-yellow-700') }}">{{ str_replace('_', ' ', ucfirst($task->status)) }}</span></div>
                </div>
                <div class="px-5 py-3 bg-gray-50 rounded-b-xl flex items-center justify-between text-sm">@if($task->assignedEmployee)<div class="flex items-center gap-2"><div class="w-6 h-6 rounded-full bg-green-100 text-green-700 flex items-center justify-center text-xs font-semibold">{{ substr($task->assignedEmployee->first_name, 0, 1) }}</div><span class="text-gray-600">{{ $task->assignedEmployee->first_name }}</span></div>@else<span class="text-gray-400">Unassigned</span>@endif<span class="{{ $task->isOverdue() ? 'text-red-600 font-medium' : 'text-gray-500' }}">{{ $task->due_date ? $task->due_date->format('M d') : '—' }}</span></div>
            </div>
        @empty
            <div class="col-span-full text-center py-16"><h3 class="text-lg font-medium text-gray-900 mb-2">No tasks found</h3><a href="{{ route('tasks.create') }}" class="inline-block px-4 py-2 text-white rounded-lg" style="background-color: #0C521C;">Create Task</a></div>
        @endforelse
    </div>

    @if($tasks->hasPages())<div class="mt-8">{{ $tasks->links() }}</div>@endif
</div>
@endsection