<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PersonalAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'account_type',
        'account_number',
        'account_name',
        'balance',
        'currency',
        'status',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function incomes()
    {
        return $this->hasMany(PersonalIncome::class, 'account_id');
    }

    public function expenses()
    {
        return $this->hasMany(PersonalExpense::class, 'account_id');
    }

    public function isExpenseAccount()
    {
        return $this->account_type === 'expense';
    }

    public function isSavingAccount()
    {
        return $this->account_type === 'saving';
    }
}
