<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectTag;
use App\Models\ProjectBudget;
use App\Models\ProjectMilestone;
use App\Models\ProjectClient;
use App\Models\ProjectTemplate;
use App\Models\TeamMember;
use App\Models\TimeEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProjectController extends Controller
{
    /**
     * Display a listing of all projects.
     */
    public function index()
    {
        $perPage = request('per_page', 10);
        $projects = Project::where('is_archived', false)
            ->with('tags', 'budget', 'client', 'teamMembers')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        $categories = ProjectTag::distinct()->pluck('category');
        $statuses = ['Active', 'In Progress', 'Completed', 'Pending Review', 'Started', 'On Hold', 'Cancelled', 'Archived','Awaiting Input','Not Started','Testing'];

        // Get projects completed per day for the current month
        $currentMonth = Carbon::now();
        $startOfMonth = $currentMonth->clone()->startOfMonth();
        $endOfMonth = $currentMonth->clone()->endOfMonth();

        $completedPerDay = Project::where('is_archived', false)
            ->where('status', 'completed')
            ->whereBetween('updated_at', [$startOfMonth, $endOfMonth])
            ->get()
            ->groupBy(function ($project) {
                return $project->updated_at->format('Y-m-d');
            })
            ->map(function ($projects) {
                return $projects->count();
            })
            ->toArray();

        // Create array with all days of the month
        $monthlyData = [];
        for ($day = 1; $day <= $endOfMonth->day; $day++) {
            $date = $startOfMonth->clone()->addDays($day - 1)->format('Y-m-d');
            $monthlyData[$date] = $completedPerDay[$date] ?? 0;
        }

        // Count overdue projects
        $overdueCount = Project::where('is_archived', false)
            ->where('status', '!=', 'completed')
            ->whereNotNull('due_date')
            ->where('due_date', '<', now()->toDateString())
            ->count();

        return view('admin.projects.index', [
            'projects' => $projects,
            'categories' => $categories,
            'statuses' => $statuses,
            'monthlyData' => $monthlyData,
            'currentMonth' => $currentMonth->format('F Y'),
            'overdueCount' => $overdueCount,
        ]);
    }

    /**
     * Display archived projects.
     */
    public function archived()
    {
        $projects = Project::where('is_archived', true)
            ->with('tags', 'budget', 'client')
            ->orderBy('archived_at', 'desc')
            ->paginate(15);

        return view('admin.projects.archived', [
            'projects' => $projects,
        ]);
    }

    /**
     * Show the form for creating a new project.
     */
    public function create()
    {
        $templates = ProjectTemplate::where('is_active', true)->get();
        $teamMembers = TeamMember::all();

        return view('admin.projects.create', [
            'templates' => $templates,
            'teamMembers' => $teamMembers,
        ]);
    }

    /**
     * Store a newly created project in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:planning,in-progress,completed,on-hold,cancelled,archived,awaiting-input,not-started,testing,overdue',
            'start_date' => 'required|date',
            'due_date' => 'required|date|after:start_date',
            'estimated_hours' => 'nullable|numeric|min:0',
            'template_id' => 'nullable|exists:project_templates,id',
            'progress' => 'nullable|numeric|min:0|max:100',
            'team_members' => 'nullable|array',
            'team_members.*' => 'exists:team_members,id',
        ]);

        $project = Project::create($validated);

        // Sync team members from form
        if (!empty($validated['team_members'])) {
            $project->teamMembers()->sync($validated['team_members']);
        }

        // If using a template
        if ($request->template_id) {
            $template = ProjectTemplate::find($request->template_id);
            if ($template->team_members) {
                $project->teamMembers()->sync($template->team_members);
            }
            $template->increment('usage_count');
        }

        return redirect()->route('admin.projects.index')
            ->with('success', 'Project created successfully!');
    }

    /**
     * Display the specified project (dashboard view).
     */
    public function show(Project $project)
    {
        $project->load('tags', 'budget', 'milestones', 'client', 'teamMembers', 'timeEntries');

        $stats = [
            'total_team_members' => $project->teamMembers()->count(),
            'total_milestones' => $project->milestones()->count(),
            'completed_milestones' => $project->milestones()->where('status', 'completed')->count(),
            'total_hours_logged' => $project->timeEntries()->sum('duration_minutes') / 60,
            'estimated_hours' => $project->estimated_hours,
            'remaining_budget' => $project->budget?->remaining_budget ?? 0,
            'budget_spent' => $project->budget?->spent_amount ?? 0,
        ];

        $upcomingMilestones = $project->milestones()
            ->where('status', '!=', 'completed')
            ->where('target_date', '>=', now())
            ->orderBy('target_date')
            ->limit(5)
            ->get();

        $recentTimeEntries = $project->timeEntries()
            ->with('user')
            ->latest('entry_date')
            ->limit(10)
            ->get();

        return view('admin.projects.show', [
            'project' => $project,
            'stats' => $stats,
            'upcomingMilestones' => $upcomingMilestones,
            'recentTimeEntries' => $recentTimeEntries,
        ]);
    }

    /**
     * Show the form for editing the specified project.
     */
    public function edit(Project $project)
    {
        $project->load('tags', 'budget', 'milestones', 'client', 'teamMembers');
        $teamMembers = TeamMember::all();
        $categories = ProjectTag::distinct()->pluck('category');
        $selectedTeamMembers = $project->teamMembers()->pluck('team_members.id')->toArray();

        return view('admin.projects.edit', [
            'project' => $project,
            'teamMembers' => $teamMembers,
            'categories' => $categories,
            'selectedTeamMembers' => $selectedTeamMembers,
        ]);
    }

    /**
     * Update the specified project in storage.
     */
    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:planning,in-progress,completed,on-hold,cancelled,archived,awaiting-input,not-started,testing,overdue',
            'start_date' => 'required|date',
            'due_date' => 'nullable|date|after:start_date',
            'progress' => 'nullable|integer|min:0|max:100',
            'estimated_hours' => 'nullable|numeric|min:0',
        ]);

        $project->update($validated);

        // Sync team members if provided
        if ($request->has('team_members')) {
            $project->teamMembers()->sync($request->team_members);
        }

        return redirect()->route('admin.projects.index')->with('success', 'Project updated successfully!');
    }

    /**
     * Archive a project.
     */
    public function archive(Project $project)
    {
        $project->archive();

        return redirect()->back()->with('success', 'Project archived successfully!');
    }

    /**
     * Restore an archived project.
     */
    public function restore(Project $project)
    {
        $project->restore();

        return redirect()->route('admin.projects.show', $project)
            ->with('success', 'Project restored successfully!');
    }

    /**
     * Permanently delete a project.
     */
    public function destroy(Project $project)
    {
        $project->delete();

        return redirect()->route('admin.projects.index')
            ->with('success', 'Project deleted successfully!');
    }

    /**
     * Add or update project tags.
     */
    public function updateTags(Request $request, Project $project)
    {
        $validated = $request->validate([
            'categories' => 'required|array',
            'categories.*' => 'in:Development,Marketing,Design,Infrastructure,Testing,Documentation,Other',
        ]);

        $project->tags()->delete();

        foreach ($validated['categories'] as $category) {
            $colors = ProjectTag::getCategoryColors();
            ProjectTag::create([
                'project_id' => $project->id,
                'category' => $category,
                'color' => $colors[$category] ?? '#2563EB',
            ]);
        }

        return redirect()->back()->with('success', 'Project tags updated successfully!');
    }

    /**
     * Update project budget.
     */
    public function updateBudget(Request $request, Project $project)
    {
        $validated = $request->validate([
            'allocated_budget' => 'required|numeric|min:0',
            'spent_amount' => 'nullable|numeric|min:0',
            'currency' => 'required|string|max:3',
            'notes' => 'nullable|string',
        ]);

        ProjectBudget::updateOrCreate(
            ['project_id' => $project->id],
            $validated
        );

        return redirect()->back()->with('success', 'Budget updated successfully!');
    }

    /**
     * Create or update a milestone.
     */
    public function updateMilestone(Request $request, Project $project)
    {
        $validated = $request->validate([
            'id' => 'nullable|exists:project_milestones,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'target_date' => 'required|date|after:today',
            'completion_percentage' => 'nullable|integer|min:0|max:100',
            'deliverables' => 'nullable|string',
        ]);

        if ($request->id) {
            $milestone = ProjectMilestone::findOrFail($request->id);
            $milestone->update($validated);
        } else {
            ProjectMilestone::create([
                'project_id' => $project->id,
                ...$validated,
            ]);
        }

        return redirect()->back()->with('success', 'Milestone saved successfully!');
    }

    /**
     * Delete a milestone.
     */
    public function destroyMilestone(ProjectMilestone $milestone)
    {
        $project = $milestone->project;
        $milestone->delete();

        return redirect()->back()->with('success', 'Milestone deleted successfully!');
    }

    /**
     * Update client information for a project.
     */
    public function updateClient(Request $request, Project $project)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'company_name' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'billing_email' => 'nullable|email',
            'billing_address' => 'nullable|string',
            'tax_id' => 'nullable|string|max:50',
            'billing_notes' => 'nullable|string',
        ]);

        ProjectClient::updateOrCreate(
            ['project_id' => $project->id],
            $validated
        );

        return redirect()->back()->with('success', 'Client information updated successfully!');
    }

    /**
     * Display project templates.
     */
    public function templates()
    {
        $templates = ProjectTemplate::with('createdBy')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('admin.projects.templates', [
            'templates' => $templates,
        ]);
    }

    /**
     * Create a new project from template.
     */
    public function createFromTemplate(ProjectTemplate $template)
    {
        return view('admin.projects.create-from-template', [
            'template' => $template,
        ]);
    }

    /**
     * Save a project as a template.
     */
    public function saveAsTemplate(Request $request, Project $project)
    {
        $validated = $request->validate([
            'template_name' => 'required|string|max:255',
            'template_description' => 'nullable|string',
        ]);

        ProjectTemplate::create([
            'name' => $validated['template_name'],
            'description' => $validated['template_description'],
            'created_by' => \Illuminate\Support\Facades\Auth::id(),
            'tasks' => [], // Can be extended for task management
            'team_members' => $project->teamMembers()->pluck('id')->toArray(),
            'estimated_hours' => ['default' => $project->estimated_hours],
        ]);

        return redirect()->back()->with('success', 'Project saved as template successfully!');
    }

    /**
     * Get project analytics data.
     */
    public function getAnalytics(Project $project)
    {
        $timeByTeamMember = $project->timeEntries()
            ->with('user')
            ->get()
            ->groupBy('user_id')
            ->map(function ($entries) {
                return [
                    'user_name' => $entries->first()->user->name,
                    'total_hours' => $entries->sum('duration_minutes') / 60,
                    'entry_count' => $entries->count(),
                ];
            });

        $timeByDate = $project->timeEntries()
            ->get()
            ->groupBy(function ($entry) {
                return $entry->entry_date->format('Y-m-d');
            })
            ->map(function ($entries) {
                return $entries->sum('duration_minutes') / 60;
            });

        $progressData = [
            'estimated_hours' => $project->estimated_hours,
            'actual_hours' => $project->actual_hours,
            'progress_percentage' => $project->progress_percentage,
            'completion_status' => $project->status,
        ];

        return response()->json([
            'time_by_team_member' => $timeByTeamMember,
            'time_by_date' => $timeByDate,
            'progress_data' => $progressData,
        ]);
    }

    /**
     * Filter projects by category.
     */
    public function filterByCategory($category)
    {
        $projects = Project::whereHas('tags', function ($query) use ($category) {
            $query->where('category', $category);
        })
        ->where('is_archived', false)
        ->with('tags', 'budget', 'client')
        ->paginate(15);

        return view('admin.projects.index', [
            'projects' => $projects,
            'selected_category' => $category,
        ]);
    }

    /**
     * Get progress bars data for all projects.
     */
    public function getProgressBars()
    {
        $projects = Project::where('is_archived', false)
            ->with('budget', 'timeEntries')
            ->get()
            ->map(function ($project) {
                return [
                    'id' => $project->id,
                    'name' => $project->name,
                    'progress_percentage' => $project->progress_percentage,
                    'budget_percentage' => $project->budget_percentage,
                    'estimated_hours' => $project->estimated_hours,
                    'actual_hours' => $project->actual_hours,
                    'status' => $project->status,
                ];
            });

        return response()->json($projects);
    }

    /**
     * Update project progress and auto-update status based on progress percentage.
     * 
     * Status logic:
     * - 100%: completed
     * - 75-99%: in-progress (almost done)
     * - 50-74%: in-progress
     * - 25-49%: in-progress (getting started)
     * - 1-24%: planning (just started)
     * - 0%: planning
     */
    public function updateProgress(Request $request, Project $project)
    {
        $validated = $request->validate([
            'progress' => 'required|integer|min:0|max:100',
            'status' => 'nullable|string|in:planning,in-progress,completed,on-hold'
        ]);

        $progress = $validated['progress'];
        
        // Auto-determine status based on progress if not explicitly provided
        if (!$request->has('status') || empty($validated['status'])) {
            if ($progress === 100) {
                $status = 'completed';
            } elseif ($progress >= 25) {
                $status = 'in-progress';
            } else {
                $status = 'planning';
            }
        } else {
            $status = $validated['status'];
        }

        $project->update([
            'progress' => $progress,
            'status' => $status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Progress updated successfully',
            'data' => [
                'id' => $project->id,
                'progress' => $project->progress,
                'status' => $project->status,
            ]
        ]);
    }

    /**
     * Update project status via AJAX.
     */
    public function updateStatus(Request $request, Project $project)
    {
        $validated = $request->validate([
            'status' => 'required|in:planning,in-progress,completed,on-hold,cancelled,archived,awaiting-input,not-started,testing,overdue',
        ]);

        $project->update(['status' => $validated['status']]);

        return response()->json([
            'success' => true,
            'status' => $project->status,
            'message' => 'Project status updated successfully!',
        ]);
    }
}
