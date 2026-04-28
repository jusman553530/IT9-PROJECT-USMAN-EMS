<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            return app(\App\Http\Controllers\Dashboard\AdminDashboardController::class)->index();
        } elseif ($user->isAccountant()) {
            return app(\App\Http\Controllers\Dashboard\AccountantDashboardController::class)->index();
        } else {
            return app(\App\Http\Controllers\Dashboard\EmployeeDashboardController::class)->index();
        }
    }
}