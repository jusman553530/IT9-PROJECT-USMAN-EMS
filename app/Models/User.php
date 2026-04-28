<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'department_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class, 'email', 'email');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function assignedTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'assigned_by');
    }

    // Role check methods
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isAccountant(): bool
    {
        return $this->role === 'accountant';
    }

    public function isEmployee(): bool
    {
        return $this->role === 'employee';
    }

    // Check if user can manage a specific department
    public function canManageDepartment($departmentId): bool
    {
        if ($this->isAdmin()) return true;
        if ($this->isHeadDepartment() && $this->department_id == $departmentId) return true;
        if ($this->isTeamLeader() && $this->department_id == $departmentId) return true;
        return false;
    }

    // Check if user can view all employees
    public function canViewAllEmployees(): bool
    {
        return in_array($this->role, ['admin', 'head_department', 'accountant']);
    }

    // Check if user can manage payroll
    public function canManagePayroll(): bool
    {
        return in_array($this->role, ['admin', 'accountant']);
    }

    // Check if user can create/manage tasks
    public function canManageTasks(): bool
    {
        return in_array($this->role, ['admin', 'head_department', 'team_leader']);
    }

    // Get employees this user can see
    public function getAccessibleEmployees()
    {
        if ($this->isAdmin() || $this->isAccountant()) {
            return Employee::query();
        }
        
        if ($this->isHeadDepartment() || $this->isTeamLeader()) {
            return Employee::where('department_id', $this->department_id);
        }
        
        // Regular employee - only themselves
        return Employee::where('email', $this->email);
    }
}