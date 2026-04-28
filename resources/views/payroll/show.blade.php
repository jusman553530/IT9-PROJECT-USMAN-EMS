@extends('layouts.app')

@section('title', 'Payroll Details')

@section('content')
<div>
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Payroll Details</h1>
            <p class="text-gray-600">{{ $payroll->payroll_code }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('payroll.edit', $payroll) }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Edit
            </a>
            <a href="{{ route('payroll.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Back to List
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Details (Left - 2 columns) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Employee Info -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Employee Information</h2>
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-14 h-14 rounded-full bg-green-100 text-green-700 flex items-center justify-center font-semibold text-xl">
                        {{ substr($payroll->employee->first_name ?? 'U', 0, 1) }}{{ substr($payroll->employee->last_name ?? '', 0, 1) }}
                    </div>
                    <div>
                        <p class="text-lg font-semibold text-gray-900">{{ $payroll->employee->full_name ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-600">{{ $payroll->employee->employee_id ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-600">Department</p>
                        <p class="font-medium">{{ $payroll->employee->department->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Position</p>
                        <p class="font-medium">{{ $payroll->employee->position->title ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Email</p>
                        <p class="font-medium">{{ $payroll->employee->email ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Hire Date</p>
                        <p class="font-medium">{{ $payroll->employee->hire_date ? $payroll->employee->hire_date->format('M d, Y') : 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Period Information -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Pay Period</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Period Start</p>
                        <p class="text-lg font-semibold">{{ $payroll->period_start->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Period End</p>
                        <p class="text-lg font-semibold">{{ $payroll->period_end->format('M d, Y') }}</p>
                    </div>
                    @if($payroll->payment_date)
                    <div>
                        <p class="text-sm text-gray-600">Payment Date</p>
                        <p class="text-lg font-semibold">{{ $payroll->payment_date->format('M d, Y') }}</p>
                    </div>
                    @endif
                    @if($payroll->payment_method)
                    <div>
                        <p class="text-sm text-gray-600">Payment Method</p>
                        <p class="text-lg font-semibold">{{ ucfirst(str_replace('_', ' ', $payroll->payment_method)) }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Earnings Breakdown -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Earnings & Deductions</h2>
                <div class="space-y-3">
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-600">Base Salary</span>
                        <span class="font-medium text-gray-900">${{ number_format($payroll->base_salary, 2) }}</span>
                    </div>
                    @if($payroll->overtime_hours > 0)
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-600">Overtime ({{ $payroll->overtime_hours }} hrs)</span>
                        <span class="font-medium text-gray-900">${{ number_format($payroll->overtime_pay, 2) }}</span>
                    </div>
                    @endif
                    @if($payroll->bonus > 0)
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-600">Bonus</span>
                        <span class="font-medium text-green-600">+${{ number_format($payroll->bonus, 2) }}</span>
                    </div>
                    @endif
                    @if($payroll->deductions > 0)
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-600">Deductions</span>
                        <span class="font-medium text-red-600">-${{ number_format($payroll->deductions, 2) }}</span>
                    </div>
                    @endif
                    @if($payroll->tax > 0)
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-600">Tax</span>
                        <span class="font-medium text-red-600">-${{ number_format($payroll->tax, 2) }}</span>
                    </div>
                    @endif
                </div>
            </div>

            @if($payroll->notes)
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Notes</h2>
                <p class="text-gray-600">{{ $payroll->notes }}</p>
            </div>
            @endif
        </div>

        <!-- Summary Card (Right - 1 column) -->
<div class="space-y-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Payroll Summary</h3>
            
            <!-- Net Salary -->
            <div class="text-center py-6 border-b border-gray-200">
                <p class="text-sm text-gray-500 mb-1">NET SALARY</p>
                <p class="text-4xl font-bold text-green-700">${{ number_format($payroll->net_salary, 2) }}</p>
            </div>
            
            <!-- Earnings Total -->
            <div class="py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Total Earnings</span>
                    <span class="font-semibold text-gray-900">${{ number_format($payroll->base_salary + $payroll->overtime_pay + $payroll->bonus, 2) }}</span>
                </div>
            </div>
            
            <!-- Deductions Total -->
            <div class="py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Total Deductions</span>
                    <span class="font-semibold text-red-600">-${{ number_format($payroll->deductions + $payroll->tax, 2) }}</span>
                </div>
            </div>
            
            <!-- Status -->
            <div class="py-4">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Status</span>
                    <span class="px-3 py-1 rounded-full text-sm font-semibold
                        {{ $payroll->status === 'paid' ? 'bg-green-100 text-green-700' : 
                           ($payroll->status === 'approved' ? 'bg-blue-100 text-blue-700' : 
                           ($payroll->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700')) }}">
                        {{ ucfirst($payroll->status) }}
                    </span>
                </div>
            </div>
        </div>
        
        <!-- Action Buttons -->
        @if(auth()->user()->isAdmin() && $payroll->status === 'pending')
        <div class="p-4 bg-gray-50 border-t border-gray-200 flex gap-3">
            <form action="{{ route('payroll.update', $payroll) }}" method="POST" class="flex-1">
                @csrf
                @method('PUT')
                <input type="hidden" name="employee_id" value="{{ $payroll->employee_id }}">
                <input type="hidden" name="period_start" value="{{ $payroll->period_start->format('Y-m-d') }}">
                <input type="hidden" name="period_end" value="{{ $payroll->period_end->format('Y-m-d') }}">
                <input type="hidden" name="base_salary" value="{{ $payroll->base_salary }}">
                <input type="hidden" name="overtime_pay" value="{{ $payroll->overtime_pay }}">
                <input type="hidden" name="bonus" value="{{ $payroll->bonus }}">
                <input type="hidden" name="deductions" value="{{ $payroll->deductions }}">
                <input type="hidden" name="tax" value="{{ $payroll->tax }}">
                <input type="hidden" name="status" value="approved">
                <button type="submit" class="w-full px-4 py-2 text-white rounded-lg font-medium" style="background-color: #0C521C;">
                    Approve
                </button>
            </form>
            <form action="{{ route('payroll.update', $payroll) }}" method="POST" class="flex-1">
                @csrf
                @method('PUT')
                <input type="hidden" name="employee_id" value="{{ $payroll->employee_id }}">
                <input type="hidden" name="period_start" value="{{ $payroll->period_start->format('Y-m-d') }}">
                <input type="hidden" name="period_end" value="{{ $payroll->period_end->format('Y-m-d') }}">
                <input type="hidden" name="base_salary" value="{{ $payroll->base_salary }}">
                <input type="hidden" name="overtime_pay" value="{{ $payroll->overtime_pay }}">
                <input type="hidden" name="bonus" value="{{ $payroll->bonus }}">
                <input type="hidden" name="deductions" value="{{ $payroll->deductions }}">
                <input type="hidden" name="tax" value="{{ $payroll->tax }}">
                <input type="hidden" name="status" value="draft">
                <button type="submit" class="w-full px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 font-medium">
                    Reject
                </button>
            </form>
        </div>
        @endif
    
            </div>
        </div>
    </div>
</div>
@endsection