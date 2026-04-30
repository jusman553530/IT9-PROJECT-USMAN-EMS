@extends('layouts.app')

@section('title', 'Departments')

@section('content')
<div>
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Departments</h1>
            <p class="text-gray-600">Organize and manage company departments</p>
        </div>
        <a href="{{ route('departments.create') }}" 
           class="px-6 py-3 text-white rounded-lg hover:opacity-90 transition flex items-center gap-2 w-fit"
           style="background-color: #0C521C;">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add Department
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Departments Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($departments as $department)
            @php
                $colors = [
                    ['bg-green-100 text-green-700', 'bg-green-600'],
                    ['bg-emerald-100 text-emerald-700', 'bg-emerald-600'],
                    ['bg-teal-100 text-teal-700', 'bg-teal-600'],
                    ['bg-lime-100 text-lime-700', 'bg-lime-600'],
                ];
                $colorIndex = $loop->index % 4;
                $colorClass = $colors[$colorIndex][0];
            @endphp
            
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm hover:shadow-md transition-all group">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 rounded-lg {{ $colorClass }} flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <div class="flex gap-1">
                        <a href="{{ route('departments.edit', $department) }}" 
                           class="opacity-0 group-hover:opacity-100 transition-opacity text-gray-400 hover:text-green-600 p-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </a>
                        <form action="{{ route('departments.destroy', $department) }}" 
                              method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this department?')"
                              class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="opacity-0 group-hover:opacity-100 transition-opacity text-gray-400 hover:text-red-600 p-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
                
                <a href="{{ route('departments.show', $department) }}" class="block">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $department->name }}</h3>
                    <p class="text-sm text-gray-600 mb-1">Code: {{ $department->code }}</p>
                    @if($department->description)
                        <p class="text-sm text-gray-500 mb-4 line-clamp-2">{{ Str::limit($department->description, 50) }}</p>
                    @endif
                    <div class="flex items-center gap-2 text-sm text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <span>{{ $department->employees_count ?? $department->employees()->count() }} employees</span>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-span-full">
                <div class="bg-white rounded-xl border border-gray-200 p-12 text-center">
                    <svg class="w-20 h-20 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No departments yet</h3>
                    <p class="text-gray-500 mb-6">Get started by creating your first department.</p>
                    <a href="{{ route('departments.create') }}" 
                       class="inline-flex items-center gap-2 px-6 py-3 text-white rounded-lg hover:opacity-90 transition"
                       style="background-color: #0C521C;">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add Department
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($departments->hasPages())
        <div class="mt-8">
            {{ $departments->links() }}
        </div>
    @endif
</div>
@endsection