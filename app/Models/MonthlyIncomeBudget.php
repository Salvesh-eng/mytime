<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MonthlyIncomeBudget extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'month',
        'year',
        'budgeted_amount',
        'actual_amount',
        'notes',
    ];

    protected $casts = [
        'budgeted_amount' => 'decimal:2',
        'actual_amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getDifferenceAttribute()
    {
        return $this->actual_amount - $this->budgeted_amount;
    }

    public function getVariancePercentageAttribute()
    {
        if ($this->budgeted_amount == 0) {
            return 0;
        }
        return round(($this->getDifferenceAttribute() / $this->budgeted_amount) * 100, 2);
    }
}
