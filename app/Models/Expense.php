<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'expense_code',
        'category',
        'description',
        'department_id',
        'amount',
        'expense_date',
        'status',
        'submitted_by',
        'notes'
    ];

    protected $casts = [
        'expense_date' => 'date',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'submitted_by');
    }

    public static function generateExpenseCode()
    {
        $prefix = 'EXP';
        $count = self::count() + 1;
        return $prefix . str_pad($count, 3, '0', STR_PAD_LEFT);
    }
}