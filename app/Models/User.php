<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'phone',
        'photo_url',
        'department',
        'position',
        'manager_id',
        'bio',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the time entries for the user.
     */
    public function timeEntries()
    {
        return $this->hasMany(TimeEntry::class);
    }

    /**
     * Get the activities for the user.
     */
    public function activities()
    {
        return $this->hasMany(Activity::class)->latest();
    }

    /**
     * Get the manager of this user.
     */
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    /**
     * Get the subordinates of this user.
     */
    public function subordinates()
    {
        return $this->hasMany(User::class, 'manager_id');
    }

    /**
     * Get the projects assigned to this user.
     */
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_team_member', 'user_id', 'team_member_id');
    }

    /**
     * Get total hours logged by user.
     */
    public function getTotalHoursAttribute()
    {
        return $this->timeEntries()->sum('duration_minutes') / 60;
    }

    /**
     * Get average hours per day.
     */
    public function getAverageHoursPerDayAttribute()
    {
        $entries = $this->timeEntries()->count();
        if ($entries === 0) return 0;
        return round($this->total_hours / $entries, 2);
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Get the personal accounts for the user.
     */
    public function personalAccounts()
    {
        return $this->hasMany(PersonalAccount::class);
    }

    /**
     * Get the personal incomes for the user.
     */
    public function personalIncomes()
    {
        return $this->hasMany(PersonalIncome::class);
    }

    /**
     * Get the personal expenses for the user.
     */
    public function personalExpenses()
    {
        return $this->hasMany(PersonalExpense::class);
    }

    /**
     * Get the personal loans for the user.
     */
    public function personalLoans()
    {
        return $this->hasMany(PersonalLoan::class);
    }

    /**
     * Get total income for the user.
     */
    public function getTotalIncomeAttribute()
    {
        return $this->personalIncomes()->sum('amount');
    }

    /**
     * Get total expenses for the user.
     */
    public function getTotalExpensesAttribute()
    {
        return $this->personalExpenses()->sum('amount');
    }

    /**
     * Get cash on hand (total income - total expenses).
     */
    public function getCashOnHandAttribute()
    {
        return $this->total_income - $this->total_expenses;
    }
}
