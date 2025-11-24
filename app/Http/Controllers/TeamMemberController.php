<?php

namespace App\Http\Controllers;

use App\Models\TeamMember;
use Illuminate\Http\Request;

class TeamMemberController extends Controller
{
    /**
     * Show the team members list page.
     */
    public function index()
    {
        $teamMembers = TeamMember::latest()->paginate(10);
        return view('admin.team-members.index', compact('teamMembers'));
    }

    /**
     * Show the form for creating a new team member.
     */
    public function create()
    {
        return view('admin.team-members.create');
    }

    /**
     * Store a newly created team member in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:team_members,email',
            'role' => 'required|string|max:100',
            'status' => 'required|in:active,inactive',
        ]);

        TeamMember::create($validated);

        return redirect('/admin/team-members')
            ->with('success', 'Team member added successfully!');
    }

    /**
     * Show the form for editing the specified team member.
     */
    public function edit(TeamMember $teamMember)
    {
        return view('admin.team-members.edit', compact('teamMember'));
    }

    /**
     * Update the specified team member in storage.
     */
    public function update(Request $request, TeamMember $teamMember)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:team_members,email,' . $teamMember->id,
            'role' => 'required|string|max:100',
            'status' => 'required|in:active,inactive',
        ]);

        $teamMember->update($validated);

        return redirect('/admin/team-members')
            ->with('success', 'Team member updated successfully!');
    }

    /**
     * Delete the specified team member.
     */
    public function destroy(TeamMember $teamMember)
    {
        $teamMember->delete();
        return redirect('/admin/team-members')
            ->with('success', 'Team member deleted successfully!');
    }
}
