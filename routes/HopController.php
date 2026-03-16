<?php

declare(strict_types=1);

namespace HopsWeb\Http\Controllers;

use HopsWeb\Models\Hop;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HopController extends Controller
{
    public function index(Request $request): View
    {
        $query = Hop::query();

        $aromas = ["citrusy", "fruity", "floral", "herbal", "spicy", "resinous", "sugarlike", "misc"];

        foreach ($aromas as $aroma) {
            $query->when($request->boolean("aroma_$aroma"), function (Builder $q) use ($aroma): void {
                $q->where("aroma_$aroma", ">", 0);
            });
        }

        $query->when($request->string("bitterness"), fn(Builder $q, string $v) => $q->where("bitterness", $v))
            ->when($request->string("aromaticity"), fn(Builder $q, string $v) => $q->where("aromaticity", $v));

        foreach (Hop::RANGE_FIELDS as $field) {
            $query->when($request->input("{$field}_min"), function (Builder $q, $min) use ($field): void {
                $q->where("{$field}_max", ">=", $min);
            })->when($request->input("{$field}_max"), function (Builder $q, $max) use ($field): void {
                $q->where("{$field}_min", "<=", $max);
            });
        }

        return view("hops.index", [
            "hops" => $query->latest()->paginate(12)->withQueryString(),
        ]);
    }

    public function show(Hop $hop): View
    {
        return view("hops.show", compact("hop"));
    }
}
