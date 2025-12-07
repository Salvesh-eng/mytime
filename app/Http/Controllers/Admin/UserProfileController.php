<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Activity;
use Illuminate\Http\Request;
use Carbon\Carbon;

class UserProfileController extends Controller
{
    /**
     * Display user profile page
     */
    public function show(User $user)
    {
        $user->load('manager', 'subordinates', 'projects', 'timeEntries', 'activities');

        // Calculate performance metrics
        $totalHours = $user->timeEntries()->sum('duration_minutes') / 60;
        $totalProjects = $user->projects()->count();
        $completedProjects = $user->projects()->where('status', 'completed')->count();
        $activeProjects = $user->projects()->where('status', 'in-progress')->count();
        
        // Get recent activities
        $recentActivities = $user->activities()->limit(10)->get();
        
        // Get time entries for this month
        $thisMonth = Carbon::now()->startOfMonth();
        $monthlyHours = $user->timeEntries()
            ->whereDate('entry_date', '>=', $thisMonth)
            ->sum('duration_minutes') / 60;

        // Get team hierarchy
        $team = $this->getTeamHierarchy($user);

        return view('admin.users.profile', [
            'user' => $user,
            'totalHours' => $totalHours,
            'totalProjects' => $totalProjects,
            'completedProjects' => $completedProjects,
            'activeProjects' => $activeProjects,
            'monthlyHours' => $monthlyHours,
            'recentActivities' => $recentActivities,
            'team' => $team,
        ]);
    }

    /**
     * Get team hierarchy for a user
     */
    private function getTeamHierarchy(User $user)
    {
        $hierarchy = [
            'manager' => $user->manager,
            'subordinates' => $user->subordinates()->with('subordinates')->get(),
            'peers' => [],
        ];

        // Get peers (users with same manager)
        if ($user->manager_id) {
            $hierarchy['peers'] = User::where('manager_id', $user->manager_id)
                ->where('id', '!=', $user->id)
                ->get();
        }

        return $hierarchy;
    }

    /**
     * Display team hierarchy visualization
     */
    public function teamHierarchy()
    {
        // Get all users with their managers
        $users = User::with('manager', 'subordinates')
            ->where('status', 'active')
            ->get();

        // Build hierarchy tree
        $hierarchy = $this->buildHierarchyTree($users);

        return view('admin.users.team-hierarchy', [
            'hierarchy' => $hierarchy,
            'users' => $users,
        ]);
    }

    /**
     * Build hierarchy tree from users
     */
    private function buildHierarchyTree($users)
    {
        $tree = [];
        
        // Find root users (no manager)
        foreach ($users as $user) {
            if (!$user->manager_id) {
                $tree[] = $this->buildUserNode($user, $users);
            }
        }

        return $tree;
    }

    /**
     * Build a user node with subordinates
     */
    private function buildUserNode(User $user, $allUsers)
    {
        $subordinates = $user->subordinates()->get();
        
        return [
            'user' => $user,
            'subordinates' => $subordinates->map(function ($sub) use ($allUsers) {
                return $this->buildUserNode($sub, $allUsers);
            })->toArray(),
        ];
    }

    /**
     * Display user activity log
     */
    public function activityLog(User $user)
    {
        $activities = $user->activities()
            ->paginate(20);

        // Get activity statistics
        $stats = [
            'total_activities' => $user->activities()->count(),
            'this_month' => $user->activities()
                ->whereDate('created_at', '>=', Carbon::now()->startOfMonth())
                ->count(),
            'this_week' => $user->activities()
                ->whereDate('created_at', '>=', Carbon::now()->startOfWeek())
                ->count(),
        ];

        // Get activity breakdown by type
        $activityBreakdown = $user->activities()
            ->selectRaw('action_type, COUNT(*) as count')
            ->groupBy('action_type')
            ->get();

        return view('admin.users.activity-log', [
            'user' => $user,
            'activities' => $activities,
            'stats' => $stats,
            'activityBreakdown' => $activityBreakdown,
        ]);
    }

    /**
     * Upload profile photo for a user
     */
    public function uploadPhoto(Request $request, User $user)
    {
        $request->validate([
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Delete old photo if exists
        if ($user->photo_url && \Storage::disk('public')->exists($user->photo_url)) {
            \Storage::disk('public')->delete($user->photo_url);
        }

        // Store new photo
        $path = $request->file('profile_photo')->store('profile-photos', 'public');
        
        $user->update([
            'photo_url' => $path,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Profile photo updated successfully!');
    }
}
