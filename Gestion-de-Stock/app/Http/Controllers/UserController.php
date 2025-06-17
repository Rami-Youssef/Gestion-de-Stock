<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (auth()->user()->role !== 'admin') {
                abort(403, 'Accès non autorisé.');
            }
            return $next($request);
        })->except(['index', 'show']);
    }

    /**
     * Display a listing of the users
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $users = Utilisateur::paginate(10);
        return view('users.index', compact('users'));
    }
    
    /**
     * Show the user profile
     *
     * @param  \App\Models\Utilisateur  $user
     * @return \Illuminate\View\View
     */
    public function show(Utilisateur $user)
    {
        return view('users.show', compact('user'));
    }
    
    /**
     * Show the form for creating a new user.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('users.create');
    }
    
    /**
     * Store a newly created user in storage.
     *
     * @param  \App\Http\Requests\UserRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(UserRequest $request)
    {
        $user = Utilisateur::create([
            'utilisateur' => $request->utilisateur,
            'email' => $request->email,
            'motdepasse' => bcrypt($request->password),
            'role' => $request->role
        ]);
        
        return redirect()->route('user.index')
            ->with('success', 'Utilisateur créé avec succès.');
    }
    
    /**
     * Show the form for editing the specified user.
     *
     * @param  \App\Models\Utilisateur  $user
     * @return \Illuminate\View\View
     */
    public function edit(Utilisateur $user)
    {
        return view('users.edit', compact('user'));
    }
    
    /**
     * Update the specified user in storage.
     *
     * @param  \App\Http\Requests\UserRequest  $request
     * @param  \App\Models\Utilisateur  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UserRequest $request, Utilisateur $user)
    {
        $data = [
            'utilisateur' => $request->utilisateur,
            'email' => $request->email,
            'role' => $request->role
        ];
        
        if ($request->filled('password')) {
            $data['motdepasse'] = bcrypt($request->password);
        }
        
        $user->update($data);
        
        return redirect()->route('user.index')
            ->with('success', 'Utilisateur mis à jour avec succès.');
    }
    
    /**
     * Remove the specified user from storage.
     *
     * @param  \App\Models\Utilisateur  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Utilisateur $user)
    {
        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return redirect()->route('user.index')
                ->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }
        
        // Check if the user has stock movements
        if ($user->mouvementStocks()->count() > 0) {
            return redirect()->route('user.index')
                ->with('error', 'Impossible de supprimer cet utilisateur car il a effectué des mouvements de stock.');
        }
        
        $user->delete();
        
        return redirect()->route('user.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }
}
