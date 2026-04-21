<?php

declare(strict_types=1);

namespace HopsWeb\Http\Controllers\Admin;

use HopsWeb\Http\Controllers\Controller;
use HopsWeb\Http\Requests\Admin\CreateHopRequest;
use HopsWeb\Http\Requests\Admin\UpdateHopRequest;
use HopsWeb\Models\Hop;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HopController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request): View
    {
        $this->authorize("viewAny", Hop::class);

        $query = Hop::query();

        if ($request->filled("search")) {
            $search = $request->input("search");
            $query->where(function ($query) use ($search): void {
                $query->where("name", "like", "%" . $search . "%")
                    ->orWhere("slug", "like", "%" . $search . "%")
                    ->orWhere("country", "like", "%" . $search . "%");
            });
        }

        $hops = $query->orderBy("name")->paginate(20)->withQueryString();

        return view("admin.hops.index", compact("hops"));
    }

    public function create(): View
    {
        $this->authorize("store", Hop::class);

        return view("admin.hops.create");
    }

    public function store(CreateHopRequest $request): RedirectResponse
    {
        $this->authorize("store", Hop::class);

        $data = $request->validated();

        $hop = new Hop();
        $hop->fill($this->extractBasicFields($data));

        foreach (Hop::RANGE_FIELDS as $field) {
            $hop->setAttribute("{$field}_min", $data["{$field}_min"] ?? null);
            $hop->setAttribute("{$field}_max", $data["{$field}_max"] ?? null);
        }

        $hop->save();

        return redirect()->route("admin.hops.index");
    }

    public function edit(Hop $hop): View
    {
        $this->authorize("update", $hop);

        return view("admin.hops.edit", compact("hop"));
    }

    public function update(UpdateHopRequest $request, Hop $hop): RedirectResponse
    {
        $data = $request->validated();

        $hop->fill($this->extractBasicFields($data));

        foreach (Hop::RANGE_FIELDS as $field) {
            $hop->setAttribute("{$field}_min", $data["{$field}_min"] ?? null);
            $hop->setAttribute("{$field}_max", $data["{$field}_max"] ?? null);
        }

        $hop->save();

        return redirect()->route("admin.hops.index");
    }

    public function destroy(Hop $hop): RedirectResponse
    {
        $this->authorize("delete", $hop);

        $hop->delete();

        return redirect()->route("admin.hops.index");
    }

    private function extractBasicFields(array $data): array
    {
        $rangeKeys = [];

        foreach (Hop::RANGE_FIELDS as $field) {
            $rangeKeys[] = "{$field}_min";
            $rangeKeys[] = "{$field}_max";
        }

        return array_diff_key($data, array_flip($rangeKeys));
    }
}
