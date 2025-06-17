@extends('layouts.app')

@section('content')
    <div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row">
                    <div class="col">
                        <h1 class="text-white mb-0">Gestion des Utilisateurs</h1>
                        <p class="text-white">Gérez les utilisateurs de votre système</p>
                    </div>
                    @if(Auth::user()->role === 'admin')
                    <div class="col text-right">
                        <a href="{{ route('user.create') }}" class="btn btn-white">Nouvel Utilisateur</a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
<div class="container-fluid mt--7">
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">Liste des Utilisateurs</h3>
                            </div>
                            <div class="col-4 text-right">
                                <button class="btn btn-sm {{ isset($search) || isset($role) ? 'btn-danger' : 'btn-primary' }}" type="button" data-toggle="collapse" data-target="#searchCollapse" aria-expanded="false" aria-controls="searchCollapse">
                                    <i class="fas fa-filter"></i> Filtres
                                    @if(isset($search) || isset($role))
                                        <span class="filter-badge">{{ isset($search) && isset($role) ? '2' : '1' }}</span>
                                    @endif
                                </button>
                            </div>
                        </div>
                        
                        <div class="collapse mt-3 filter-collapse" id="searchCollapse">
                            <form action="{{ route('user.index') }}" method="GET">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="search">Recherche</label>
                                            <input type="text" class="form-control form-control-sm" id="search" name="search" placeholder="Nom ou email" value="{{ $search ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="role">Rôle</label>
                                            <select class="form-control form-control-sm filter-select" id="role" name="role">
                                                <option value="">Tous les rôles</option>
                                                @foreach($roles as $r)
                                                    <option value="{{ $r }}" {{ isset($role) && $role == $r ? 'selected' : '' }}>{{ ucfirst($r) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2 align-self-end filter-actions">
                                        <button type="submit" class="btn btn-primary btn-sm">Rechercher</button>
                                        <a href="{{ route('user.index') }}" class="btn btn-secondary btn-sm btn-reset-filters">Réinitialiser</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    @if(session('success'))
                        <div class="alert alert-success mx-4">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger mx-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">Nom</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Rôle</th>
                                    <th scope="col">Date de création</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->utilisateur }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->role }}</td>
                                    <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                    <td class="text-right">
                                        <div class="dropdown">
                                            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                @if(Auth::user()->role === 'admin')
                                                <a class="dropdown-item" href="{{ route('user.edit', $user) }}">Modifier</a>
                                                @endif
                                                <a class="dropdown-item" href="{{ route('user.show', $user) }}">Voir le profil</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer py-4">
                        <nav class="d-flex justify-content-center" aria-label="...">
                            {{ $users->links() }}
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        
        @include('layouts.footers.auth')
    </div>
@endsection
