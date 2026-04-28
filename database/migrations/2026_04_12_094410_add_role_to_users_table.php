<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', [
                'admin',           // Full system access
                'head_department', // Department head
                'accountant',      // Finance/payroll only
                'team_leader',     // Team leader
                'employee'         // Regular employee
            ])->default('employee')->after('email');
            
            // For team leaders and head departments, link to their department
            $table->foreignId('department_id')->nullable()->after('role')->constrained();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropColumn(['role', 'department_id']);
        });
    }
};