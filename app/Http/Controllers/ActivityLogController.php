<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')
            ->orderBy('created_at', 'desc');
        
        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }
        
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }
        
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        $logs = $query->paginate(20);
        $users = \App\Models\User::orderBy('name')->get();
        $modules = ['employees', 'departments', 'positions', 'payroll', 'tasks', 'leaves', 'attendance', 'expenses', 'reports'];
        $actions = ['created', 'updated', 'deleted', 'approved', 'rejected', 'logged_in', 'logged_out', 'clock_in', 'clock_out'];
        
        return view('admin.activity-logs', compact('logs', 'users', 'modules', 'actions'));
    }
}