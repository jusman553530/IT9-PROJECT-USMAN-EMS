<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'payroll_code',
        'period_start',
        'period_end',
        'base_salary',
        'overtime_hours',
        'overtime_pay',
        'bonus',
        'deductions',
        'tax',
        'net_salary',
        'status',
        'payment_date',
        'payment_method',
        'notes'
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'payment_date' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    // Generate unique payroll code
    public static function generatePayrollCode()
    {
        $prefix = 'PAY';
        $year = date('Y');
        $month = date('m');
        $count = self::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->count() + 1;
        
        return $prefix . $year . $month . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    // Calculate net salary
    public function calculateNetSalary()
    {
        $totalEarnings = $this->base_salary + $this->overtime_pay + $this->bonus;
        $totalDeductions = $this->deductions + $this->tax;
        $this->net_salary = $totalEarnings - $totalDeductions;
        return $this->net_salary;
    }
}