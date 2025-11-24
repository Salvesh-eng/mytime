<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Project;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@mytime.local'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'status' => 'active',
            ]
        );

        User::firstOrCreate(
            ['email' => 'user@mytime.local'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
                'role' => 'user',
                'status' => 'active',
            ]
        );

        // Create test projects for notifications
        Project::firstOrCreate(
            ['name' => 'Website Redesign'],
            [
                'description' => 'Complete redesign of the main website',
                'status' => 'in-progress',
                'start_date' => Carbon::now()->addDays(3),
                'due_date' => Carbon::now()->addDays(15),
                'progress' => 45,
            ]
        );

        Project::firstOrCreate(
            ['name' => 'Mobile App Development'],
            [
                'description' => 'Native iOS and Android app',
                'status' => 'planning',
                'start_date' => Carbon::now()->addDays(10),
                'due_date' => Carbon::now()->addDays(60),
                'progress' => 0,
            ]
        );

        Project::firstOrCreate(
            ['name' => 'Q4 Marketing Campaign'],
            [
                'description' => 'Holiday season marketing push',
                'status' => 'in-progress',
                'start_date' => Carbon::now()->subDays(5),
                'due_date' => Carbon::now()->addDays(5),
                'progress' => 65,
            ]
        );

        Project::firstOrCreate(
            ['name' => 'Database Migration'],
            [
                'description' => 'Migrate from old to new database',
                'status' => 'planning',
                'start_date' => Carbon::now()->addDays(2),
                'due_date' => Carbon::now()->addDays(20),
                'progress' => 0,
            ]
        );
    }
}
