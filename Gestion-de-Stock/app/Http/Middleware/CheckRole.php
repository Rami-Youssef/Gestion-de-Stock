<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        // Check if user is logged in
        if (!Auth::check()) {
            return redirect('login');
        }

        // Check if user has the required role
        $userRole = Auth::user()->role;

        // If admin, allow access to all routes
        if ($userRole === 'admin') {
            return $next($request);
        }

        // Check if user has the required role
        if ($userRole === $role) {
            return $next($request);
        }

        // If not, redirect to dashboard with error
        return redirect()->route('dashboard')
            ->with('error', 'Vous n\'avez pas les droits nécessaires pour accéder à cette page');
    }
}
