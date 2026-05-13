<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('payrolls', function (Blueprint $table) {
            // Add new PH payroll fields
            $table->integer('days_worked')->default(0)->after('base_salary');
            $table->decimal('daily_rate', 10, 2)->default(0)->after('days_worked');
            $table->decimal('hourly_rate', 10, 2)->default(0)->after('daily_rate');
            
            // Overtime
            $table->decimal('regular_ot_hours', 5, 2)->default(0)->after('overtime_hours');
            $table->decimal('regular_ot_amount', 10, 2)->default(0)->after('overtime_pay');
            $table->decimal('rest_day_ot_hours', 5, 2)->default(0)->after('regular_ot_amount');
            $table->decimal('rest_day_ot_amount', 10, 2)->default(0)->after('rest_day_ot_hours');
            $table->decimal('holiday_ot_hours', 5, 2)->default(0)->after('rest_day_ot_amount');
            $table->decimal('holiday_ot_amount', 10, 2)->default(0)->after('holiday_ot_hours');
            $table->decimal('nsd_hours', 5, 2)->default(0)->after('holiday_ot_amount');
            $table->decimal('nsd_amount', 10, 2)->default(0)->after('nsd_hours');
            
            // Allowances
            $table->decimal('rice_subsidy', 10, 2)->default(0)->after('bonus');
            $table->decimal('clothing_allowance', 10, 2)->default(0)->after('rice_subsidy');
            $table->decimal('transport_allowance', 10, 2)->default(0)->after('clothing_allowance');
            
            // Government deductions
            $table->decimal('sss_contribution', 10, 2)->default(0)->after('tax');
            $table->decimal('sss_wisp', 10, 2)->default(0)->after('sss_contribution');
            $table->decimal('philhealth_contribution', 10, 2)->default(0)->after('sss_wisp');
            $table->decimal('pagibig_contribution', 10, 2)->default(0)->after('philhealth_contribution');
            $table->decimal('withholding_tax', 10, 2)->default(0)->after('pagibig_contribution');
            
            // Other deductions
            $table->decimal('tardiness_deduction', 10, 2)->default(0)->after('deductions');
            $table->decimal('sss_loan', 10, 2)->default(0)->after('tardiness_deduction');
            $table->decimal('pagibig_loan', 10, 2)->default(0)->after('sss_loan');
            
            // Gross pay
            $table->decimal('gross_pay', 10, 2)->default(0)->after('notes');
        });
    }

    public function down()
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropColumn([
                'days_worked', 'daily_rate', 'hourly_rate',
                'regular_ot_hours', 'regular_ot_amount',
                'rest_day_ot_hours', 'rest_day_ot_amount',
                'holiday_ot_hours', 'holiday_ot_amount',
                'nsd_hours', 'nsd_amount',
                'rice_subsidy', 'clothing_allowance', 'transport_allowance',
                'sss_contribution', 'sss_wisp', 'philhealth_contribution', 'pagibig_contribution', 'withholding_tax',
                'tardiness_deduction', 'sss_loan', 'pagibig_loan',
                'gross_pay',
            ]);
        });
    }
};