<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class checkAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the user has access via session
        if (!session('has_access')) {
            // Redirect to the landing page with an error message if access is denied
            return redirect('/')->withErrors('You need a valid code to access this page.');
        }

        return $next($request);
    }
}
