<?php

declare(strict_types=1);

namespace HopsWeb\Http\Controllers;

use HopsWeb\Models\AgendaResult;
use HopsWeb\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LaboratoryController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();

        $agendas = $user->agendas()
            ->with(["user", "results"])
            ->latest()
            ->paginate(10);

        $lastActivityResult = AgendaResult::query()->whereHas("agenda", fn($query) => $query->where("user_id", $user->id))->latest()->first();

        $lastAgendaCreated = $user->agendas()->latest()->first();

        $lastActivity = match (true) {
            $lastActivityResult !== null && $lastAgendaCreated !== null => $lastActivityResult->created_at->gt($lastAgendaCreated->created_at)
                ? $lastActivityResult->created_at
                : $lastAgendaCreated->created_at,
            $lastActivityResult !== null => $lastActivityResult->created_at,
            $lastAgendaCreated !== null => $lastAgendaCreated->created_at,
            default => null,
        };

        $stats = [
            "total_agendas" => $user->agendas()->count(),
            "total_runs" => AgendaResult::query()->whereHas("agenda", fn($query) => $query->where("user_id", $user->id))->count(),
            "active_researchers" => User::query()->whereHas("agendas")->count(),
            "last_activity" => $lastActivity,
        ];

        return view("laboratory.dashboard", [
            "agendas" => $agendas,
            "stats" => $stats,
        ]);
    }
}
