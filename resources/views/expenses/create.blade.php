@extends('layouts.app')

@section('title', 'Record Expense')

@section('content')
<div>
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Record Expense</h1>
        <p class="text-gray-600">Submit a new expense for approval</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-8 max-w-2xl">
        <form action="{{ route('expenses.store') }}" method="POST">
            @csrf
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                    <select name="category" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-green-600" required>
                        <option value="">Select Category</option>
                        <option value="Software">Software</option>
                        <option value="Travel">Travel</option>
                        <option value="Training">Training</option>
                        <option value="Equipment">Equipment</option>
                        <option value="Marketing">Marketing</option>
                        <option value="Office Supplies">Office Supplies</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                    <input type="text" name="description" value="{{ old('description') }}" placeholder="e.g., Annual SaaS licenses renewal" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-green-600" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Department *</label>
                    <select name="department_id" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-green-600" required>
                        <option value="">Select Department</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Amount *</label>
                        <input type="number" name="amount" value="{{ old('amount') }}" step="0.01" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-green-600" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date *</label>
                        <input type="date" name="expense_date" value="{{ old('expense_date', date('Y-m-d')) }}" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-green-600" required>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea name="notes" rows="3" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-green-600">{{ old('notes') }}</textarea>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-8 pt-6 border-t">
                <a href="{{ route('expenses.index') }}" class="px-6 py-3 border rounded-lg text-gray-700 hover:bg-gray-50">Cancel</a>
                <button type="submit" class="px-6 py-3 text-white rounded-lg hover:opacity-90" style="background-color: #0C521C;">Submit Expense</button>
            </div>
        </form>
    </div>
</div>
@endsection