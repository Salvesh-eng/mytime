<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('role', 'user')->take(5)->get();

        if ($users->isEmpty()) {
            return;
        }

        $activities = [
            [
                'action_type' => 'start_timer',
                'description' => 'started timer on {project}',
                'model_type' => 'TimeEntry',
            ],
            [
                'action_type' => 'submit_timesheet',
                'description' => 'submitted timesheet for {hours} hours',
                'model_type' => 'TimeEntry',
            ],
            [
                'action_type' => 'approve_entry',
                'description' => 'approved time entry of {hours} hours',
                'model_type' => 'TimeEntry',
            ],
            [
                'action_type' => 'reject_entry',
                'description' => 'rejected time entry for {project}',
                'model_type' => 'TimeEntry',
            ],
            [
                'action_type' => 'create_project',
                'description' => 'created new project {project}',
                'model_type' => 'Project',
            ],
            [
                'action_type' => 'complete_project',
                'description' => 'marked project {project} as complete',
                'model_type' => 'Project',
            ],
            [
                'action_type' => 'join_project',
                'description' => 'joined project {project}',
                'model_type' => 'Project',
            ],
        ];

        $projects = ['Website Redesign', 'Mobile App', 'Dashboard Dev', 'API Integration', 'Testing'];

        // Create 15 sample activities
        for ($i = 0; $i < 15; $i++) {
            $user = $users->random();
            $activity = $activities[array_rand($activities)];
            $project = $projects[array_rand($projects)];
            $hours = rand(1, 8);

            $description = $activity['description'];
            $description = str_replace('{project}', $project, $description);
            $description = str_replace('{hours}', $hours, $description);

            Activity::create([
                'user_id' => $user->id,
                'action_type' => $activity['action_type'],
                'description' => $description,
                'model_type' => $activity['model_type'],
                'model_id' => rand(1, 10),
                'created_at' => Carbon::now()->subMinutes(rand(1, 1440)), // Random time in last 24 hours
            ]);
        }
    }
}
