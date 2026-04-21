<?php

declare(strict_types=1);

namespace HopsWeb\Policies;

use HopsWeb\Models\Hop;
use HopsWeb\Models\User;

class HopPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->is_admin;
    }

    public function view(User $user, Hop $model): bool
    {
        return $user->is_admin;
    }

    public function store(User $user): bool
    {
        return $user->is_admin;
    }

    public function update(User $user, Hop $model): bool
    {
        return $user->is_admin;
    }

    public function delete(User $user, Hop $model): bool
    {
        return $user->is_admin;
    }
}
