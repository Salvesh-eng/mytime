<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'status',
        'start_date',
        'due_date',
        'progress',
        'is_archived',
        'archived_at',
        'estimated_hours',
        'actual_hours',
        'slug',
    ];

    protected $casts = [
        'start_date' => 'date',
        'due_date' => 'date',
        'archived_at' => 'datetime',
        'is_archived' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->slug) {
                $model->slug = Str::slug($model->name);
            }
        });
    }

    // Relationships
    public function teamMembers()
    {
        return $this->belongsToMany(TeamMember::class, 'project_team_member');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function tags()
    {
        return $this->hasMany(ProjectTag::class);
    }

    public function budget()
    {
        return $this->hasOne(ProjectBudget::class);
    }

    public function milestones()
    {
        return $this->hasMany(ProjectMilestone::class);
    }

    public function client()
    {
        return $this->hasOne(ProjectClient::class);
    }

    public function timeEntries()
    {
        return $this->hasMany(TimeEntry::class);
    }

    // Attributes & Accessors
    public function getProgressColorAttribute()
    {
        if ($this->progress <= 25) {
            return '#DC2626'; // Red
        } elseif ($this->progress <= 50) {
            return '#F59E0B'; // Orange
        } elseif ($this->progress <= 75) {
            return '#06B6D4'; // Teal
        } else {
            return '#16A34A'; // Green
        }
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'planning' => '#F59E0B',
            'in-progress' => '#06B6D4',
            'completed' => '#16A34A',
            'on-hold' => '#6B7280',
            default => '#2563EB',
        };
    }

    // Methods
    public function getProgressPercentageAttribute()
    {
        if ($this->estimated_hours <= 0) {
            return $this->progress ?? 0;
        }

        return min(100, round(($this->actual_hours / $this->estimated_hours) * 100, 2));
    }

    public function getRemainingBudgetAttribute()
    {
        if (!$this->budget) {
            return 0;
        }

        return max(0, $this->budget->allocated_budget - $this->budget->spent_amount);
    }

    public function getBudgetPercentageAttribute()
    {
        if (!$this->budget || $this->budget->allocated_budget <= 0) {
            return 0;
        }

        return min(100, round(($this->budget->spent_amount / $this->budget->allocated_budget) * 100, 2));
    }

    public function archive()
    {
        $this->update([
            'is_archived' => true,
            'archived_at' => now(),
        ]);

        return $this;
    }

    public function restore()
    {
        $this->update([
            'is_archived' => false,
            'archived_at' => null,
        ]);

        return $this;
    }

    public function getTotalTimeSpentAttribute()
    {
        return $this->timeEntries()->sum('duration_minutes') / 60;
    }

    public function getUpcomingMilestonesAttribute()
    {
        return $this->milestones()
            ->whereIn('status', ['pending', 'in-progress'])
            ->where('target_date', '>=', now())
            ->orderBy('target_date')
            ->limit(3)
            ->get();
    }

    public function getTagsArrayAttribute()
    {
        return $this->tags()->pluck('category')->toArray();
    }
}
