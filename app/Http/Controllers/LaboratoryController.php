<?php

declare(strict_types=1);

namespace HopsWeb\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class LaboratoryController extends Controller
{
    public function __invoke(Request $request): View
    {
        $agendas = $request->user()
            ->agendas()
            ->latest()
            ->paginate(10);

        return view("laboratory.index", [
            "agendas" => $agendas,
        ]);
    }
}
