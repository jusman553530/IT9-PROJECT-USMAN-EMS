@extends('layouts.app')

@section('title', $position->title)

@section('content')
<div>
    <!-- Page Header -->
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $position->title }}</h1>
            <p class="text-gray-600">{{ $position->department->name ?? 'No Department' }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('positions.edit', $position) }}" 
               class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Edit Position
            </a>
            <a href="{{ route('positions.index') }}" 
               class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Back to List
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Position Details</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Title</p>
                        <p class="text-gray-900 font-medium">{{ $position->title }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Department</p>
                        <p class="text-gray-900">{{ $position->department->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Salary Range</p>
                        <p class="text-gray-900">
                            @if($position->min_salary && $position->max_salary)
                                ${{ number_format($position->min_salary) }} - ${{ number_format($position->max_salary) }}
                            @elseif($position->min_salary)
                                From ${{ number_format($position->min_salary) }}
                            @elseif($position->max_salary)
                                Up to ${{ number_format($position->max_salary) }}
                            @else
                                <span class="text-gray-400">Not specified</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Total Employees</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $employees->total() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Quick Stats</h2>
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-600">Created</p>
                    <p class="text-gray-900">{{ $position->created_at->format('M d, Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Last Updated</p>
                    <p class="text-gray-900">{{ $position->updated_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Employees in this Position -->
    <div class="mt-8 bg-white rounded-xl border border-gray-200 shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Employees in this Position</h2>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee ID</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($employees as $employee)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $employee->employee_id }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $employee->full_name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $employee->email }}</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-2 py-1 text-xs rounded-full 
                                    {{ $employee->status === 'active' ? 'bg-green-100 text-green-700' : 
                                       ($employee->status === 'on_leave' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                    {{ ucfirst(str_replace('_', ' ', $employee->status)) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                No employees assigned to this position yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($employees->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $employees->links() }}
            </div>
        @endif
    </div>
</div>
@endsection