@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div>
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-bold text-gray-900">My Profile</h1>
        <a href="{{ route('profile.edit') }}" class="px-4 py-2 text-white rounded-lg hover:opacity-90" style="background-color: #0C521C;">
            Edit Profile
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Personal Information</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Full Name</p>
                        <p class="font-medium">{{ $employee->full_name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Email</p>
                        <p class="font-medium">{{ $employee->email }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Phone</p>
                        <p class="font-medium">{{ $employee->phone }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Date of Birth</p>
                        <p class="font-medium">{{ $employee->date_of_birth ? $employee->date_of_birth->format('M d, Y') : 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Gender</p>
                        <p class="font-medium">{{ ucfirst($employee->gender) }}</p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-sm text-gray-600">Address</p>
                        <p class="font-medium">{{ $employee->address }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Employment Information</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Employee ID</p>
                        <p class="font-medium">{{ $employee->employee_id }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Department</p>
                        <p class="font-medium">{{ $employee->department->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Position</p>
                        <p class="font-medium">{{ $employee->position->title ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Hire Date</p>
                        <p class="font-medium">{{ $employee->hire_date ? $employee->hire_date->format('M d, Y') : 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Status</p>
                        <span class="px-2 py-1 text-xs rounded-full {{ $employee->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                            {{ ucfirst($employee->status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm h-fit">
            <div class="text-center">
                <div class="w-20 h-20 rounded-full bg-green-100 text-green-700 flex items-center justify-center text-2xl font-semibold mx-auto mb-3">
                    {{ substr($employee->first_name, 0, 1) }}{{ substr($employee->last_name, 0, 1) }}
                </div>
                <h3 class="text-lg font-semibold">{{ $employee->full_name }}</h3>
                <p class="text-gray-600">{{ $employee->position->title ?? 'Employee' }}</p>
                <p class="text-sm text-gray-500">{{ $employee->department->name ?? '' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection