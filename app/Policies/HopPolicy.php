<?php

declare(strict_types=1);

namespace HopsWeb\Policies;

use HopsWeb\Models\Hop;
use HopsWeb\Models\User;

class HopPolicy
{
    public function viewAny(User $user): bool
    {
        return $this->manage($user);
    }

    public function view(User $user, Hop $model): bool
    {
        return $this->manage($user);
    }

    public function store(User $user): bool
    {
        return $this->manage($user);
    }

    public function update(User $user, Hop $model): bool
    {
        return $this->manage($user);
    }

    public function delete(User $user, Hop $model): bool
    {
        return $this->manage($user);
    }

    public function manage(User $user): bool
    {
        return $user->is_admin;
    }
}
