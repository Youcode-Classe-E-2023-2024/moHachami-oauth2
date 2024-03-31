<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\RoleController;
class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        // Check if the authenticated user has the admin role
        if ($request->user() &&  RoleController::userHasRole($request->user()->id,'admin')) {
            // User has admin role, allow the request to proceed
            return $next($request);
        }

        // User does not have admin role, return forbidden response
        return response()->json(['error' => 'Forbidden'], 403);
    }
}
