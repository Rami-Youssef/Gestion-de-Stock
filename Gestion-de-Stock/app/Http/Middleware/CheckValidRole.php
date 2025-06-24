<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckValidRole
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

        // Check if user has a valid role
        $userRole = Auth::user()->role;
        
        // Only allow users with one of the three valid roles
        if (in_array($userRole, ['user', 'admin', 'super_admin'])) {
            return $next($request);
        }
        
        // If not a valid role, log out and redirect with error
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('login')
            ->with('error', 'Votre compte n\'a pas un rôle valide. Veuillez contacter un administrateur.');
    }
}
