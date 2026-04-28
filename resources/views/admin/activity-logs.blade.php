@extends('layouts.app')

@section('title', 'Activity Logs')

@section('content')
<div>
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Activity Logs</h1>
        <p class="text-gray-600">Track all system activities and changes</p>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Module</label>
                <select name="module" class="w-full px-3 py-2 border rounded-lg">
                    <option value="">All Modules</option>
                    @foreach($modules as $mod)
                        <option value="{{ $mod }}" {{ request('module') == $mod ? 'selected' : '' }}>{{ ucfirst($mod) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Action</label>
                <select name="action" class="w-full px-3 py-2 border rounded-lg">
                    <option value="">All Actions</option>
                    @foreach($actions as $act)
                        <option value="{{ $act }}" {{ request('action') == $act ? 'selected' : '' }}>{{ str_replace('_', ' ', ucfirst($act)) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">User</label>
                <select name="user_id" class="w-full px-3 py-2 border rounded-lg">
                    <option value="">All Users</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="px-4 py-2 text-white rounded-lg" style="background-color: #0C521C;">Filter</button>
                <a href="{{ route('admin.activity-logs') }}" class="px-4 py-2 border rounded-lg">Clear</a>
            </div>
        </form>
    </div>

    <!-- Logs Table -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Module</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Date & Time</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-green-100 text-green-700 flex items-center justify-center text-xs font-semibold">
                                        {{ substr($log->user->name ?? 'S', 0, 1) }}
                                    </div>
                                    <span class="text-sm text-gray-900">{{ $log->user->name ?? 'System' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs rounded-full
                                    {{ $log->action === 'created' ? 'bg-green-100 text-green-700' : 
                                       ($log->action === 'deleted' ? 'bg-red-100 text-red-700' : 
                                       ($log->action === 'updated' ? 'bg-blue-100 text-blue-700' : 
                                       ($log->action === 'approved' ? 'bg-emerald-100 text-emerald-700' : 
                                       ($log->action === 'rejected' ? 'bg-orange-100 text-orange-700' : 'bg-gray-100 text-gray-700')))) }}">
                                    {{ str_replace('_', ' ', ucfirst($log->action)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ ucfirst($log->module) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $log->description }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $log->created_at->format('M d, Y h:i A') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">No activity logs found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($logs->hasPages())
            <div class="px-6 py-4 border-t">{{ $logs->links() }}</div>
        @endif
    </div>
</div>
@endsection