<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PersonalExpense extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'account_id',
        'expense_date',
        'invoice_number',
        'category',
        'amount',
        'currency',
        'description',
        'status',
    ];

    protected $casts = [
        'expense_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function account()
    {
        return $this->belongsTo(PersonalAccount::class);
    }

    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 2);
    }
}
