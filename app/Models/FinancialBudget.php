<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FinancialBudget extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'name',
        'allocated_amount',
        'spent_amount',
        'currency',
        'start_date',
        'end_date',
        'status',
        'description',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function getRemainingBudgetAttribute()
    {
        return max(0, $this->allocated_amount - $this->spent_amount);
    }

    public function getBudgetPercentageAttribute()
    {
        if ($this->allocated_amount <= 0) {
            return 0;
        }
        return min(100, round(($this->spent_amount / $this->allocated_amount) * 100, 2));
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'active' => '#06B6D4',
            'completed' => '#16A34A',
            'archived' => '#6B7280',
            default => '#2563EB',
        };
    }
}
