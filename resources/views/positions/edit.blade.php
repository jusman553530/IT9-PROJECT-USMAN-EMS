@extends('layouts.app')

@section('title', 'Edit Position')

@section('content')
<div>
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Edit Position</h1>
        <p class="text-gray-600">Update position information</p>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-8">
        <form action="{{ route('positions.update', $position) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Position Title -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Position Title *</label>
                    <input 
                        type="text" 
                        name="title" 
                        value="{{ old('title', $position->title) }}"
                        placeholder="e.g., Senior Software Engineer"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600"
                        required
                    >
                    @error('title')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Department -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Department *</label>
                    <select name="department_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600" required>
                        <option value="">Select Department</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" {{ old('department_id', $position->department_id) == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('department_id')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Minimum Salary -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Salary</label>
                    <input 
                        type="number" 
                        name="min_salary" 
                        value="{{ old('min_salary', $position->min_salary) }}"
                        step="0.01"
                        placeholder="e.g., 50000"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600"
                    >
                    @error('min_salary')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Maximum Salary -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Maximum Salary</label>
                    <input 
                        type="number" 
                        name="max_salary" 
                        value="{{ old('max_salary', $position->max_salary) }}"
                        step="0.01"
                        placeholder="e.g., 80000"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600"
                    >
                    @error('max_salary')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('positions.index') }}" 
                   class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button 
                    type="submit"
                    class="px-6 py-3 text-white rounded-lg hover:opacity-90 transition"
                    style="background-color: #0C521C;">
                    Update Position
                </button>
            </div>
        </form>
    </div>
</div>
@endsection