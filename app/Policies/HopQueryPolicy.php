<?php

declare(strict_types=1);

namespace HopsWeb\Policies;

use HopsWeb\Models\HopQuery;
use HopsWeb\Models\User;

class HopQueryPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->is_admin;
    }

    public function view(User $user, HopQuery $hopQuery): bool
    {
        return $user->is_admin;
    }
}
