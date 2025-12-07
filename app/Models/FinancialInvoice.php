<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FinancialInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'project_id',
        'client_id',
        'subtotal',
        'tax_amount',
        'total_amount',
        'currency',
        'issue_date',
        'due_date',
        'status',
        'notes',
        'terms',
        'created_by',
        'sent_at',
        'paid_at',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
        'sent_at' => 'datetime',
        'paid_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function client()
    {
        return $this->belongsTo(ProjectClient::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'draft' => '#6B7280',
            'sent' => '#F59E0B',
            'paid' => '#16A34A',
            'overdue' => '#DC2626',
            'cancelled' => '#8B5CF6',
            default => '#2563EB',
        };
    }

    public function getDaysOverdueAttribute()
    {
        if ($this->status !== 'overdue') {
            return 0;
        }
        return now()->diffInDays($this->due_date);
    }
}
