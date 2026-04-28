<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'date_of_birth',
        'gender',
        'address',
        'department_id',
        'position_id',
        'hire_date',
        'salary',
        'status'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'hire_date' => 'date',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
    // Add this method to your Employee model
public function attendances()
{
    return $this->hasMany(Attendance::class);
}

// Get today's attendance for this employee
public function todayAttendance()
{
    return $this->attendances()->today()->first();
}

// Check if employee is present today
public function isPresentToday()
{
    $attendance = $this->todayAttendance();
    return $attendance && $attendance->status === 'present';
}
public function payrolls()
{
    return $this->hasMany(Payroll::class);
}

public function latestPayroll()
{
    return $this->hasOne(Payroll::class)->latest();
}
public function assignedTasks()
{
    return $this->hasMany(Task::class, 'assigned_to');
}
public function user()
{
    return $this->hasOne(User::class, 'email', 'email');
}
public function problemReports()
{
    return $this->hasMany(ProblemReport::class);
}
public function expenses()
{
    return $this->hasMany(Expense::class, 'submitted_by');
}
}