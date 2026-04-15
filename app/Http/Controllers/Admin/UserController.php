<?php

declare(strict_types=1);

namespace HopsWeb\Http\Controllers\Admin;

use HopsWeb\Http\Controllers\Controller;
use HopsWeb\Http\Requests\Admin\UpdateUserRequest;
use HopsWeb\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request): View
    {
        $this->authorize("viewAny", User::class);

        $query = User::query();

        if ($request->filled("search")) {
            $search = $request->input("search");
            $query->where(function ($query) use ($search): void {
                $query->where("name", "like", "%" . $search . "%")
                    ->orWhere("email", "like", "%" . $search . "%");
            });
        }

        $users = $query->latest()->paginate(20)->withQueryString();

        return view("admin.users.index", compact("users"));
    }

    public function edit(User $user): View
    {
        $this->authorize("update", $user);

        return view("admin.users.edit", compact("user"));
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $user->update($request->validated());

        return redirect()->route("admin.users.index");
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->authorize("delete", $user);

        $user->delete();

        return redirect()->route("admin.users.index");
    }
}
