<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckDepartmentAccess
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        $departmentId = $request->route('department') ?? $request->department_id;
        
        if ($user->isAdmin() || $user->isAccountant()) {
            return $next($request);
        }
        
        if ($user->department_id == $departmentId) {
            return $next($request);
        }
        
        abort(403, 'You can only access your own department.');
    }
}