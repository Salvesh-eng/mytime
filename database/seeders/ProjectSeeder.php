<?php

namespace Database\Seeders;

use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the projects table with 50 real projects.
     */
    public function run(): void
    {
        $projectNames = [
            'Website Redesign',
            'Mobile App Development',
            'API Development',
            'Database Migration',
            'Cloud Infrastructure',
            'Security Audit',
            'Performance Optimization',
            'UI/UX Overhaul',
            'Data Analytics Platform',
            'CRM System',
            'E-commerce Platform',
            'Chat Application',
            'Video Streaming Service',
            'IoT Dashboard',
            'AI Integration',
            'Blockchain Project',
            'Machine Learning Pipeline',
            'DevOps Pipeline',
            'Microservices Architecture',
            'GraphQL API',
            'Real-time Notification System',
            'Payment Gateway Integration',
            'Email Marketing Platform',
            'Social Media Integration',
            'Document Management System',
            'Inventory Management',
            'HR Management System',
            'Project Management Tool',
            'Customer Support Portal',
            'Analytics Dashboard',
            'Mobile Banking App',
            'Fitness Tracking App',
            'Travel Booking Platform',
            'Food Delivery App',
            'Ride Sharing App',
            'Marketplace Platform',
            'Learning Management System',
            'Video Conference Tool',
            'Collaboration Suite',
            'Task Management App',
            'Note Taking App',
            'Password Manager',
            'VPN Service',
            'Cloud Storage',
            'Backup Solution',
            'Disaster Recovery',
            'Network Monitoring',
            'System Administration',
            'Database Optimization',
            'Code Review Platform',
        ];

        $statuses = ['planning', 'in-progress', 'completed', 'on-hold'];
        $descriptions = [
            'Modernizing the user interface and improving user experience',
            'Building a native mobile application for iOS and Android',
            'Creating RESTful and GraphQL APIs for third-party integrations',
            'Migrating legacy database to modern cloud infrastructure',
            'Setting up scalable cloud infrastructure on AWS/Azure',
            'Conducting comprehensive security assessment and penetration testing',
            'Optimizing application performance and reducing load times',
            'Redesigning user interface with modern design principles',
            'Building advanced analytics and reporting capabilities',
            'Implementing customer relationship management system',
            'Building full-featured e-commerce platform with payment integration',
            'Developing real-time chat application with messaging features',
            'Creating video streaming platform with adaptive bitrate',
            'Building IoT dashboard for device monitoring and control',
            'Integrating artificial intelligence for predictive analytics',
            'Implementing blockchain technology for secure transactions',
            'Building machine learning models for data prediction',
            'Setting up CI/CD pipeline for automated deployments',
            'Migrating monolithic application to microservices',
            'Implementing GraphQL API for efficient data querying',
            'Building real-time notification system with WebSockets',
            'Integrating multiple payment gateways',
            'Creating email marketing platform with automation',
            'Integrating social media APIs for sharing and authentication',
            'Building document management system with version control',
            'Implementing inventory tracking and management',
            'Creating HR management system with payroll integration',
            'Building project management tool with team collaboration',
            'Creating customer support portal with ticketing system',
            'Building analytics dashboard with real-time data visualization',
            'Developing mobile banking application with security features',
            'Creating fitness tracking app with health metrics',
            'Building travel booking platform with payment integration',
            'Creating food delivery app with real-time tracking',
            'Building ride sharing app with GPS integration',
            'Creating marketplace platform for sellers and buyers',
            'Building learning management system with course management',
            'Creating video conference tool with screen sharing',
            'Building collaboration suite with document editing',
            'Creating task management app with team collaboration',
            'Building note taking app with cloud sync',
            'Creating password manager with encryption',
            'Building VPN service with multiple protocols',
            'Creating cloud storage solution with file sharing',
            'Building backup solution with disaster recovery',
            'Creating network monitoring tool with alerts',
            'Building system administration dashboard',
            'Optimizing database queries and indexing',
            'Creating code review platform for team collaboration',
        ];

        for ($i = 1; $i <= 50; $i++) {
            $startDate = Carbon::now()->subDays(rand(1, 180));
            $dueDate = $startDate->clone()->addDays(rand(30, 180));
            $status = $statuses[array_rand($statuses)];
            $progress = $status === 'completed' ? 100 : ($status === 'on-hold' ? rand(0, 30) : rand(20, 95));
            $estimatedHours = rand(40, 500);
            $actualHours = ($progress / 100) * $estimatedHours;

            Project::create([
                'name' => $projectNames[($i - 1) % count($projectNames)] . ' #' . $i,
                'description' => $descriptions[($i - 1) % count($descriptions)],
                'status' => $status,
                'start_date' => $startDate,
                'due_date' => $dueDate,
                'progress' => $progress,
                'estimated_hours' => $estimatedHours,
                'actual_hours' => $actualHours,
                'is_archived' => false,
            ]);
        }
    }
}
