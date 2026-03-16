<?php

declare(strict_types=1);

namespace HopsWeb\Http\Controllers;

use HopsWeb\Models\Hop;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HopController extends Controller
{
    public function index(Request $request): View
    {
        $hops = Hop::query()
            ->filter($request->all())
            ->orderBy("name")
            ->paginate(12)
            ->withQueryString();

        return view("hops.index", [
            "hops" => $hops,
            "filters" => $request->all(),
        ]);
    }

    public function show(Hop $hop): View
    {
        return view("hops.show", [
            "hop" => $hop,
        ]);
    }
}
