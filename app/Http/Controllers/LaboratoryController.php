<?php

declare(strict_types=1);

namespace HopsWeb\Http\Controllers;

use HopsWeb\Models\AgendaResult;
use HopsWeb\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LaboratoryController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $agendas = $user->agendas()
            ->with(["user", "results"])
            ->latest()
            ->paginate(10);

        $lastActivityResult = AgendaResult::whereHas("agenda", function ($query) use ($user): void {
            $query->where("user_id", $user->id);
        })->latest()->first();

        $lastAgendaCreated = $user->agendas()->latest()->first();

        $lastActivity = null;

        if ($lastActivityResult && $lastAgendaCreated) {
            $lastActivity = $lastActivityResult->created_at->gt($lastAgendaCreated->created_at) 
                ? $lastActivityResult->created_at 
                : $lastAgendaCreated->created_at;
        } else {
            $lastActivity = $lastActivityResult?->created_at ?? $lastAgendaCreated?->created_at;
        }

        $stats = [
            "total_agendas" => $user->agendas()->count(),
            "total_runs" => AgendaResult::whereHas("agenda", function ($query) use ($user): void {
                $query->where("user_id", $user->id);
            })->count(),
            "active_researchers" => User::whereHas("agendas")->count(),
            "last_activity" => $lastActivity,
        ];

        return view("laboratory.dashboard", [
            "agendas" => $agendas,
            "stats" => $stats,
        ]);
    }
}
