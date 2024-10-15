<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->type == 'admin') {
            $local = auth()->user()->Profile?->language ?? 'en';
            \App::setLocale($local);
            return $next($request);
        } else {
            return response()->json([
                'status' =>'error',
                'message' => 'Unauthorized'
            ], 401);
        }
    }
}
