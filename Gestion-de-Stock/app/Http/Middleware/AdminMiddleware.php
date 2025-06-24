<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // User must be authenticated
        if (!Auth::check()) {
            return redirect('login');
        }

        // Check if user has admin or super_admin role
        $userRole = Auth::user()->role;
        
        // Only allow admin or super_admin users
        if ($userRole === 'admin' || $userRole === 'super_admin') {
            return $next($request);
        }
        
        // If not an admin or super_admin, redirect with error
        return redirect()->route('dashboard')
            ->with('error', 'Vous n\'avez pas les droits nécessaires pour effectuer cette action. Seuls les Administrateurs peuvent modifier les données.');
    }
}
