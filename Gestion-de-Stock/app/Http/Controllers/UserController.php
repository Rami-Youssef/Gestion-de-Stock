<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\Traits\ExportableTrait;
use App\Exports\UsersExport;

class UserController extends Controller
{
    use ExportableTrait;

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
        });
    }

    /**
     * Display a listing of the users
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $role = $request->input('role');
        $sort = $request->input('sort', 'nom_asc'); // Default sort by name
        
        $query = Utilisateur::query();
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('utilisateur', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }
        
        if ($role) {
            $query->where('role', $role);
        }
        
        // Apply sorting
        switch ($sort) {
            case 'email_asc':
                $query->orderBy('email', 'asc');
                break;
            case 'email_desc':
                $query->orderBy('email', 'desc');
                break;
            case 'role_asc':
                $query->orderBy('role', 'asc');
                break;
            case 'role_desc':
                $query->orderBy('role', 'desc');
                break;
            case 'recent':
                $query->orderBy('created_at', 'desc');
                break;
            case 'ancien':
                $query->orderBy('created_at', 'asc');
                break;
            case 'nom_desc':
                $query->orderBy('utilisateur', 'desc');
                break;
            default: // nom_asc
                $query->orderBy('utilisateur', 'asc');
                break;
        }
        
        // Apply pagination and keep query string for filters
        $users = $query->paginate(10);
        
        $roles = Utilisateur::select('role')->distinct()->pluck('role');
        
        return view('users.index', compact('users', 'search', 'role', 'roles', 'sort'));
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
    
    public function exportExcel(Request $request)
    {
        return $this->handleExcelExport($request, UsersExport::class, 'Utilisateurs');
    }

    public function exportPdf(Request $request)
    {
        return $this->handlePdfExport($request, 'exports.users-pdf', 'Utilisateurs', function($request, $allData) {
            $query = Utilisateur::query();

            // Apply search filter
            if ($request->has('search') && !empty($request->search)) {
                $query->where(function($q) use ($request) {
                    $q->where('nom', 'like', '%' . $request->search . '%')
                      ->orWhere('prenom', 'like', '%' . $request->search . '%')
                      ->orWhere('email', 'like', '%' . $request->search . '%');
                });
            }

            // Apply role filter
            if ($request->has('role') && !empty($request->role)) {
                $query->where('role', $request->role);
            }

            // Apply sorting
            $sort = $request->get('sort', 'nom_asc');
            switch ($sort) {
                case 'nom_desc':
                    $query->orderBy('nom', 'desc');
                    break;
                case 'prenom_asc':
                    $query->orderBy('prenom', 'asc');
                    break;
                case 'prenom_desc':
                    $query->orderBy('prenom', 'desc');
                    break;
                case 'email_asc':
                    $query->orderBy('email', 'asc');
                    break;
                case 'email_desc':
                    $query->orderBy('email', 'desc');
                    break;
                case 'role_asc':
                    $query->orderBy('role', 'asc');
                    break;
                case 'role_desc':
                    $query->orderBy('role', 'desc');
                    break;
                case 'recent':
                    $query->orderBy('created_at', 'desc');
                    break;
                default: // nom_asc
                    $query->orderBy('nom', 'asc');
                    break;
            }

            if ($allData) {
                $users = $query->get();
            } else {
                $users = $query->paginate(15)->items();
            }

            return [
                'users' => $users,
                'search' => $request->search,
                'role' => $request->role,
                'sort' => $sort,
            ];
        });
    }
}
