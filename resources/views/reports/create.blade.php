@extends('layouts.app')

@section('title', 'Report a Problem')

@section('content')
<div>
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Report a Problem</h1>
        <p class="text-gray-600">Describe your issue and we'll address it as soon as possible</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-8">
        <form action="{{ route('reports.store') }}" method="POST">
            @csrf
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                    <input type="text" name="title" value="{{ old('title') }}" placeholder="Brief summary of the problem" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-green-600" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                    <textarea name="description" rows="5" placeholder="Describe the problem in detail..." class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-green-600" required>{{ old('description') }}</textarea>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Type *</label>
                        <select name="type" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-green-600" required>
                            <option value="technical">Technical</option>
                            <option value="hr">HR</option>
                            <option value="facilities">Facilities</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Priority *</label>
                        <select name="priority" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-green-600" required>
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                            <option value="urgent">Urgent</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-8 pt-6 border-t">
                <a href="{{ route('reports.index') }}" class="px-6 py-3 border rounded-lg text-gray-700 hover:bg-gray-50">Cancel</a>
                <button type="submit" class="px-6 py-3 text-white rounded-lg hover:opacity-90" style="background-color: #0C521C;">Submit Report</button>
            </div>
        </form>
    </div>
</div>
@endsection