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

    public function canViewAllEmployees(): bool
    {
        return in_array($this->role, ['admin', 'accountant']);
    }

    public function canManagePayroll(): bool
    {
        return in_array($this->role, ['admin', 'accountant']);
    }

    public function canManageTasks(): bool
    {
        return $this->role === 'admin';
    }
}