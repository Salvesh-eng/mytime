<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\TimeEntry;
use App\Models\Project;
use App\Models\Activity;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Show the admin dashboard.
     */
    public function index()
    {
        $totalUsers = User::where('role', 'user')->count();
        $totalTimeEntries = TimeEntry::count();
        $hoursLoggedToday = TimeEntry::whereDate('entry_date', Carbon::today())
            ->sum('hours');
        
        $recentEntries = TimeEntry::with('user')
            ->latest('entry_date')
            ->limit(10)
            ->get();

        $pendingApprovals = TimeEntry::where('status', 'pending')->count();

        $recentProjects = Project::with('teamMembers')
            ->latest('created_at')
            ->limit(6)
            ->get();

        $totalProjects = Project::count();

        // Get all projects for the dashboard widget (limit to 12 for display)
        $allProjects = Project::latest('updated_at')
            ->limit(12)
            ->get();

        $recentActivities = Activity::with('user')
            ->latest('created_at')
            ->limit(10)
            ->get();

        return view('admin.dashboard', [
            'totalUsers' => $totalUsers,
            'totalTimeEntries' => $totalTimeEntries,
            'hoursLoggedToday' => $hoursLoggedToday,
            'recentEntries' => $recentEntries,
            'pendingApprovals' => $pendingApprovals,
            'recentProjects' => $recentProjects,
            'totalProjects' => $totalProjects,
            'allProjects' => $allProjects,
            'recentActivities' => $recentActivities,
        ]);
    }

    /**
     * Get dashboard metrics via API for auto-refresh.
     */
    public function getMetrics()
    {
        $startDate = request()->query('startDate');
        $endDate = request()->query('endDate');

        $query = TimeEntry::query();
        $userQuery = User::where('role', 'user');

        if ($startDate && $endDate) {
            $query->whereBetween('entry_date', [$startDate, $endDate]);
        }

        $totalUsers = $userQuery->count();
        $totalTimeEntries = $query->count();
        
        $hoursQuery = TimeEntry::query();
        if ($startDate && $endDate) {
            $hoursQuery->whereBetween('entry_date', [$startDate, $endDate]);
        } else {
            $hoursQuery->whereDate('entry_date', Carbon::today());
        }
        $hoursLoggedToday = $hoursQuery->sum('hours');

        $pendingQuery = TimeEntry::where('status', 'pending');
        if ($startDate && $endDate) {
            $pendingQuery->whereBetween('entry_date', [$startDate, $endDate]);
        }
        $pendingApprovals = $pendingQuery->count();

        // Get project distribution data
        $projectDistribution = $this->getProjectDistribution($startDate, $endDate);
        
        // Get daily hours data for last 7 days
        $dailyHours = $this->getDailyHours();

        return response()->json([
            'totalUsers' => $totalUsers,
            'totalTimeEntries' => $totalTimeEntries,
            'hoursLoggedToday' => number_format($hoursLoggedToday, 2),
            'pendingApprovals' => $pendingApprovals,
            'timestamp' => Carbon::now()->format('H:i:s'),
            'projectDistribution' => $projectDistribution,
            'dailyHours' => $dailyHours,
        ]);
    }

    /**
     * Get time distribution by project
     */
    private function getProjectDistribution($startDate = null, $endDate = null)
    {
        $query = TimeEntry::join('projects', 'time_entries.project_id', '=', 'projects.id')
            ->select('projects.name', DB::raw('SUM(time_entries.hours) as total_hours'))
            ->groupBy('projects.name');

        if ($startDate && $endDate) {
            $query->whereBetween('time_entries.entry_date', [$startDate, $endDate]);
        }

        $data = $query->orderByDesc('total_hours')->limit(8)->get();

        return [
            'labels' => $data->pluck('name')->toArray(),
            'values' => $data->pluck('total_hours')->toArray(),
        ];
    }

    /**
     * Get daily hours for last 7 days
     */
    private function getDailyHours()
    {
        $days = [];
        $hours = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $days[] = $date->format('M d');
            
            $dailyHours = TimeEntry::whereDate('entry_date', $date)->sum('hours');
            $hours[] = round($dailyHours, 2);
        }

        return [
            'labels' => $days,
            'values' => $hours,
        ];
    }

    /**
     * Get recent activities via API
     */
    public function getRecentActivities()
    {
        $activities = Activity::with('user')
            ->latest('created_at')
            ->limit(10)
            ->get()
            ->map(function ($activity) {
                return [
                    'id' => $activity->id,
                    'user_name' => $activity->user->name,
                    'user_id' => $activity->user->id,
                    'action_type' => $activity->action_type,
                    'description' => $activity->description,
                    'time_ago' => $activity->created_at->diffForHumans(),
                    'created_at' => $activity->created_at->format('M d, H:i'),
                    'icon' => $this->getActionIcon($activity->action_type),
                ];
            });

        return response()->json([
            'activities' => $activities,
            'timestamp' => Carbon::now()->format('H:i:s'),
        ]);
    }

    /**
     * Get icon for activity action type
     */
    private function getActionIcon($actionType)
    {
        $icons = [
            'start_timer' => '▶️',
            'submit_timesheet' => '📝',
            'approve_entry' => '✅',
            'reject_entry' => '❌',
            'create_project' => '📁',
            'complete_project' => '🏁',
            'join_project' => '👤',
        ];

        return $icons[$actionType] ?? '📌';
    }

    /**
     * Global search for projects and users
     */
    public function search()
    {
        $query = request()->query('q', '');

        if (strlen($query) < 2) {
            return response()->json(['projects' => [], 'users' => []]);
        }

        $projects = Project::where('name', 'like', '%' . $query . '%')
            ->limit(5)
            ->select('id', 'name')
            ->get();

        $users = User::where('name', 'like', '%' . $query . '%')
            ->orWhere('email', 'like', '%' . $query . '%')
            ->limit(5)
            ->select('id', 'name')
            ->get();

        return response()->json([
            'projects' => $projects,
            'users' => $users,
        ]);
    }
}

