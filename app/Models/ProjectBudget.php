<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectBudget extends Model
{
    protected $fillable = [
        'project_id',
        'allocated_budget',
        'spent_amount',
        'currency',
        'notes',
    ];

    protected $casts = [
        'allocated_budget' => 'decimal:2',
        'spent_amount' => 'decimal:2',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function getRemainingBudgetAttribute()
    {
        return max(0, $this->allocated_budget - $this->spent_amount);
    }

    public function getBudgetUtilizationPercentageAttribute()
    {
        if ($this->allocated_budget <= 0) {
            return 0;
        }

        return min(100, round(($this->spent_amount / $this->allocated_budget) * 100, 2));
    }

    public function isBudgetExceeded()
    {
        return $this->spent_amount > $this->allocated_budget;
    }

    public function getBudgetStatusAttribute()
    {
        $percentage = $this->budget_utilization_percentage;

        if ($percentage >= 100) {
            return 'exceeded';
        } elseif ($percentage >= 80) {
            return 'warning';
        } elseif ($percentage >= 50) {
            return 'moderate';
        } else {
            return 'healthy';
        }
    }
}
