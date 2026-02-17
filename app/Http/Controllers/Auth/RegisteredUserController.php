<?php

declare(strict_types=1);

namespace HopsWeb\Http\Controllers\Auth;

use HopsWeb\Http\Controllers\Controller;
use HopsWeb\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view("auth.register");
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            "name" => ["required", "string", "max:255"],
            "email" => ["required", "string", "lowercase", "email", "max:255", Rule::unique(User::class)],
            "password" => ["required", "confirmed", Rules\Password::defaults()],
        ]);

        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route("dashboard", absolute: false));
    }
}
