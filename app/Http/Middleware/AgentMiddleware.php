<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AgentMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->type == 'agent') {
            return $next($request);
        } else {
            return response()->json([
                'status' =>'error',
                'message' => 'Unauthorized'
            ], 401);
        }
    }
}
