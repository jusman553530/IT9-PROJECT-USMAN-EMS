<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\User;
use App\Models\Department;
use App\Models\Position;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    public function run()
    {
        $departments = [
            'Finance' => [
                'code' => 'FIN',
                'employees' => [
                    ['first' => 'Maria', 'last' => 'Santos', 'email' => 'maria.santos@ems.com', 'role' => 'accountant', 'position' => 'Accountant'],
                    ['first' => 'Juan', 'last' => 'Dela Cruz', 'email' => 'juan.delacruz@ems.com', 'role' => 'accountant', 'position' => 'Accountant'],
                    ['first' => 'Angela', 'last' => 'Reyes', 'email' => 'angela.reyes@ems.com', 'role' => 'accountant', 'position' => 'Accountant'],
                    ['first' => 'Roberto', 'last' => 'Gonzales', 'email' => 'roberto.gonzales@ems.com', 'role' => 'head_department', 'position' => 'Head of Finance'],
                ]
            ],
            'Marketing' => [
                'code' => 'MKT',
                'employees' => [
                    ['first' => 'Sofia', 'last' => 'Garcia', 'email' => 'sofia.garcia@ems.com', 'role' => 'employee', 'position' => 'Marketing Specialist'],
                    ['first' => 'Marco', 'last' => 'Villanueva', 'email' => 'marco.villanueva@ems.com', 'role' => 'employee', 'position' => 'Content Writer'],
                    ['first' => 'Isabella', 'last' => 'Lopez', 'email' => 'isabella.lopez@ems.com', 'role' => 'employee', 'position' => 'Social Media Manager'],
                    ['first' => 'Rafael', 'last' => 'Torres', 'email' => 'rafael.torres@ems.com', 'role' => 'head_department', 'position' => 'Head of Marketing'],
                ]
            ],
            'Human Resources' => [
                'code' => 'HR',
                'employees' => [
                    ['first' => 'Patricia', 'last' => 'Mendoza', 'email' => 'patricia.mendoza@ems.com', 'role' => 'employee', 'position' => 'HR Specialist'],
                    ['first' => 'Carlos', 'last' => 'Aquino', 'email' => 'carlos.aquino@ems.com', 'role' => 'employee', 'position' => 'Recruitment Officer'],
                    ['first' => 'Beatrice', 'last' => 'Lim', 'email' => 'beatrice.lim@ems.com', 'role' => 'employee', 'position' => 'Training Coordinator'],
                    ['first' => 'Eduardo', 'last' => 'Rivera', 'email' => 'eduardo.rivera@ems.com', 'role' => 'head_department', 'position' => 'Head of HR'],
                ]
            ],
            'Customer Support' => [
                'code' => 'CS',
                'employees' => [
                    ['first' => 'Kim', 'last' => 'Santos', 'email' => 'kim.santos@ems.com', 'role' => 'employee', 'position' => 'Support Agent'],
                    ['first' => 'Nina', 'last' => 'Chavez', 'email' => 'nina.chavez@ems.com', 'role' => 'employee', 'position' => 'Support Agent'],
                    ['first' => 'Oscar', 'last' => 'Ramos', 'email' => 'oscar.ramos@ems.com', 'role' => 'employee', 'position' => 'Support Agent'],
                    ['first' => 'Diana', 'last' => 'Cruz', 'email' => 'diana.cruz@ems.com', 'role' => 'head_department', 'position' => 'Head of Support'],
                ]
            ],
            'Operations' => [
                'code' => 'OP',
                'employees' => [
                    ['first' => 'Fernando', 'last' => 'Pascual', 'email' => 'fernando.pascual@ems.com', 'role' => 'employee', 'position' => 'Operations Associate'],
                    ['first' => 'Gina', 'last' => 'Valdez', 'email' => 'gina.valdez@ems.com', 'role' => 'employee', 'position' => 'Logistics Coordinator'],
                    ['first' => 'Henry', 'last' => 'Ocampo', 'email' => 'henry.ocampo@ems.com', 'role' => 'employee', 'position' => 'Warehouse Supervisor'],
                    ['first' => 'Lorna', 'last' => 'Bautista', 'email' => 'lorna.bautista@ems.com', 'role' => 'head_department', 'position' => 'Head of Operations'],
                ]
            ],
        ];

        foreach ($departments as $deptName => $data) {
            $department = Department::where('name', $deptName)->first();
            
            if (!$department) {
                $this->command->warn("Department '$deptName' not found, skipping...");
                continue;
            }

            foreach ($data['employees'] as $empData) {
                // Check if employee already exists
                $exists = Employee::where('email', $empData['email'])->exists();
                if ($exists) {
                    $this->command->warn("Employee {$empData['email']} already exists, skipping...");
                    continue;
                }

                // Create or get position
                $position = Position::firstOrCreate(
                    ['title' => $empData['position'], 'department_id' => $department->id],
                    ['min_salary' => 30000, 'max_salary' => 60000]
                );

                // Create Employee
                $employee = Employee::create([
                    'first_name' => $empData['first'],
                    'last_name' => $empData['last'],
                    'email' => $empData['email'],
                    'phone' => '09' . rand(10, 99) . rand(1000000, 9999999),
                    'date_of_birth' => now()->subYears(rand(25, 40))->format('Y-m-d'),
                    'gender' => in_array($empData['first'], ['Roberto', 'Marco', 'Rafael', 'Carlos', 'Eduardo', 'Oscar', 'Fernando', 'Henry']) ? 'male' : 'female',
                    'address' => rand(100, 999) . ' ' . ['Main St', 'Oak Ave', 'Pine Rd', 'Elm Blvd', 'Cedar Ln'][rand(0, 4)] . ', Metro Manila',
                    'department_id' => $department->id,
                    'position_id' => $position->id,
                    'hire_date' => now()->subMonths(rand(3, 24))->format('Y-m-d'),
                    'salary' => rand(30000, 80000),
                    'employee_id' => 'EMP' . str_pad(Employee::count() + 1, 5, '0', STR_PAD_LEFT),
                    'status' => 'active',
                ]);

                // Create User account
                User::create([
                    'name' => $empData['first'] . ' ' . $empData['last'],
                    'email' => $empData['email'],
                    'password' => Hash::make('password123'),
                    'role' => $empData['role'],
                    'department_id' => $department->id,
                ]);

                $this->command->info("Created: {$empData['first']} {$empData['last']} - {$empData['role']} in {$deptName}");
            }
        }

        $this->command->info('==========================================');
        $this->command->info('All passwords: password123');
        $this->command->info('==========================================');
    }
}