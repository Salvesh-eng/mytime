<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectTemplate extends Model
{
    protected $fillable = [
        'name',
        'description',
        'created_by',
        'tasks',
        'team_members',
        'estimated_hours',
        'is_active',
    ];

    protected $casts = [
        'tasks' => 'array',
        'team_members' => 'array',
        'estimated_hours' => 'array',
        'is_active' => 'boolean',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function createProjectFromTemplate(array $projectData)
    {
        $project = Project::create($projectData);

        // Assign team members if template has them
        if ($this->team_members) {
            $project->teamMembers()->sync($this->team_members);
        }

        $project->update(['usage_count' => $this->usage_count + 1]);

        return $project;
    }

    public function getTaskCountAttribute()
    {
        return count($this->tasks ?? []);
    }

    public function getTeamMemberCountAttribute()
    {
        return count($this->team_members ?? []);
    }
}
