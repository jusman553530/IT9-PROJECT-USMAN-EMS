@extends('layouts.app')

@section('title', 'Edit Payroll')

@section('content')
<div>
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Edit Payroll</h1>
        <p class="text-gray-600">Update payroll record #{{ $payroll->payroll_code }}</p>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-8">
        <form action="{{ route('payroll.update', $payroll) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-8">
                <!-- Employee & Period -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">Employee & Period</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Employee *</label>
                            <select name="employee_id" id="employee_select" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600" required>
                                <option value="">Select Employee</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" 
                                            data-salary="{{ $employee->salary }}"
                                            {{ old('employee_id', $payroll->employee_id) == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->full_name }} ({{ $employee->employee_id }}) - ${{ number_format($employee->salary, 2) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('employee_id')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Period Start *</label>
                            <input 
                                type="date" 
                                name="period_start" 
                                value="{{ old('period_start', $payroll->period_start->format('Y-m-d')) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600"
                                required
                            >
                            @error('period_start')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Period End *</label>
                            <input 
                                type="date" 
                                name="period_end" 
                                value="{{ old('period_end', $payroll->period_end->format('Y-m-d')) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600"
                                required
                            >
                            @error('period_end')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Earnings -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">Earnings</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Base Salary *</label>
                            <input 
                                type="number" 
                                name="base_salary" 
                                id="base_salary"
                                value="{{ old('base_salary', $payroll->base_salary) }}"
                                step="0.01"
                                min="0"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600"
                                required
                            >
                            @error('base_salary')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Overtime Hours</label>
                            <input 
                                type="number" 
                                name="overtime_hours" 
                                id="overtime_hours"
                                value="{{ old('overtime_hours', $payroll->overtime_hours) }}"
                                step="0.5"
                                min="0"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600"
                            >
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Overtime Pay</label>
                            <input 
                                type="number" 
                                name="overtime_pay" 
                                id="overtime_pay"
                                value="{{ old('overtime_pay', $payroll->overtime_pay) }}"
                                step="0.01"
                                min="0"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600"
                            >
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Bonus</label>
                            <input 
                                type="number" 
                                name="bonus" 
                                value="{{ old('bonus', $payroll->bonus) }}"
                                step="0.01"
                                min="0"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600"
                            >
                        </div>
                    </div>
                </div>

                <!-- Deductions -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">Deductions</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Deductions</label>
                            <input 
                                type="number" 
                                name="deductions" 
                                value="{{ old('deductions', $payroll->deductions) }}"
                                step="0.01"
                                min="0"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600"
                            >
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tax</label>
                            <input 
                                type="number" 
                                name="tax" 
                                value="{{ old('tax', $payroll->tax) }}"
                                step="0.01"
                                min="0"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600"
                            >
                        </div>
                    </div>
                </div>

                <!-- Net Salary Preview -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <span class="text-lg font-semibold text-gray-900">Net Salary:</span>
                        <span class="text-2xl font-bold text-green-600" id="net_salary_preview">${{ number_format($payroll->net_salary, 2) }}</span>
                    </div>
                </div>

                <!-- Additional Details -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">Additional Details</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                            <select name="status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600" required>
                                <option value="draft" {{ old('status', $payroll->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="pending" {{ old('status', $payroll->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ old('status', $payroll->status) == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="paid" {{ old('status', $payroll->status) == 'paid' ? 'selected' : '' }}>Paid</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Payment Date</label>
                            <input 
                                type="date" 
                                name="payment_date" 
                                value="{{ old('payment_date', $payroll->payment_date ? $payroll->payment_date->format('Y-m-d') : '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600"
                            >
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                            <select name="payment_method" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600">
                                <option value="">Select Method</option>
                                <option value="bank_transfer" {{ old('payment_method', $payroll->payment_method) == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="cash" {{ old('payment_method', $payroll->payment_method) == 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="check" {{ old('payment_method', $payroll->payment_method) == 'check' ? 'selected' : '' }}>Check</option>
                            </select>
                        </div>
                        <div class="md:col-span-3">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                            <textarea 
                                name="notes" 
                                rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600"
                            >{{ old('notes', $payroll->notes) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('payroll.index') }}" 
                   class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button 
                    type="submit"
                    class="px-6 py-3 text-white rounded-lg hover:opacity-90 transition"
                    style="background-color: #0C521C;">
                    Update Payroll
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Same calculation script as create.blade.php
document.addEventListener('DOMContentLoaded', function() {
    const baseSalaryInput = document.getElementById('base_salary');
    const overtimeHours = document.getElementById('overtime_hours');
    const overtimePay = document.getElementById('overtime_pay');
    const bonusInput = document.querySelector('input[name="bonus"]');
    const deductionsInput = document.querySelector('input[name="deductions"]');
    const taxInput = document.querySelector('input[name="tax"]');
    const netSalaryPreview = document.getElementById('net_salary_preview');
    
    function calculateNetSalary() {
        const baseSalary = parseFloat(baseSalaryInput.value) || 0;
        const overtime = parseFloat(overtimePay.value) || 0;
        const bonus = parseFloat(bonusInput.value) || 0;
        const deductions = parseFloat(deductionsInput.value) || 0;
        const tax = parseFloat(taxInput.value) || 0;
        
        const totalEarnings = baseSalary + overtime + bonus;
        const totalDeductions = deductions + tax;
        const netSalary = totalEarnings - totalDeductions;
        
        netSalaryPreview.textContent = '$' + netSalary.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    }
    
    [baseSalaryInput, overtimePay, bonusInput, deductionsInput, taxInput].forEach(input => {
        input.addEventListener('input', calculateNetSalary);
    });
    
    overtimeHours.addEventListener('input', function() {
        const hours = parseFloat(this.value) || 0;
        const baseSalary = parseFloat(baseSalaryInput.value) || 0;
        const hourlyRate = baseSalary / 160;
        const overtimeRate = hourlyRate * 1.5;
        overtimePay.value = (hours * overtimeRate).toFixed(2);
        calculateNetSalary();
    });
});
</script>
@endsection