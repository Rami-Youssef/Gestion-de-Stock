<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuperAdminMiddleware
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

        // Check if user has super_admin role
        $userRole = Auth::user()->role;
        
        // Only allow super_admin users
        if ($userRole === 'super_admin') {
            return $next($request);
        }
        
        // If not a super_admin, redirect with error
        return redirect()->route('dashboard')
            ->with('error', 'Vous n\'avez pas les droits nécessaires pour accéder à cette page. Seuls les Super Administrateurs peuvent y accéder.');
    }
}
