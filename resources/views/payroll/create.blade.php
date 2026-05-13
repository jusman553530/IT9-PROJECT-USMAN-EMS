@extends('layouts.app')

@section('title', 'Create Payslip')

@section('content')
<div class="pb-28">
    <!-- Breadcrumb + Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 mb-6">
        <div>
            <a href="{{ route('payroll.index') }}" class="flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 mb-3">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Payroll Management
            </a>
            <div class="flex items-center gap-3">
                <h1 class="text-3xl font-bold text-gray-900">Create Payslip</h1>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs bg-amber-100 text-amber-700 border border-amber-200">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Draft
                </span>
            </div>
            <p class="text-gray-600 text-sm mt-1">Philippine Payroll Standards · Semi-Monthly Payroll</p>
        </div>
        <div class="flex items-center gap-2 text-xs text-gray-500 bg-white border border-gray-200 rounded-lg px-3 py-2 shadow-sm">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Compliant with BIR, SSS, PhilHealth & HDMF 2026
        </div>
    </div>

    <form action="{{ route('payroll.store') }}" method="POST" id="payrollForm">
        @csrf
        
        <!-- Employee Context Card -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 mb-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                <div>
                    <label class="text-xs text-gray-500 mb-1.5 block font-medium">Employee</label>
                    <div class="relative">
                        <div class="absolute left-3 top-1/2 -translate-y-1/2 w-7 h-7 rounded-full bg-green-100 flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <select name="employee_id" id="employeeSelect" class="w-full pl-12 pr-10 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-600" required>
                            <option value="">Select Employee</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}" data-salary="{{ $emp->salary }}" data-name="{{ $emp->full_name }}" data-dept="{{ $emp->department->name ?? '' }}" data-position="{{ $emp->position->title ?? '' }}">
                                    {{ $emp->full_name }} ({{ $emp->employee_id }}) — {{ $emp->position->title ?? 'N/A' }}
                                </option>
                            @endforeach
                        </select>
                        <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
                <div>
                    <label class="text-xs text-gray-500 mb-1.5 block font-medium">Pay Period</label>
                    <select name="pay_period" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-600">
                        <option value="1-15">1st Half (1-15)</option>
                        <option value="16-31">2nd Half (16-31)</option>
                    </select>
                </div>
            </div>
            <input type="hidden" name="period_start" id="periodStart">
            <input type="hidden" name="period_end" id="periodEnd">
        </div>

        <!-- Main 2-Column Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
            
            <!-- LEFT COLUMN (3/5) -->
            <div class="lg:col-span-3 space-y-5">
                
                <!-- 1. Standard Income -->
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="flex items-start gap-3 px-5 py-3.5 border-b border-gray-200 bg-gray-50">
                        <span class="w-2 h-2 rounded-full mt-1.5 bg-green-600"></span>
                        <div><p class="text-sm font-semibold text-gray-900">Standard Income</p><p class="text-xs text-gray-500 mt-0.5">Basic compensation for the current pay period</p></div>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="text-xs text-gray-500 mb-1 block">Basic Salary (Monthly)</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400">₱</span>
                                    <input type="number" name="base_salary" id="baseSalary" value="0" step="0.01" class="w-full pl-7 pr-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-600" required>
                                </div>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 mb-1 block">Days Worked <span class="text-gray-400">(out of 13)</span></label>
                                <input type="number" name="days_worked" id="daysWorked" value="11" min="0" max="13" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-600">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs text-gray-500 mb-1 block">Daily Rate</label>
                                <div class="flex items-center justify-between px-3 py-2.5 rounded-lg border bg-gray-50 border-gray-200">
                                    <span class="text-xs text-gray-400">AUTO</span>
                                    <span class="text-sm font-semibold" id="dailyRate">₱0.00</span>
                                </div>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 mb-1 block">Basic Pay (This Period)</label>
                                <div class="flex items-center justify-between px-3 py-2.5 rounded-lg border bg-green-50 border-green-200">
                                    <span class="text-xs text-green-600">AUTO</span>
                                    <span class="text-sm font-semibold text-green-700" id="basicPay">₱0.00</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Variable Income (OT & NSD) -->
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="flex items-start gap-3 px-5 py-3.5 border-b border-gray-200 bg-gray-50">
                        <span class="w-2 h-2 rounded-full mt-1.5 bg-blue-500"></span>
                        <div><p class="text-sm font-semibold text-gray-900">Variable Income</p><p class="text-xs text-gray-500 mt-0.5">Overtime, rest day premiums & night shift differential</p></div>
                    </div>
                    <div class="p-5 space-y-4">
                        @foreach([
                            ['name' => 'regular_ot_hours', 'label' => 'Regular Overtime', 'rate' => '× 1.25', 'amount' => 'regular_ot_amount'],
                            ['name' => 'rest_day_ot_hours', 'label' => 'Rest Day Overtime', 'rate' => '× 1.30', 'amount' => 'rest_day_ot_amount'],
                            ['name' => 'holiday_ot_hours', 'label' => 'Holiday Overtime', 'rate' => '× 2.60', 'amount' => 'holiday_ot_amount'],
                            ['name' => 'nsd_hours', 'label' => 'Night Shift Differential', 'rate' => '× 0.10', 'amount' => 'nsd_amount'],
                        ] as $ot)
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-xs text-gray-500 mb-1 block">{{ $ot['label'] }} <span class="text-gray-400">({{ $ot['rate'] }})</span></label>
                                <div class="relative">
                                    <input type="number" name="{{ $ot['name'] }}" value="0" step="0.5" min="0" class="ot-hours w-full pl-3 pr-9 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-600">
                                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400">hrs</span>
                                </div>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 mb-1 block">Peso Amount</label>
                                <div class="flex items-center justify-between px-3 py-2.5 rounded-lg border bg-gray-50 border-gray-200">
                                    <span class="text-xs text-gray-400">AUTO</span>
                                    <span class="text-sm font-semibold ot-amount" id="{{ $ot['amount'] }}">₱0.00</span>
                                </div>
                                <input type="hidden" name="{{ $ot['amount'] }}" value="0">
                            </div>
                        </div>
                        @endforeach
                        <div class="pt-4 border-t border-gray-200 flex items-center justify-between">
                            <span class="text-sm text-gray-500 font-medium">Total Variable Income</span>
                            <span class="text-sm font-bold" id="totalOT">₱0.00</span>
                        </div>
                    </div>
                </div>

                <!-- 3. Allowances -->
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="flex items-start gap-3 px-5 py-3.5 border-b border-gray-200 bg-gray-50">
                        <span class="w-2 h-2 rounded-full mt-1.5 bg-purple-500"></span>
                        <div><p class="text-sm font-semibold text-gray-900">Allowances & De Minimis Benefits</p><p class="text-xs text-gray-500 mt-0.5">Non-taxable within BIR limits</p></div>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label class="text-xs text-gray-500 mb-1 block">Rice Subsidy <span class="text-gray-400">(max ₱1,000)</span></label>
                                <div class="relative"><span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400">₱</span><input type="number" name="rice_subsidy" id="riceSubsidy" value="1000" step="0.01" class="w-full pl-7 pr-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-600"></div>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 mb-1 block">Clothing Allowance <span class="text-gray-400">(max ₱250)</span></label>
                                <div class="relative"><span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400">₱</span><input type="number" name="clothing_allowance" id="clothingAllowance" value="250" step="0.01" class="w-full pl-7 pr-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-600"></div>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 mb-1 block">Transport Allowance <span class="text-gray-400">(max ₱1,000)</span></label>
                                <div class="relative"><span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400">₱</span><input type="number" name="transport_allowance" id="transportAllowance" value="1000" step="0.01" class="w-full pl-7 pr-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-600"></div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between bg-green-50 rounded-lg border border-green-200 px-4 py-3.5">
                            <div><p class="text-sm font-semibold text-gray-900">Total Gross Pay</p><p class="text-xs text-gray-500">Basic Pay + OT + Allowances</p></div>
                            <p class="text-xl font-bold text-green-700" id="grossPay">₱0.00</p>
                            <input type="hidden" name="gross_pay" id="grossPayInput" value="0">
                        </div>
                    </div>
                </div>

                <!-- 4. Other Deductions -->
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="flex items-start gap-3 px-5 py-3.5 border-b border-gray-200 bg-gray-50">
                        <span class="w-2 h-2 rounded-full mt-1.5 bg-red-500"></span>
                        <div><p class="text-sm font-semibold text-gray-900">Other Deductions</p><p class="text-xs text-gray-500 mt-0.5">Tardiness, salary loans, and voluntary contributions</p></div>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="text-xs text-gray-500 mb-1 block">Tardiness / Undertime</label>
                                <div class="relative"><span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400">₱</span><input type="number" name="tardiness_deduction" id="tardiness" value="0" step="0.01" class="w-full pl-7 pr-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-600"></div>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 mb-1 block">SSS Salary Loan</label>
                                <div class="relative"><span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400">₱</span><input type="number" name="sss_loan" id="sssLoan" value="0" step="0.01" class="w-full pl-7 pr-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-600"></div>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 mb-1 block">Pag-IBIG Salary Loan</label>
                                <div class="relative"><span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400">₱</span><input type="number" name="pagibig_loan" id="pagibigLoan" value="0" step="0.01" class="w-full pl-7 pr-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-600"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN (2/5) -->
            <div class="lg:col-span-2 space-y-5">
                
                <!-- 5. Statutory Deductions -->
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="flex items-start gap-3 px-5 py-3.5 border-b border-gray-200 bg-gray-50">
                        <span class="w-2 h-2 rounded-full mt-1.5 bg-red-500"></span>
                        <div><p class="text-sm font-semibold text-gray-900">Statutory Deductions</p><p class="text-xs text-gray-500 mt-0.5">Auto-calculated — read-only</p></div>
                    </div>
                    <div class="p-5 space-y-3">
                        <div class="flex items-start justify-between py-3 border-b border-gray-100">
                            <div>
                                <p class="text-sm font-medium">SSS EE Premium</p>
                                <p class="text-xs text-gray-400" id="sssSubtext">MSC: ₱0 · EE rate: 4.5%</p>
                            </div>
                            <span class="text-sm font-semibold text-red-600" id="sssAmount">–₱0.00</span>
                            <input type="hidden" name="sss_contribution" id="sssInput" value="0">
                        </div>
                        <div class="flex items-start justify-between py-3 border-b border-gray-100">
                            <div><p class="text-sm font-medium">SSS WISP</p><p class="text-xs text-gray-400">1% on MSC > ₱20,000</p></div>
                            <span class="text-sm font-semibold text-red-600" id="wispAmount">–₱0.00</span>
                            <input type="hidden" name="sss_wisp" id="wispInput" value="0">
                        </div>
                        <div class="flex items-start justify-between py-3 border-b border-gray-100">
                            <div><p class="text-sm font-medium">PhilHealth EE Premium</p><p class="text-xs text-gray-400">5% of salary; 50% EE share</p></div>
                            <span class="text-sm font-semibold text-red-600" id="philhealthAmount">–₱0.00</span>
                            <input type="hidden" name="philhealth_contribution" id="philhealthInput" value="0">
                        </div>
                        <div class="flex items-start justify-between py-3 border-b border-gray-100">
                            <div><p class="text-sm font-medium">Pag-IBIG / HDMF EE</p><p class="text-xs text-gray-400">2% of salary; max ₱100/semi</p></div>
                            <span class="text-sm font-semibold text-red-600" id="pagibigAmount">–₱0.00</span>
                            <input type="hidden" name="pagibig_contribution" id="pagibigInput" value="0">
                        </div>
                        <div class="flex items-start justify-between py-3">
                            <div>
                                <p class="text-sm font-medium">BIR Withholding Tax</p>
                                <p class="text-xs text-gray-400" id="whtSubtext">Taxable basis: ₱0.00</p>
                                <span class="inline-flex items-center gap-1 mt-1 text-xs text-amber-600 bg-amber-50 border border-amber-200 rounded px-1.5 py-0.5">TRAIN Law graduated rate</span>
                            </div>
                            <span class="text-sm font-semibold text-red-600" id="whtAmount">–₱0.00</span>
                            <input type="hidden" name="withholding_tax" id="whtInput" value="0">
                        </div>
                        <div class="pt-3 border-t border-gray-200 flex items-center justify-between">
                            <span class="text-sm font-semibold">Total Statutory</span>
                            <span class="text-sm font-bold text-red-600" id="totalStatutory">–₱0.00</span>
                        </div>
                    </div>
                </div>

                <!-- 6. Final Summary -->
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="flex items-start gap-3 px-5 py-3.5 border-b border-gray-200 bg-gray-50">
                        <span class="w-2 h-2 rounded-full mt-1.5 bg-green-600"></span>
                        <p class="text-sm font-semibold text-gray-900">Payslip Summary</p>
                    </div>
                    <div class="p-5 space-y-3">
                        <div class="flex justify-between py-1"><span class="text-sm text-gray-500">Total Gross Pay</span><span class="text-sm font-semibold" id="summaryGross">₱0.00</span></div>
                        <div class="flex justify-between py-1"><span class="text-sm text-gray-500">Statutory Deductions</span><span class="text-sm text-red-600" id="summaryStatutory">–₱0.00</span></div>
                        <div class="flex justify-between py-1"><span class="text-sm text-gray-500">Other Deductions</span><span class="text-sm text-red-600" id="summaryOther">–₱0.00</span></div>
                        <div class="flex justify-between py-2 px-3 bg-red-50 rounded-lg border border-red-100">
                            <span class="text-sm font-semibold text-red-700">Total Deductions</span>
                            <span class="text-sm font-bold text-red-600" id="totalDeductions">–₱0.00</span>
                        </div>
                        <div class="relative py-1"><div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-200"></div></div><div class="relative flex justify-center"><span class="bg-white px-2 text-xs text-gray-400 uppercase">net pay</span></div></div>
                        <div class="rounded-xl p-5 text-center" style="background-color: #0C521C;">
                            <p class="text-white/70 text-xs uppercase tracking-widest mb-1 font-semibold">Take Home Pay</p>
                            <p class="text-white text-4xl font-extrabold" id="netPay">₱0.00</p>
                            <p class="text-white/60 text-xs mt-1.5" id="netPayInfo"></p>
                            <input type="hidden" name="net_salary" id="netPayInput" value="0">
                            <input type="hidden" name="tax" id="taxInput" value="0">
                            <input type="hidden" name="deductions" id="totalDeductionsInput" value="0">
                            <input type="hidden" name="status" value="pending">
                        </div>
                        <div class="grid grid-cols-2 gap-2 pt-1">
                            <div class="text-center p-2 bg-gray-100 rounded-lg"><p class="text-xs text-gray-500">Gross Pay</p><p class="text-xs font-semibold mt-0.5" id="footerGross">₱0.00</p></div>
                            <div class="text-center p-2 bg-gray-100 rounded-lg"><p class="text-xs text-gray-500">Total Deductions</p><p class="text-xs font-semibold text-red-600 mt-0.5" id="footerDed">–₱0.00</p></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sticky Footer -->
        <div class="fixed bottom-0 left-0 right-0 lg:left-64 bg-white border-t border-gray-200 shadow-lg z-20 px-6 py-4">
            <div class="flex items-center justify-between gap-3 flex-wrap">
                <div class="hidden sm:flex items-center gap-2 text-xs text-gray-500">
                    <span class="font-semibold text-gray-900" id="footerName"></span>
                    <span>·</span>
                    <span>Net Pay: <strong class="text-green-700" id="footerNet">₱0.00</strong></span>
                </div>
                <div class="flex items-center gap-2 ml-auto">
                    <a href="{{ route('payroll.index') }}" class="px-4 py-2.5 rounded-lg text-sm text-gray-500 border border-gray-300 hover:bg-gray-50">Cancel</a>
                    <button type="submit" class="px-5 py-2.5 rounded-lg text-sm text-white hover:opacity-90" style="background-color: #0C521C;">Generate Payslip</button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const baseSalaryInput = document.getElementById('baseSalary');
    const daysWorkedInput = document.getElementById('daysWorked');
    const employeeSelect = document.getElementById('employeeSelect');
    
    // Auto-fill when employee selected
    employeeSelect.addEventListener('change', function() {
        const opt = this.options[this.selectedIndex];
        const salary = opt.dataset.salary;
        if (salary) {
            baseSalaryInput.value = salary;
            document.getElementById('footerName').textContent = opt.dataset.name;
        }
        calculateAll();
    });

    // Recalculate on input change
    const inputs = document.querySelectorAll('input[type="number"]');
    inputs.forEach(input => input.addEventListener('input', calculateAll));

    function calculateAll() {
        const baseSalary = parseFloat(baseSalaryInput.value) || 0;
        const daysWorked = parseInt(daysWorkedInput.value) || 0;
        
        // Daily rate: monthly / 26 working days
        const dailyRate = baseSalary / 26;
        const basicPay = dailyRate * daysWorked;
        const hourlyRate = dailyRate / 8;
        
        document.getElementById('dailyRate').textContent = '₱' + dailyRate.toFixed(2);
        document.getElementById('basicPay').textContent = '₱' + basicPay.toFixed(2);

        // OT calculations
        const rates = [1.25, 1.30, 2.60, 0.10];
        const otIds = ['regular_ot_hours', 'rest_day_ot_hours', 'holiday_ot_hours', 'nsd_hours'];
        const amtIds = ['regular_ot_amount', 'rest_day_ot_amount', 'holiday_ot_amount', 'nsd_amount'];
        let totalOT = 0;
        
        otIds.forEach((id, i) => {
            const hours = parseFloat(document.querySelector(`[name="${id}"]`).value) || 0;
            const amount = hours * hourlyRate * rates[i];
            totalOT += amount;
            document.getElementById(amtIds[i]).textContent = '₱' + amount.toFixed(2);
            document.querySelector(`[name="${amtIds[i]}"]`).value = amount.toFixed(2);
        });
        document.getElementById('totalOT').textContent = '₱' + totalOT.toFixed(2);

        // Allowances
        const rice = parseFloat(document.getElementById('riceSubsidy').value) || 0;
        const clothing = parseFloat(document.getElementById('clothingAllowance').value) || 0;
        const transport = parseFloat(document.getElementById('transportAllowance').value) || 0;
        const totalAllowances = rice + clothing + transport;
        
        const grossPay = basicPay + totalOT + totalAllowances;
        document.getElementById('grossPay').textContent = '₱' + grossPay.toFixed(2);
        document.getElementById('grossPayInput').value = grossPay.toFixed(2);
        document.getElementById('summaryGross').textContent = '₱' + grossPay.toFixed(2);
        document.getElementById('footerGross').textContent = '₱' + grossPay.toFixed(2);

        // PH Statutory deductions
        const msc = Math.max(4000, Math.min(30000, Math.ceil(baseSalary / 500) * 500));
        const sssEE = Math.round(msc * 0.045 * 100) / 100;
        const sssWISP = msc > 20000 ? Math.round((msc - 20000) * 0.01 * 100) / 100 : 0;
        
        const philTotal = Math.max(500, Math.min(5000, baseSalary * 0.05));
        const philhealthEE = Math.round((philTotal / 2) * 100) / 100;
        
        const pagibigRate = baseSalary <= 1500 ? 0.01 : 0.02;
        const pagibigEE = Math.min(200, Math.round(baseSalary * pagibigRate * 100) / 100);
        
        // Non-taxable limits
        const ntRice = Math.min(rice, 1000);
        const ntClothing = Math.min(clothing, 250);
        const ntTransport = Math.min(transport, 1000);
        const totalNonTaxable = ntRice + ntClothing + ntTransport;
        
        const taxablePerPeriod = Math.max(0, basicPay + totalOT + (totalAllowances - totalNonTaxable) - sssEE - sssWISP - philhealthEE - pagibigEE);
        
        // WHT (TRAIN Law)
        const annualTaxable = taxablePerPeriod * 24;
        let wht = 0;
        if (annualTaxable > 8000000) wht = (2410000 + (annualTaxable - 8000000) * 0.35) / 24;
        else if (annualTaxable > 2000000) wht = (490000 + (annualTaxable - 2000000) * 0.32) / 24;
        else if (annualTaxable > 800000) wht = (130000 + (annualTaxable - 800000) * 0.30) / 24;
        else if (annualTaxable > 400000) wht = (30000 + (annualTaxable - 400000) * 0.25) / 24;
        else if (annualTaxable > 250000) wht = ((annualTaxable - 250000) * 0.20) / 24;
        wht = Math.max(0, Math.round(wht * 100) / 100);
        
        // Update statutory displays
        document.getElementById('sssAmount').textContent = '–₱' + sssEE.toFixed(2);
        document.getElementById('sssInput').value = sssEE.toFixed(2);
        document.getElementById('sssSubtext').textContent = 'MSC: ₱' + msc.toLocaleString() + ' · EE rate: 4.5%';
        
        document.getElementById('wispAmount').textContent = '–₱' + sssWISP.toFixed(2);
        document.getElementById('wispInput').value = sssWISP.toFixed(2);
        
        document.getElementById('philhealthAmount').textContent = '–₱' + philhealthEE.toFixed(2);
        document.getElementById('philhealthInput').value = philhealthEE.toFixed(2);
        
        document.getElementById('pagibigAmount').textContent = '–₱' + pagibigEE.toFixed(2);
        document.getElementById('pagibigInput').value = pagibigEE.toFixed(2);
        
        document.getElementById('whtAmount').textContent = '–₱' + wht.toFixed(2);
        document.getElementById('whtInput').value = wht.toFixed(2);
        document.getElementById('whtSubtext').textContent = 'Taxable basis: ₱' + taxablePerPeriod.toFixed(2);
        
        const totalStatutory = sssEE + sssWISP + philhealthEE + pagibigEE + wht;
        document.getElementById('totalStatutory').textContent = '–₱' + totalStatutory.toFixed(2);
        document.getElementById('summaryStatutory').textContent = '–₱' + totalStatutory.toFixed(2);
        
        // Other deductions
        const tardiness = parseFloat(document.getElementById('tardiness').value) || 0;
        const sssLoan = parseFloat(document.getElementById('sssLoan').value) || 0;
        const pagibigLoan = parseFloat(document.getElementById('pagibigLoan').value) || 0;
        const totalOther = tardiness + sssLoan + pagibigLoan;
        document.getElementById('summaryOther').textContent = '–₱' + totalOther.toFixed(2);
        
        const totalDeductions = totalStatutory + totalOther;
        document.getElementById('totalDeductions').textContent = '–₱' + totalDeductions.toFixed(2);
        document.getElementById('totalDeductionsInput').value = totalDeductions.toFixed(2);
        document.getElementById('taxInput').value = totalStatutory.toFixed(2);
        document.getElementById('footerDed').textContent = '–₱' + totalDeductions.toFixed(2);
        
        const netPay = Math.max(0, grossPay - totalDeductions);
        document.getElementById('netPay').textContent = '₱' + netPay.toFixed(2);
        document.getElementById('netPayInput').value = netPay.toFixed(2);
        document.getElementById('footerNet').textContent = '₱' + netPay.toFixed(2);
    }

    calculateAll();
});
</script>
@endsection