<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Employee;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = Expense::with(['employee', 'department'])
            ->orderBy('expense_date', 'desc');
        
        if ($request->filled('status') && $request->status !== 'All') {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        
        $expenses = $query->paginate(15);
        $departments = Department::orderBy('name')->get();
        
        $totalApproved = Expense::where('status', 'approved')->sum('amount');
        $totalPending = Expense::where('status', 'pending')->sum('amount');
        $totalRejected = Expense::where('status', 'rejected')->whereMonth('expense_date', date('m'))->sum('amount');
        
        $budgetCategories = [
            ['name' => 'Software & Tools', 'budget' => 8000, 'spent' => Expense::where('category', 'Software')->sum('amount')],
            ['name' => 'Travel & Transport', 'budget' => 5000, 'spent' => Expense::where('category', 'Travel')->sum('amount')],
            ['name' => 'Training & Development', 'budget' => 3000, 'spent' => Expense::where('category', 'Training')->sum('amount')],
            ['name' => 'Equipment & Supplies', 'budget' => 4000, 'spent' => Expense::where('category', 'Equipment')->sum('amount')],
            ['name' => 'Marketing & Ads', 'budget' => 6000, 'spent' => Expense::where('category', 'Marketing')->sum('amount')],
        ];
        
        return view('expenses.index', compact(
            'expenses', 'departments', 'totalApproved', 'totalPending', 'totalRejected', 'budgetCategories'
        ));
    }

    public function create()
    {
        $departments = Department::orderBy('name')->get();
        return view('expenses.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $employee = Employee::where('email', $user->email)->first();
        
        $validated = $request->validate([
            'category' => 'required|string',
            'description' => 'required|string',
            'department_id' => 'required|exists:departments,id',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $validated['expense_code'] = Expense::generateExpenseCode();
        $validated['submitted_by'] = $employee->id;
        $validated['status'] = 'pending';

        Expense::create($validated);

        return redirect()->route('expenses.index')
            ->with('success', 'Expense submitted successfully.');
    }

    public function approve(Expense $expense)
    {
        $expense->update(['status' => 'approved']);
        return back()->with('success', 'Expense approved.');
    }

    public function reject(Expense $expense)
    {
        $expense->update(['status' => 'rejected']);
        return back()->with('success', 'Expense rejected.');
    }
}