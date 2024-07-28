<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleManagement
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // dump('Auth::guard(\'sanctum\')->guest()', Auth::guard('sanctum')->guest());
        // dd(auth('sanctum')->user()->name);
        // This will work when Authorization is not developed and role is set for each user
        // if (Auth::guard('sanctum')->guest() || !in_array(auth('sanctum')->user()->role, $roles)) {
        //     return response()->json(['message' => 'Unauthorized'], 403);
        // }

        return $next($request);
    }
}
