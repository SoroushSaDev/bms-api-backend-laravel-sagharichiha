<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EmailVerifiedMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && !is_null(auth()->user()->email_verified_at)) {
            return $next($request);
        } else {
            return response()->json(['message' => 'Your email address is not verified.'], 409);
        }
    }
}
