<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InstanceAdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is authenticated and has the instance admin role
        if (!auth()->check() || in_array(auth()->user()->role_id, [1, 2, 4]) === false) {
            // Redirect to the login page if not authenticated or does not have the role
            // return redirect()->route('login')->with('error', 'You do not have access to this section.');
            abort(403, 'Unauthorized action.'); // Optionally, you can use abort to return a 403 Forbidden response
        }
        return $next($request);
    }
}
