<?php

declare(strict_types=1);

namespace HopsWeb\Http\Controllers;

use HopsWeb\Actions\CreateAgendaAction;
use HopsWeb\Http\Requests\CreateAgendaRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AgendaController extends Controller
{
    public function create(Request $request, LaboratoryController $laboratoryController): View
    {
        $data = $laboratoryController->getDashboardData($request->user(), $request);
        $data["activeTab"] = "create";

        return view("laboratory.index", $data);
    }

    public function store(CreateAgendaRequest $request, CreateAgendaAction $action): RedirectResponse
    {
        $action->execute($request);

        return redirect()->route("laboratory.index")->with("success", __("Agenda created successfully."));
    }
}
