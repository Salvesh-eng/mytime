<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Generate and get notifications for the authenticated user
     */
    public function getNotifications()
    {
        // Generate notifications from projects
        $this->generateNotifications();

        // Get unread notifications
        $notifications = Notification::where('is_read', false)
            ->latest()
            ->limit(5)
            ->get()
            ->map(function ($notif) {
                return [
                    'id' => $notif->id,
                    'type' => $notif->type,
                    'title' => $notif->title,
                    'message' => $notif->message,
                    'time' => $notif->created_at->format('M d, Y g:i A'),
                    'project_id' => $notif->project_id,
                ];
            });

        return response()->json([
            'notifications' => $notifications,
            'count' => $notifications->count(),
        ]);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Request $request, $id)
    {
        $notification = Notification::find($id);
        
        if ($notification) {
            $notification->update(['is_read' => true]);
            return response()->json(['success' => true, 'message' => 'Marked as read']);
        }

        return response()->json(['success' => false, 'message' => 'Notification not found'], 404);
    }

    /**
     * Delete notification
     */
    public function deleteNotification($id)
    {
        $notification = Notification::find($id);
        
        if ($notification) {
            $notification->delete();
            return response()->json(['success' => true, 'message' => 'Deleted']);
        }

        return response()->json(['success' => false, 'message' => 'Notification not found'], 404);
    }

    /**
     * Generate notifications from projects
     */
    private function generateNotifications()
    {
        // Clear old notifications (older than 30 days)
        Notification::where('created_at', '<', Carbon::now()->subDays(30))->delete();

        // Get upcoming projects (starting within 7 days)
        $upcomingProjects = Project::where('start_date', '>=', Carbon::now())
            ->where('start_date', '<=', Carbon::now()->addDays(7))
            ->get();

        foreach ($upcomingProjects as $project) {
            // Check if notification already exists for this project today
            $exists = Notification::where('project_id', $project->id)
                ->where('type', 'upcoming')
                ->whereDate('created_at', Carbon::today())
                ->exists();

            if (!$exists) {
                $daysUntil = Carbon::now()->diffInDays($project->start_date);
                Notification::create([
                    'type' => 'upcoming',
                    'title' => 'Upcoming Project',
                    'message' => "{$project->name} starts in {$daysUntil} day" . ($daysUntil !== 1 ? 's' : ''),
                    'project_id' => $project->id,
                    'is_read' => false,
                ]);
            }
        }

        // Get projects due soon (due within 7 days)
        $dueProjects = Project::whereNotNull('due_date')
            ->where('due_date', '>=', Carbon::now())
            ->where('due_date', '<=', Carbon::now()->addDays(7))
            ->where('status', '!=', 'completed')
            ->get();

        foreach ($dueProjects as $project) {
            $exists = Notification::where('project_id', $project->id)
                ->where('type', 'due')
                ->whereDate('created_at', Carbon::today())
                ->exists();

            if (!$exists) {
                $daysUntil = Carbon::now()->diffInDays($project->due_date);
                Notification::create([
                    'type' => 'due',
                    'title' => 'Project Due Soon',
                    'message' => "{$project->name} is due in {$daysUntil} day" . ($daysUntil !== 1 ? 's' : ''),
                    'project_id' => $project->id,
                    'is_read' => false,
                ]);
            }
        }

        // Get recently added projects (added within 24 hours)
        $recentProjects = Project::where('created_at', '>=', Carbon::now()->subHours(24))
            ->get();

        foreach ($recentProjects as $project) {
            $exists = Notification::where('project_id', $project->id)
                ->where('type', 'new')
                ->whereDate('created_at', Carbon::today())
                ->exists();

            if (!$exists) {
                Notification::create([
                    'type' => 'new',
                    'title' => 'New Project Added',
                    'message' => "{$project->name} was just created",
                    'project_id' => $project->id,
                    'is_read' => false,
                ]);
            }
        }
    }

    /**
     * Show the notifications page
     */
    public function index()
    {
        // Generate notifications
        $this->generateNotifications();

        $allNotifications = Notification::with('project')->latest()->get();

        // Group by type and get the projects with notification data
        $upcomingProjects = collect();
        $dueProjects = collect();
        $recentProjects = collect();

        foreach ($allNotifications as $notification) {
            if ($notification->type === 'upcoming') {
                if ($notification->project) {
                    $notification->project->notification_id = $notification->id;
                    $upcomingProjects->push($notification->project);
                }
            } elseif ($notification->type === 'due') {
                if ($notification->project) {
                    $notification->project->notification_id = $notification->id;
                    $dueProjects->push($notification->project);
                }
            } elseif ($notification->type === 'new') {
                if ($notification->project) {
                    $notification->project->notification_id = $notification->id;
                    $recentProjects->push($notification->project);
                }
            }
        }

        return view('admin.notifications.index', compact('upcomingProjects', 'dueProjects', 'recentProjects'));
    }
}
