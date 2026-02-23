<?php

declare(strict_types=1);

namespace HopsWeb\Http\Controllers\Auth;

use HopsWeb\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    public function __invoke(Request $request): RedirectResponse|View
    {
        return $request->user()->hasVerifiedEmail()
                    ? redirect()->intended(route("dashboard", absolute: false))
                    : view("auth.verify-email");
    }
}
