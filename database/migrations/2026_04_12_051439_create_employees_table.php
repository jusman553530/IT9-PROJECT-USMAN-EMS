<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('employees', function (Blueprint $table) {
        $table->id();
        $table->string('employee_id')->unique();
        $table->string('first_name');
        $table->string('last_name');
        $table->string('email')->unique();
        $table->string('phone');
        $table->date('date_of_birth');
        $table->enum('gender', ['male', 'female', 'other']);
        $table->text('address');
        $table->foreignId('department_id')->constrained();
        $table->foreignId('position_id')->constrained();
        $table->date('hire_date');
        $table->decimal('salary', 10, 2);
        $table->enum('status', ['active', 'inactive', 'on_leave'])->default('active');
        $table->timestamps();
        $table->softDeletes();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
