<?php

declare(strict_types=1);

namespace HopsWeb\Actions;

use HopsWeb\Http\Requests\CreateAgendaRequest;
use HopsWeb\Models\Agenda;

class CreateAgendaAction
{
    public function execute(CreateAgendaRequest $request): Agenda
    {
        $data = $request->getData();

        $query = $data["query_json"] !== null
            ? json_decode($data["query_json"], true)
            : [];

        return Agenda::query()->create([
            "user_id" => $request->user()->id,
            "name" => $data["name"],
            "query" => $query,
        ]);
    }
}
