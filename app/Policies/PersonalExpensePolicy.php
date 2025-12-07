<?php

namespace App\Policies;

use App\Models\User;
use App\Models\PersonalExpense;

class PersonalExpensePolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PersonalExpense $expense): bool
    {
        return $user->id === $expense->user_id;
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
    public function update(User $user, PersonalExpense $expense): bool
    {
        return $user->id === $expense->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PersonalExpense $expense): bool
    {
        return $user->id === $expense->user_id;
    }
}
