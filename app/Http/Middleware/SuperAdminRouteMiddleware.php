<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminRouteMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is authenticated and has the super admin role
        if (!auth()->check() || auth()->user()->role_id !== 1) {
            // Redirect to the login page if not authenticated or does not have the role
            // return redirect()->route('login')->with('error', 'You do not have access to this section.');
            abort(403, 'Unauthorized action.'); // Optionally, you can use abort to return a 403 Forbidden response
        }
        return $next($request);
    }
}
