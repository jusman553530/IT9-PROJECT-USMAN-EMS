@extends('layouts.app')

@section('title', 'My Payslips')

@section('content')
<div>
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">My Payslips</h1>
        <p class="text-gray-600">Your salary history and payment records</p>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
        @php
            $latestPayslip = $payslips->first();
            $ytdEarnings = $payslips->whereBetween('period_start', [now()->startOfYear(), now()])->sum('net_salary');
            $avgMonthly = $payslips->count() > 0 ? round($payslips->avg('net_salary')) : 0;
        @endphp

        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <span class="text-sm text-gray-600">Latest Net Pay</span>
            </div>
            <p class="text-3xl font-semibold text-gray-900">${{ number_format($latestPayslip->net_salary ?? 0) }}</p>
            <p class="text-sm text-green-600 mt-1">{{ $latestPayslip ? $latestPayslip->period_start->format('F Y') : 'N/A' }}</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
                <span class="text-sm text-gray-600">YTD Earnings</span>
            </div>
            <p class="text-3xl font-semibold text-gray-900">${{ number_format($ytdEarnings) }}</p>
            <p class="text-sm text-gray-600 mt-1">Jan – {{ now()->format('M Y') }}</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-lg bg-teal-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <span class="text-sm text-gray-600">Avg Monthly Pay</span>
            </div>
            <p class="text-3xl font-semibold text-gray-900">${{ number_format($avgMonthly) }}</p>
            <p class="text-sm text-gray-600 mt-1">{{ now()->year }} average</p>
        </div>
    </div>

    <!-- Payslips List -->
    <div class="space-y-4">
        @forelse($payslips as $slip)
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden" x-data="{ open: false }">
                <!-- Slip Header -->
                <button @click="open = !open" class="w-full flex items-center justify-between p-5 hover:bg-gray-50 transition-colors text-left">
                    <div class="flex items-center gap-4">
                        <div class="w-11 h-11 rounded-lg bg-green-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">{{ $slip->period_start->format('F Y') }}</p>
                            <p class="text-sm text-gray-600">{{ $slip->period_start->format('M d') }} – {{ $slip->period_end->format('M d, Y') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="text-right">
                            <p class="font-semibold text-gray-900">${{ number_format($slip->net_salary) }}</p>
                            <span class="text-xs px-2 py-0.5 rounded-full {{ $slip->status === 'paid' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                {{ ucfirst($slip->status) }}
                            </span>
                        </div>
                        <svg x-show="!open" class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                        <svg x-show="open" class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                        </svg>
                    </div>
                </button>

                <!-- Expanded Breakdown -->
                <div x-show="open" class="border-t border-gray-200 p-5 bg-gray-50">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Earnings -->
                        <div>
                            <h4 class="text-sm font-semibold text-gray-600 mb-3">EARNINGS</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-900">Base Salary</span>
                                    <span class="text-sm font-medium text-gray-900">${{ number_format($slip->base_salary) }}</span>
                                </div>
                                @if($slip->bonus > 0)
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-900">Performance Bonus</span>
                                    <span class="text-sm font-medium text-green-600">+${{ number_format($slip->bonus) }}</span>
                                </div>
                                @endif
                                @if($slip->overtime_pay > 0)
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-900">Overtime ({{ $slip->overtime_hours }} hrs)</span>
                                    <span class="text-sm font-medium text-green-600">+${{ number_format($slip->overtime_pay) }}</span>
                                </div>
                                @endif
                                <div class="flex justify-between pt-2 border-t border-gray-200">
                                    <span class="text-sm font-semibold text-gray-900">Gross Earnings</span>
                                    <span class="text-sm font-semibold text-gray-900">${{ number_format($slip->base_salary + $slip->bonus + $slip->overtime_pay) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Deductions -->
                        <div>
                            <h4 class="text-sm font-semibold text-gray-600 mb-3">DEDUCTIONS</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-900">Income Tax</span>
                                    <span class="text-sm font-medium text-red-500">-${{ number_format($slip->tax) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-900">Other Deductions</span>
                                    <span class="text-sm font-medium text-red-500">-${{ number_format($slip->deductions) }}</span>
                                </div>
                                <div class="flex justify-between pt-2 border-t border-gray-200">
                                    <span class="text-sm font-semibold text-gray-900">Total Deductions</span>
                                    <span class="text-sm font-semibold text-red-500">-${{ number_format($slip->tax + $slip->deductions) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Net Pay Row -->
                    <div class="mt-4 p-4 rounded-lg flex items-center justify-between" style="background-color: #0C521C; color: white;">
                        <span class="font-semibold text-white/90">Net Pay</span>
                        <span class="text-2xl font-bold text-white">${{ number_format($slip->net_salary) }}</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl border border-gray-200 p-16 text-center">
                <svg class="w-20 h-20 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No payslips yet</h3>
                <p class="text-gray-500">Your salary records will appear here once processed.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection