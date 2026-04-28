<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('positions', function (Blueprint $table) {
            if (!Schema::hasColumn('positions', 'department_id')) {
                $table->foreignId('department_id')->constrained()->onDelete('cascade');
            }
            if (!Schema::hasColumn('positions', 'min_salary')) {
                $table->decimal('min_salary', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('positions', 'max_salary')) {
                $table->decimal('max_salary', 10, 2)->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('positions', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropColumn(['department_id', 'min_salary', 'max_salary']);
        });
    }
};