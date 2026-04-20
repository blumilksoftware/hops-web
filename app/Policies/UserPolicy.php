<?php

declare(strict_types=1);

namespace HopsWeb\Policies;

use HopsWeb\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->is_admin;
    }

    public function store(User $user): bool
    {
        return $user->is_admin;
    }

    public function update(User $user, User $model): bool
    {
        return $user->is_admin;
    }

    public function delete(User $user, User $model): bool
    {
        return $user->is_admin && $user->id !== $model->id;
    }
}
