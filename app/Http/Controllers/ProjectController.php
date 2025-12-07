<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\TeamMember;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Show the projects list page.
     */
    public function index()
    {
        $projects = Project::with('teamMembers')->latest()->paginate(10);
        return view('admin.projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new project.
     */
    public function create()
    {
        $teamMembers = TeamMember::where('status', 'active')->get();
        return view('admin.projects.create', compact('teamMembers'));
    }

    /**
     * Store a newly created project in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:planning,in-progress,completed,on-hold',
            'start_date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:start_date',
            'progress' => 'required|integer|min:0|max:100',
            'team_members' => 'nullable|array',
            'team_members.*' => 'exists:team_members,id',
        ]);

        $project = Project::create($validated);

        if (!empty($validated['team_members'])) {
            $project->teamMembers()->attach($validated['team_members']);
        }

        return redirect('/admin/projects')
            ->with('success', 'Project created successfully!');
    }

    /**
     * Show the form for editing the specified project.
     */
    public function edit(Project $project)
    {
        $teamMembers = TeamMember::where('status', 'active')->get();
        $selectedTeamMembers = $project->teamMembers->pluck('id')->toArray();
        return view('admin.projects.edit', compact('project', 'teamMembers', 'selectedTeamMembers'));
    }

    /**
     * Update the specified project in storage.
     */
    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:planning,in-progress,completed,on-hold',
            'start_date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:start_date',
            'progress' => 'required|integer|min:0|max:100',
            'team_members' => 'nullable|array',
            'team_members.*' => 'exists:team_members,id',
        ]);

        $project->update($validated);
        $project->teamMembers()->sync($validated['team_members'] ?? []);

        return redirect('/admin/projects')
            ->with('success', 'Project updated successfully!');
    }

    /**
     * Delete the specified project.
     */
    public function destroy(Project $project)
    {
        $project->delete();
        return redirect('/admin/projects')
            ->with('success', 'Project deleted successfully!');
    }

    /**
     * Update project progress via AJAX.
     */
    public function updateProgress(Request $request, Project $project)
    {
        $validated = $request->validate([
            'progress' => 'required|integer|min:0|max:100',
        ]);

        $updateData = ['progress' => $validated['progress']];

        // Auto-complete project when progress reaches 100%
        if ($validated['progress'] == 100 && $project->status !== 'completed') {
            $updateData['status'] = 'completed';
        }

        $project->update($updateData);

        return response()->json([
            'success' => true,
            'progress' => $project->progress,
            'status' => $project->status,
        ]);
    }

    /**
     * Update project status via AJAX.
     */
    public function updateStatus(Request $request, Project $project)
    {
        $validated = $request->validate([
            'status' => 'required|in:planning,in-progress,completed,on-hold',
        ]);

        $project->update(['status' => $validated['status']]);

        return response()->json([
            'success' => true,
            'status' => $project->status,
            'message' => 'Project status updated successfully!',
        ]);
    }
}
