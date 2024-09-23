<?php

namespace App\Policies;

use App\Models\Expense;
use App\Models\User;

class ExpensePolicy
{
    public function index(User $user)
    {
        return true;
    }

    public function store(User $user)
    {
        return true;
    }

    public function show(User $user, Expense $expense)
    {
        return $expense->user_id === $user->id;
    }

    public function update(User $user, Expense $expense)
    {
        return $expense->user_id === $user->id;
    }

    public function destroy(User $user, Expense $expense)
    {
        return $expense->user_id === $user->id;
    }

}
