<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProblemReport;
use Illuminate\Http\Request;

class ProblemReportController extends Controller
{
    public function index(Request $request)
    {
        $query = ProblemReport::with('employee.department')
            ->orderBy('created_at', 'desc');
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        
        $reports = $query->paginate(15);
        
        $stats = [
            'total' => ProblemReport::count(),
            'open' => ProblemReport::where('status', 'open')->count(),
            'in_progress' => ProblemReport::where('status', 'in_progress')->count(),
            'resolved' => ProblemReport::where('status', 'resolved')->count(),
        ];
        
        return view('admin.reports.index', compact('reports', 'stats'));
    }

    public function show(ProblemReport $report)
    {
        $report->load('employee.department');
        return view('admin.reports.show', compact('report'));
    }

    public function respond(Request $request, ProblemReport $report)
    {
        $validated = $request->validate([
            'admin_response' => 'required|string',
        ]);
        
        $report->update([
            'admin_response' => $validated['admin_response'],
            'status' => 'in_progress',
        ]);
        
        return back()->with('success', 'Response sent successfully.');
    }

    public function updateStatus(Request $request, ProblemReport $report)
    {
        $validated = $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed',
        ]);
        
        $report->update([
            'status' => $validated['status'],
            'resolved_at' => $validated['status'] === 'resolved' ? now() : null,
        ]);
        
        return back()->with('success', 'Status updated successfully.');
    }
}