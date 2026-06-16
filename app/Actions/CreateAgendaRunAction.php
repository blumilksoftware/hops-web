<?php

declare(strict_types=1);

namespace HopsWeb\Actions;

use HopsWeb\Http\Requests\CreateAgendaRunRequest;
use HopsWeb\Models\Agenda;
use HopsWeb\Models\AgendaResult;

class CreateAgendaRunAction
{
    public function execute(Agenda $agenda, CreateAgendaRunRequest $request): AgendaResult
    {
        $data = $request->getData();

        return AgendaResult::query()->create([
            "agenda_id" => $agenda->id,
            "parameters" => [
                "module_weights" => $data["module_weights"],
                "biochemical_weights" => $data["biochemical_weights"],
            ],
        ]);
    }
}
