<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        // Check if the user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to access this page.');
        }

        // Check if the user has the required role or is an admin
        $userRole = Auth::user()->role;
        if ($userRole === $role || $userRole === 'admin') {
            return $next($request);
        }

        // Redirect based on the user's role
        return redirect()->route($userRole === 'admin' ? 'dashboard' : 'kitchen-dashboard')
            ->with('error', 'You do not have permission to access this page.');
    }
}
