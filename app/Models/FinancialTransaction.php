<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FinancialTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'type',
        'category',
        'description',
        'amount',
        'currency',
        'account',
        'transaction_date',
        'status',
        'notes',
        'created_by',
        'approved_by',
        'approved_at',
        'invoice_number',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'approved_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => '#F59E0B',
            'approved' => '#06B6D4',
            'rejected' => '#DC2626',
            'completed' => '#16A34A',
            default => '#6B7280',
        };
    }

    public function getTypeColorAttribute()
    {
        return match($this->type) {
            'income' => '#16A34A',
            'expense' => '#DC2626',
            default => '#6B7280',
        };
    }

    public function getCategoryIconAttribute()
    {
        return match($this->category) {
            'salary' => '👤',
            'equipment' => '🖥️',
            'software' => '💻',
            'travel' => '✈️',
            'utilities' => '⚡',
            'marketing' => '📢',
            'client_payment' => '💰',
            'other' => '📌',
            default => '📌',
        };
    }
}
