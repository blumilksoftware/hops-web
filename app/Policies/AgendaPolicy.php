<?php

declare(strict_types=1);

namespace HopsWeb\Policies;

use HopsWeb\Models\Agenda;
use HopsWeb\Models\User;

class AgendaPolicy
{
    public function addRun(User $user, Agenda $agenda): bool
    {
        return $user->is_team_member && $user->id === $agenda->user_id;
    }

    public function compare(User $user, Agenda $agenda): bool
    {
        return $user->is_team_member && $user->id === $agenda->user_id;
    }

    public function view(User $user, Agenda $agenda): bool
    {
        return $user->is_team_member && $user->id === $agenda->user_id;
    }
}
