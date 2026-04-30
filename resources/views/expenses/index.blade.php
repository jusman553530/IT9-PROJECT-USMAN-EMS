@extends('layouts.app')

@section('title', 'Expense Management')

@section('content')
<div>
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Expense Management</h1>
            <p class="text-gray-600">Review, approve, and track company expenses</p>
        </div>
        <a href="{{ route('expenses.create') }}" class="px-6 py-3 text-white rounded-lg hover:opacity-90 flex items-center gap-2 w-fit" style="background-color: #0C521C;">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Record Expense
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">{{ session('success') }}</div>
    @endif

        <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
                <span class="text-sm text-gray-600">Total Approved</span>
            </div>
            <p class="text-3xl font-semibold text-gray-900 text-right">${{ number_format($totalApproved) }}</p>
            <p class="text-sm text-green-600 mt-1 text-right">{{ App\Models\Expense::where('status', 'approved')->count() }} expenses</p>
        </div>
        <div class="bg-white rounded-xl border border-yellow-200 p-6 shadow-sm">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-lg bg-yellow-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"></path>
                    </svg>
                </div>
                <span class="text-sm text-gray-600">Pending Review</span>
            </div>
            <p class="text-3xl font-semibold text-yellow-700 text-right">${{ number_format($totalPending) }}</p>
            <p class="text-sm text-yellow-600 mt-1 text-right">{{ App\Models\Expense::where('status', 'pending')->count() }} awaiting approval</p>
        </div>
        <div class="bg-white rounded-xl border border-red-200 p-6 shadow-sm">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                    </svg>
                </div>
                <span class="text-sm text-gray-600">Rejected</span>
            </div>
            <p class="text-3xl font-semibold text-red-700 text-right">${{ number_format($totalRejected) }}</p>
            <p class="text-sm text-red-500 mt-1 text-right">This month</p>
        </div>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Expenses List -->
        <div class="lg:col-span-2">
            <!-- Filter Tabs -->
            <div class="flex items-center gap-2 mb-4 overflow-x-auto pb-1">
                <svg class="w-4 h-4 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
                @foreach(['All', 'Approved', 'Pending', 'Rejected'] as $filter)
                    <a href="{{ route('expenses.index', ['status' => $filter === 'All' ? null : strtolower($filter)]) }}" 
                       class="px-4 py-2 rounded-lg whitespace-nowrap text-sm {{ request('status') === strtolower($filter) || (!request('status') && $filter === 'All') ? 'text-white' : 'text-gray-600 bg-white border border-gray-200 hover:bg-gray-50' }}"
                       style="{{ request('status') === strtolower($filter) || (!request('status') && $filter === 'All') ? 'background-color: #0C521C;' : '' }}">
                        {{ $filter }}
                    </a>
                @endforeach
            </div>

            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                @forelse($expenses as $index => $expense)
                    <div class="p-4 border-b border-gray-200 last:border-b-0 hover:bg-gray-50 transition-colors {{ $index % 2 === 0 ? '' : 'bg-gray-50/50' }}">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-start gap-3 flex-1 min-w-0">
                                <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"></path>
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap mb-1">
                                        <p class="text-sm font-medium text-gray-900">{{ $expense->description }}</p>
                                        <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-700">{{ $expense->category }}</span>
                                    </div>
                                    <p class="text-xs text-gray-600">{{ $expense->department->name ?? 'N/A' }} · {{ $expense->employee->full_name ?? 'N/A' }} · {{ $expense->expense_date->format('M d, Y') }}</p>
                                </div>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <p class="text-sm font-semibold text-gray-900 mb-1">${{ number_format($expense->amount) }}</p>
                                <span class="text-xs px-2 py-0.5 rounded-full capitalize
                                    {{ $expense->status === 'approved' ? 'bg-green-100 text-green-700' : 
                                       ($expense->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                    {{ $expense->status }}
                                </span>
                            </div>
                        </div>
                        @if($expense->status === 'pending')
                            <div class="flex gap-2 mt-3 ml-13">
                                <form action="{{ route('expenses.approve', $expense) }}" method="POST">
                                    @csrf
                                    <button class="px-3 py-1.5 bg-green-100 text-green-700 rounded-lg text-xs hover:bg-green-200">Approve</button>
                                </form>
                                <form action="{{ route('expenses.reject', $expense) }}" method="POST">
                                    @csrf
                                    <button class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg text-xs hover:bg-red-200">Reject</button>
                                </form>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="p-16 text-center text-gray-500">No expenses found.</div>
                @endforelse
            </div>
            
            @if($expenses->hasPages())
                <div class="mt-6">{{ $expenses->links() }}</div>
            @endif
        </div>

        <!-- Budget by Category -->
        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm h-fit">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Budget by Category</h2>
            <div class="space-y-5">
                @foreach($budgetCategories as $cat)
                    @php $pct = $cat['budget'] > 0 ? round(($cat['spent'] / $cat['budget']) * 100) : 0; @endphp
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-sm text-gray-900">{{ $cat['name'] }}</span>
                            <span class="text-xs text-gray-600">{{ $pct }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2 mb-1">
                            <div class="h-2 rounded-full bg-green-600" style="width: {{ $pct }}%"></div>
                        </div>
                        <div class="flex justify-between text-xs text-gray-500">
                            <span>${{ number_format($cat['spent']) }} spent</span>
                            <span>${{ number_format($cat['budget']) }} budget</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection