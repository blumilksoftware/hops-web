<?php

declare(strict_types=1);

namespace HopsWeb\Http\Controllers;

use HopsWeb\Actions\CreateAgendaAction;
use HopsWeb\Http\Requests\CreateAgendaRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AgendaController extends Controller
{
    public function create(): View
    {
        return view("laboratory.agenda.create");
    }

    public function store(CreateAgendaRequest $request, CreateAgendaAction $action): RedirectResponse
    {
        $action->execute($request);

        return redirect()->route("laboratory.index")->with("success", __("Agenda created successfully."));
    }
}
