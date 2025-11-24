<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TeamMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'role',
        'status',
    ];

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_team_member');
    }
}
