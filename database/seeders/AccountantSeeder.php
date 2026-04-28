<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\User;
use App\Models\Department;
use App\Models\Position;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AccountantSeeder extends Seeder
{
    public function run()
    {
        // Find Finance Department
        $financeDepartment = Department::where('name', 'like', '%finance%')->first();
        
        if (!$financeDepartment) {
            $this->command->error('Finance department not found. Please create it first.');
            return;
        }
        
        // Find or create positions in Finance department
        $accountantPosition = Position::firstOrCreate(
            ['title' => 'Accountant', 'department_id' => $financeDepartment->id],
            ['min_salary' => 40000, 'max_salary' => 60000]
        );
        
        $headFinancePosition = Position::firstOrCreate(
            ['title' => 'Head of Finance', 'department_id' => $financeDepartment->id],
            ['min_salary' => 70000, 'max_salary' => 90000]
        );
        
        // ==========================================
        // 3 ACCOUNTANTS
        // ==========================================
        
        $accountants = [
            [
                'first_name' => 'Maria',
                'last_name' => 'Santos',
                'email' => 'maria.santos@ems.com',
                'phone' => '09171111111',
                'date_of_birth' => '1990-03-15',
                'gender' => 'female',
                'address' => '123 Finance St, Makati City',
                'salary' => 45000,
            ],
            [
                'first_name' => 'Juan',
                'last_name' => 'Dela Cruz',
                'email' => 'juan.delacruz@ems.com',
                'phone' => '09172222222',
                'date_of_birth' => '1988-07-22',
                'gender' => 'male',
                'address' => '456 Accounting Ave, Quezon City',
                'salary' => 48000,
            ],
            [
                'first_name' => 'Angela',
                'last_name' => 'Reyes',
                'email' => 'angela.reyes@ems.com',
                'phone' => '09173333333',
                'date_of_birth' => '1992-11-08',
                'gender' => 'female',
                'address' => '789 Budget Blvd, Pasig City',
                'salary' => 42000,
            ],
        ];
        
        foreach ($accountants as $index => $data) {
            // Create Employee
            $employee = Employee::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'date_of_birth' => $data['date_of_birth'],
                'gender' => $data['gender'],
                'address' => $data['address'],
                'department_id' => $financeDepartment->id,
                'position_id' => $accountantPosition->id,
                'hire_date' => '2024-01-15',
                'salary' => $data['salary'],
                'employee_id' => 'EMP' . str_pad(Employee::count() + 1, 5, '0', STR_PAD_LEFT),
                'status' => 'active',
            ]);
            
            // Create User Account (Accountant role)
            User::create([
                'name' => $data['first_name'] . ' ' . $data['last_name'],
                'email' => $data['email'],
                'password' => Hash::make('password123'),
                'role' => 'accountant',
                'department_id' => $financeDepartment->id,
            ]);
            
            $this->command->info('Created Accountant: ' . $data['email']);
        }
        
        // ==========================================
        // 1 HEAD OF FINANCE (Head Department Role)
        // ==========================================
        
        $headData = [
            'first_name' => 'Roberto',
            'last_name' => 'Gonzales',
            'email' => 'roberto.gonzales@ems.com',
            'phone' => '09174444444',
            'date_of_birth' => '1982-05-10',
            'gender' => 'male',
            'address' => '100 Finance Tower, BGC Taguig',
            'salary' => 85000,
        ];
        
        $headEmployee = Employee::create([
            'first_name' => $headData['first_name'],
            'last_name' => $headData['last_name'],
            'email' => $headData['email'],
            'phone' => $headData['phone'],
            'date_of_birth' => $headData['date_of_birth'],
            'gender' => $headData['gender'],
            'address' => $headData['address'],
            'department_id' => $financeDepartment->id,
            'position_id' => $headFinancePosition->id,
            'hire_date' => '2023-06-01',
            'salary' => $headData['salary'],
            'employee_id' => 'EMP' . str_pad(Employee::count() + 1, 5, '0', STR_PAD_LEFT),
            'status' => 'active',
        ]);
        
        User::create([
            'name' => $headData['first_name'] . ' ' . $headData['last_name'],
            'email' => $headData['email'],
            'password' => Hash::make('password123'),
            'role' => 'head_department', // This is the head department role
            'department_id' => $financeDepartment->id,
        ]);
        
        $this->command->info('Created Head of Finance: ' . $headData['email']);
        
        $this->command->info('==========================================');
        $this->command->info('All passwords: password123');
        $this->command->info('==========================================');
    }
}