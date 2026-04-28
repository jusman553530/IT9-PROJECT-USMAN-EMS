<?php

namespace App\Http\Controllers;

use App\Models\ProblemReport;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProblemReportController extends Controller
{
    public function index()
    {
        return $this->create();
    }

    public function create()
    {
        return view('reports.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $employee = Employee::where('email', $user->email)->first();
        
        if (!$employee) {
            return redirect()->route('dashboard')->with('error', 'Employee record not found.');
        }
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:technical,hr,facilities,other',
            'priority' => 'required|in:low,medium,high,urgent',
        ]);

        $validated['employee_id'] = $employee->id;
        $validated['status'] = 'open';

        ProblemReport::create($validated);

        return redirect()->route('reports.index')
            ->with('success', 'Problem report submitted successfully.');
    }

    public function show(ProblemReport $report)
    {
        $report->load('employee');
        return view('reports.show', compact('report'));
    }
}