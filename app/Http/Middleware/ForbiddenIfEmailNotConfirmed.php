<?php

namespace App\Http\Middleware;

use Closure;

class ForbiddenIfEmailNotConfirmed
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! $request->user()->confirmed) {
            return response()->json(['flash' => 'You must confirm your email address.'], 403);
        }

        return $next($request);
    }
}
