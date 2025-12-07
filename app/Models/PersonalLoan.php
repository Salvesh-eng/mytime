<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PersonalLoan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'loan_type',
        'principal_amount',
        'interest_rate',
        'start_date',
        'end_date',
        'amount_paid',
        'status',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'principal_amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'interest_rate' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getRemainingAmountAttribute()
    {
        return $this->principal_amount - $this->amount_paid;
    }

    public function getFormattedPrincipalAttribute()
    {
        return number_format($this->principal_amount, 2);
    }

    public function getFormattedAmountPaidAttribute()
    {
        return number_format($this->amount_paid, 2);
    }
}
