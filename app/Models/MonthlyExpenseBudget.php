<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MonthlyExpenseBudget extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'month',
        'year',
        'budgeted_amount',
        'notes',
    ];

    protected $casts = [
        'budgeted_amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function expenses()
    {
        return $this->hasMany(ExpenseTracking::class, 'user_id', 'user_id')
            ->whereMonth('expense_date', $this->month + 1)
            ->whereYear('expense_date', $this->year);
    }

    public function getTotalExpensesAttribute()
    {
        return $this->expenses()->sum('amount');
    }

    public function getRemainingBudgetAttribute()
    {
        return $this->budgeted_amount - $this->getTotalExpensesAttribute();
    }
}
