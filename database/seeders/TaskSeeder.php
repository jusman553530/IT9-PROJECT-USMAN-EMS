<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run()
    {
        $admin = User::where('role', 'admin')->first() ?? User::first();
        
        $departments = Department::all();
        
        if ($departments->isEmpty()) {
            $this->command->error('No departments found!');
            return;
        }

        $tasksByDepartment = [
            'Customer Support' => [
                [
                    'title' => 'Update FAQ documentation',
                    'description' => 'Refresh the FAQ section with new common issues.',
                    'priority' => 'medium',
                    'due_date' => now()->addDays(7),
                ],
                [
                    'title' => 'Reduce ticket response time',
                    'description' => 'Implement new workflow to decrease average response time to under 1 hour.',
                    'priority' => 'high',
                    'due_date' => now()->addDays(5),
                ],
                [
                    'title' => 'Customer satisfaction survey',
                    'description' => 'Create and send out quarterly satisfaction survey to all recent customers.',
                    'priority' => 'medium',
                    'due_date' => now()->addDays(14),
                ],
            ],
            'Finance' => [
                [
                    'title' => 'Prepare monthly financial report',
                    'description' => 'Compile all financial data and generate the monthly report for management.',
                    'priority' => 'high',
                    'due_date' => now()->addDays(3),
                ],
                [
                    'title' => 'Audit expense reports',
                    'description' => 'Review and verify all employee expense reports for Q2.',
                    'priority' => 'urgent',
                    'due_date' => now()->addDays(2),
                ],
                [
                    'title' => 'Budget planning for next quarter',
                    'description' => 'Draft the budget proposal for Q3 based on current spending trends.',
                    'priority' => 'high',
                    'due_date' => now()->addDays(10),
                ],
            ],
            'Human Resources' => [
                [
                    'title' => 'Update employee handbook',
                    'description' => 'Revise the company policies section with new remote work guidelines.',
                    'priority' => 'medium',
                    'due_date' => now()->addDays(14),
                ],
                [
                    'title' => 'Organize team building event',
                    'description' => 'Plan and coordinate the upcoming quarterly team building activity.',
                    'priority' => 'low',
                    'due_date' => now()->addDays(21),
                ],
                [
                    'title' => 'Process new hire onboarding',
                    'description' => 'Prepare onboarding materials and schedule orientation for 5 new employees.',
                    'priority' => 'high',
                    'due_date' => now()->addDays(5),
                ],
            ],
            'Marketing' => [
                [
                    'title' => 'Launch social media campaign',
                    'description' => 'Execute the new product awareness campaign across all platforms.',
                    'priority' => 'urgent',
                    'due_date' => now()->addDays(3),
                ],
                [
                    'title' => 'Design new company brochure',
                    'description' => 'Create updated marketing materials for the upcoming trade show.',
                    'priority' => 'high',
                    'due_date' => now()->addDays(7),
                ],
            ],
            'Operations' => [
                [
                    'title' => 'Optimize inventory management',
                    'description' => 'Review current inventory system and suggest improvements.',
                    'priority' => 'high',
                    'due_date' => now()->addDays(5),
                ],
                [
                    'title' => 'Vendor contract renewal',
                    'description' => 'Review and negotiate contract terms with top 3 suppliers.',
                    'priority' => 'medium',
                    'due_date' => now()->addDays(10),
                ],
                [
                    'title' => 'Office equipment maintenance',
                    'description' => 'Schedule regular maintenance for all office equipment and IT infrastructure.',
                    'priority' => 'low',
                    'due_date' => now()->addDays(14),
                ],
            ],
        ];

        foreach ($tasksByDepartment as $deptName => $tasks) {
            $department = Department::where('name', $deptName)->first();
            
            if (!$department) {
                $this->command->warn("Department '$deptName' not found, skipping...");
                continue;
            }

            foreach ($tasks as $index => $task) {
                Task::create([
                    'task_code' => 'TSK' . date('Y') . str_pad(Task::count() + 1, 5, '0', STR_PAD_LEFT),
                    'title' => $task['title'],
                    'description' => $task['description'],
                    'department_id' => $department->id,
                    'assigned_by' => $admin->id,
                    'priority' => $task['priority'],
                    'status' => 'pending',
                    'due_date' => $task['due_date'],
                ]);
                
                $this->command->info("Created task for {$deptName}: {$task['title']}");
            }
        }
    }
}