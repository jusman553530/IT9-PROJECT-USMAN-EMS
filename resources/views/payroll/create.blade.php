@extends('layouts.app')

@section('title', 'Generate Payroll')

@section('content')
<div>
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Generate Payroll</h1>
        <p class="text-gray-600">Create a new payroll record for an employee</p>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-8">
        <form action="{{ route('payroll.store') }}" method="POST">
            @csrf

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
                                            {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
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
                                id="period_start"
                                value="{{ old('period_start', date('Y-m-01')) }}"
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
                                id="period_end"
                                value="{{ old('period_end', date('Y-m-t')) }}"
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
                                value="{{ old('base_salary', 0) }}"
                                step="0.01"
                                min="0"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600"
                                required
                            >
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Overtime Hours</label>
                            <input 
                                type="number" 
                                name="overtime_hours" 
                                id="overtime_hours"
                                value="{{ old('overtime_hours', 0) }}"
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
                                value="{{ old('overtime_pay', 0) }}"
                                step="0.01"
                                min="0"
                                readonly
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-700"
                            >
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Bonus</label>
                            <input 
                                type="number" 
                                name="bonus" 
                                id="bonus"
                                value="{{ old('bonus', 0) }}"
                                step="0.01"
                                min="0"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600"
                            >
                        </div>
                    </div>
                </div>

                <!-- Automatic Deductions (Read Only) -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">Deductions (Auto-calculated)</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Income Tax (10%)</label>
                            <input 
                                type="number" 
                                name="tax" 
                                id="tax"
                                value="{{ old('tax', 0) }}"
                                step="0.01"
                                readonly
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-red-600 font-medium"
                            >
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Health Insurance</label>
                            <input 
                                type="number" 
                                name="health_insurance" 
                                id="health_insurance"
                                value="{{ old('health_insurance', 0) }}"
                                step="0.01"
                                readonly
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-red-600 font-medium"
                            >
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Provident Fund</label>
                            <input 
                                type="number" 
                                name="provident_fund" 
                                id="provident_fund"
                                value="{{ old('provident_fund', 0) }}"
                                step="0.01"
                                readonly
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-red-600 font-medium"
                            >
                        </div>
                    </div>
                    <input type="hidden" name="deductions" id="total_deductions" value="0">
                </div>

                <!-- Net Salary Preview -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <span class="text-lg font-semibold text-gray-900">Net Salary:</span>
                        <span class="text-2xl font-bold text-green-600" id="net_salary_preview">$0.00</span>
                    </div>
                    <div class="mt-3 text-sm text-gray-500 space-y-1" id="breakdown">
                    </div>
                </div>

                <!-- Additional Details -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">Additional Details</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Payment Date</label>
                            <input 
                                type="date" 
                                name="payment_date" 
                                value="{{ old('payment_date') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600"
                            >
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                            <select name="payment_method" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600">
                                <option value="">Select Method</option>
                                <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="check" {{ old('payment_method') == 'check' ? 'selected' : '' }}>Check</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                            <textarea 
                                name="notes" 
                                rows="3"
                                placeholder="Additional notes..."
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600"
                            >{{ old('notes') }}</textarea>
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
                    Generate Payroll
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const employeeSelect = document.getElementById('employee_select');
    const baseSalaryInput = document.getElementById('base_salary');
    const overtimeHours = document.getElementById('overtime_hours');
    const overtimePay = document.getElementById('overtime_pay');
    const bonusInput = document.getElementById('bonus');
    const taxInput = document.getElementById('tax');
    const healthInsurance = document.getElementById('health_insurance');
    const providentFund = document.getElementById('provident_fund');
    const totalDeductionsInput = document.getElementById('total_deductions');
    const netSalaryPreview = document.getElementById('net_salary_preview');
    const breakdown = document.getElementById('breakdown');
    
    // Auto-fill base salary when employee is selected
    employeeSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const salary = selectedOption.dataset.salary;
        if (salary) {
            baseSalaryInput.value = salary;
            calculateAll();
        }
    });
    
    function calculateAll() {
        const baseSalary = parseFloat(baseSalaryInput.value) || 0;
        const overtime = parseFloat(overtimePay.value) || 0;
        const bonus = parseFloat(bonusInput.value) || 0;
        
        // Auto-calculate deductions
        const incomeTax = baseSalary * 0.10; // 10% income tax
        const healthIns = 500; // Fixed $500 health insurance
        const provFund = baseSalary * 0.05; // 5% provident fund
        
        taxInput.value = incomeTax.toFixed(2);
        healthInsurance.value = healthIns.toFixed(2);
        providentFund.value = provFund.toFixed(2);
        
        const totalDeductions = incomeTax + healthIns + provFund;
        totalDeductionsInput.value = totalDeductions.toFixed(2);
        
        // Calculate net salary
        const totalEarnings = baseSalary + overtime + bonus;
        const netSalary = totalEarnings - totalDeductions;
        
        netSalaryPreview.textContent = '$' + netSalary.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        
        // Show breakdown
        breakdown.innerHTML = `
            <div class="flex justify-between"><span>Base Salary:</span><span class="text-green-600">+$${baseSalary.toFixed(2)}</span></div>
            ${overtime > 0 ? `<div class="flex justify-between"><span>Overtime:</span><span class="text-green-600">+$${overtime.toFixed(2)}</span></div>` : ''}
            ${bonus > 0 ? `<div class="flex justify-between"><span>Bonus:</span><span class="text-green-600">+$${bonus.toFixed(2)}</span></div>` : ''}
            <div class="flex justify-between"><span>Income Tax (10%):</span><span class="text-red-500">-$${incomeTax.toFixed(2)}</span></div>
            <div class="flex justify-between"><span>Health Insurance:</span><span class="text-red-500">-$${healthIns.toFixed(2)}</span></div>
            <div class="flex justify-between"><span>Provident Fund (5%):</span><span class="text-red-500">-$${provFund.toFixed(2)}</span></div>
        `;
    }
    
    // Event listeners
    [baseSalaryInput, overtimePay, bonusInput].forEach(input => {
        input.addEventListener('input', calculateAll);
    });
    
    // Auto-calculate overtime pay
    overtimeHours.addEventListener('input', function() {
        const hours = parseFloat(this.value) || 0;
        const baseSalary = parseFloat(baseSalaryInput.value) || 0;
        const hourlyRate = baseSalary / 160;
        const overtimeRate = hourlyRate * 1.5;
        overtimePay.value = (hours * overtimeRate).toFixed(2);
        calculateAll();
    });
    
    // Initialize
    calculateAll();
});
</script>
@endsection