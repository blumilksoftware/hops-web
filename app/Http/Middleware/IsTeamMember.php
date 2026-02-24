<?php

declare(strict_types=1);

namespace HopsWeb\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsTeamMember
{
    public function handle(Request $request, Closure $next): Response
    {
        abort_if(!$request->user()?->is_team_member, 403);

        return $next($request);
    }
}
