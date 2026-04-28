<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Employee;  // ← Add this line
use App\Models\Attendance; // ← Add this line
use Carbon\Carbon;        // ← Add this line

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials, $request->filled('remember'))) {
        $request->session()->regenerate();
        
        // Auto clock-in for employees
        $user = Auth::user();
        $employee = Employee::where('email', $user->email)->first();
        
        if ($employee) {
            Attendance::firstOrCreate(
                [
                    'employee_id' => $employee->id,
                    'date' => today(),
                ],
                [
                    'time_in' => now()->format('H:i:s'),
                    'status' => now()->hour >= 9 ? 'late' : 'present'
                ]
            );
        }
        
        return redirect()->intended(route('dashboard'));
    }

    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ]);
}
    public function logout(Request $request)
{
    $user = Auth::user();
    
    // Auto clock-out
    $employee = Employee::where('email', $user->email)->first();
    
    if ($employee) {
        $attendance = Attendance::where('employee_id', $employee->id)
            ->whereDate('date', today())
            ->first();
            
        if ($attendance && !$attendance->time_out) {
            $attendance->update([
                'time_out' => now()->format('H:i:s')
            ]);
        }
    }
    
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    
    return redirect('/login');
}
}