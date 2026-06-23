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
        $data = $this->getDashboardData($request->user(), $request);
        $data["activeTab"] = $request->query("tab", "dashboard");

        return view("laboratory.index", $data);
    }

    public function getDashboardData(User $user, ?Request $request = null): array
    {
        $sort = $request ? $request->query("sort", "created_at") : "created_at";
        $direction = $request ? $request->query("direction", "desc") : "desc";

        if (!in_array($sort, ["name", "created_at"], true)) {
            $sort = "created_at";
        }

        if (!in_array($direction, ["asc", "desc"], true)) {
            $direction = "desc";
        }

        $agendas = $user->agendas()
            ->with(["user", "results"])
            ->orderBy($sort, $direction)
            ->paginate(10)
            ->withQueryString();

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

        return [
            "agendas" => $agendas,
            "stats" => $stats,
            "sort" => $sort,
            "direction" => $direction,
        ];
    }
}
