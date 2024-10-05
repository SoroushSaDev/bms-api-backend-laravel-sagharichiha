<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PhoneNumberVerifiedMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && !is_null(auth()->user()->phone_number_verified_at)) {
            return $next($request);
        } else {
            return response()->json(['message' => 'Your phone number is not verified.'], 409);
        }
    }
}
