<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_code',
        'title',
        'description',
        'department_id',
        'assigned_to',
        'assigned_by',
        'priority',
        'status',
        'due_date',
        'completed_at',
        'notes'
    ];

    protected $casts = [
        'due_date' => 'date',
        'completed_at' => 'datetime',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function assignedEmployee()
    {
        return $this->belongsTo(Employee::class, 'assigned_to');
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    // Generate unique task code
    public static function generateTaskCode()
    {
        $prefix = 'TSK';
        $year = date('Y');
        $count = self::whereYear('created_at', $year)->count() + 1;
        return $prefix . $year . str_pad($count, 5, '0', STR_PAD_LEFT);
    }

    // Scope for pending tasks
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // Scope for tasks by department
    public function scopeByDepartment($query, $departmentId)
    {
        return $query->where('department_id', $departmentId);
    }

    // Check if overdue
    public function isOverdue()
    {
        return $this->due_date && $this->due_date->isPast() && $this->status !== 'completed';
    }
}