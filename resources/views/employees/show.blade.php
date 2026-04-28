@extends('layouts.app')

@section('title', $employee->full_name)

@section('content')
<div>
    <!-- Page Header -->
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $employee->full_name }}</h1>
            <p class="text-gray-600">Employee ID: {{ $employee->employee_id }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('employees.edit', $employee) }}" 
               class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Edit Employee
            </a>
            <a href="{{ route('employees.index') }}" 
               class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Back to List
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Personal Information -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Personal Information</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Full Name</p>
                        <p class="text-gray-900 font-medium">{{ $employee->full_name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Email</p>
                        <p class="text-gray-900">{{ $employee->email }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Phone</p>
                        <p class="text-gray-900">{{ $employee->phone }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Date of Birth</p>
                        <p class="text-gray-900">{{ $employee->date_of_birth->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Gender</p>
                        <p class="text-gray-900">{{ ucfirst($employee->gender) }}</p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-sm text-gray-600">Address</p>
                        <p class="text-gray-900">{{ $employee->address }}</p>
                    </div>
                </div>
            </div>

            <!-- Employment Information -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Employment Information</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Department</p>
                        <p class="text-gray-900">{{ $employee->department->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Position</p>
                        <p class="text-gray-900">{{ $employee->position->title ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Hire Date</p>
                        <p class="text-gray-900">{{ $employee->hire_date->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Salary</p>
                        <p class="text-gray-900">${{ number_format($employee->salary, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Status</p>
                        <span class="px-2 py-1 text-xs rounded-full 
                            {{ $employee->status === 'active' ? 'bg-green-100 text-green-700' : 
                               ($employee->status === 'on_leave' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                            {{ ucfirst(str_replace('_', ' ', $employee->status)) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Quick Stats</h2>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-600">Time with Company</p>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ $employee->hire_date->diffForHumans(null, ['parts' => 2]) }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Age</p>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ $employee->date_of_birth->age }} years
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection