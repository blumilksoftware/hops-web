<?php

declare(strict_types=1);

namespace HopsWeb\Http\Controllers;

use HopsWeb\Actions\CreateAgendaRunAction;
use HopsWeb\Http\Requests\CreateAgendaRunRequest;
use HopsWeb\Models\Agenda;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AgendaRunController extends Controller
{
    use AuthorizesRequests;

    public function create(Agenda $agenda): View
    {
        $this->authorize("addRun", $agenda);

        return view("laboratory.agenda.run.create", [
            "agenda" => $agenda,
        ]);
    }

    public function store(CreateAgendaRunRequest $request, Agenda $agenda, CreateAgendaRunAction $action): RedirectResponse
    {
        $this->authorize("addRun", $agenda);
        $action->execute($agenda, $request);

        return redirect()->route("laboratory.index")->with("success", __("Parameter set saved successfully."));
    }
}
