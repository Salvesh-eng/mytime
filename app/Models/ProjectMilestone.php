<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectMilestone extends Model
{
    protected $fillable = [
        'project_id',
        'title',
        'description',
        'target_date',
        'status',
        'completion_percentage',
        'deliverables',
    ];

    protected $casts = [
        'target_date' => 'date',
        'deliverables' => 'array',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function getIsOverdueAttribute()
    {
        return now()->isAfter($this->target_date) && $this->status !== 'completed';
    }

    public function getDaysRemainingAttribute()
    {
        return $this->target_date->diffInDays(now(), false);
    }

    public function getProgressColorAttribute()
    {
        return match($this->status) {
            'pending' => '#9CA3AF',
            'in-progress' => '#06B6D4',
            'completed' => '#16A34A',
            'overdue' => '#DC2626',
            default => '#2563EB',
        };
    }

    public function updateStatus()
    {
        if ($this->completion_percentage >= 100) {
            $this->status = 'completed';
        } elseif ($this->completion_percentage > 0) {
            $this->status = 'in-progress';
        } elseif (now()->isAfter($this->target_date)) {
            $this->status = 'overdue';
        }

        $this->save();
        return $this;
    }
}
