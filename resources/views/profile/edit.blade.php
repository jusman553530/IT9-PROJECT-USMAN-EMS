@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div>
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Edit Profile</h1>
        <p class="text-gray-600">Update your personal information</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-8">
        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">First Name *</label>
                        <input type="text" name="first_name" value="{{ old('first_name', $employee->first_name) }}" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-green-600" required>
                        @error('first_name')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Last Name *</label>
                        <input type="text" name="last_name" value="{{ old('last_name', $employee->last_name) }}" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-green-600" required>
                        @error('last_name')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone *</label>
                        <input type="text" name="phone" value="{{ old('phone', $employee->phone) }}" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-green-600" required>
                        @error('phone')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Address *</label>
                        <textarea name="address" rows="2" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-green-600" required>{{ old('address', $employee->address) }}</textarea>
                        @error('address')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="border-t pt-6">
                    <h3 class="text-md font-semibold text-gray-900 mb-4">Change Password (optional)</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                            <input type="password" name="password" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-green-600">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-green-600">
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-8 pt-6 border-t">
                <a href="{{ route('profile.show') }}" class="px-6 py-3 border rounded-lg text-gray-700 hover:bg-gray-50">Cancel</a>
                <button type="submit" class="px-6 py-3 text-white rounded-lg hover:opacity-90" style="background-color: #0C521C;">Update Profile</button>
            </div>
        </form>
    </div>
</div>
@endsection