<?php

declare(strict_types=1);

namespace HopsWeb\Http\Controllers\Admin;

use HopsWeb\Http\Controllers\Controller;
use HopsWeb\Models\HopQuery;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HopQueryController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request): View
    {
        $this->authorize("viewAny", HopQuery::class);

        $query = HopQuery::query()->with("user");

        if ($request->filled("user")) {
            $userSearch = $request->input("user");
            $query->whereHas("user", function (Builder $query) use ($userSearch): void {
                $query->where("name", "like", "%" . $userSearch . "%")->orWhere(
                    "email",
                    "like",
                    "%" . $userSearch . "%",
                );
            });
        }

        if ($request->filled("date_from")) {
            $query->whereDate("created_at", ">=", $request->input("date_from"));
        }

        if ($request->filled("date_to")) {
            $query->whereDate("created_at", "<=", $request->input("date_to"));
        }

        $hopQueries = $query->latest()->paginate(20)->withQueryString();

        return view("admin.hop-queries.index", compact("hopQueries"));
    }

    public function show(HopQuery $hopQuery): View
    {
        $this->authorize("view", $hopQuery);

        $hopQuery->load("user");

        return view("admin.hop-queries.show", compact("hopQuery"));
    }
}
