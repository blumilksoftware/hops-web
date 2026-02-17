<?php

declare(strict_types=1);

namespace HopsWeb\Http\Controllers\Auth;

use HopsWeb\Http\Controllers\Controller;
use HopsWeb\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view("auth.login");
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route("dashboard", absolute: false));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard("web")->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect("/");
    }
}
