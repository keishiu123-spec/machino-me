<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAmbassador
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! in_array($user->role, ['ambassador', 'admin'])) {
            abort(403, 'アンバサダー権限が必要です');
        }

        return $next($request);
    }
}
