<?php

namespace App\Policies;

use App\Models\User;
use App\Models\PersonalIncome;

class PersonalIncomePolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PersonalIncome $income): bool
    {
        return $user->id === $income->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PersonalIncome $income): bool
    {
        return $user->id === $income->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PersonalIncome $income): bool
    {
        return $user->id === $income->user_id;
    }
}
