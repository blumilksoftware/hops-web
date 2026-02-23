<?php

declare(strict_types=1);

namespace HopsWeb\Http\Controllers\Auth;

use HopsWeb\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route("dashboard", absolute: false));
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with("status", "verification-link-sent");
    }
}
