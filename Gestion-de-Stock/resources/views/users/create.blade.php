@extends('layouts.app')

@section('content')
    <div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row">
                    <div class="col">
                        <h1 class="text-white mb-0">Nouvel Utilisateur</h1>
                        <p class="text-white">Créez un nouvel utilisateur du système</p>
                    </div>
                    <div class="col text-right">
                        <a href="{{ route('user.index') }}" class="btn btn-white">Retour à la liste</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-12">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <h3 class="mb-0">Détails de l'utilisateur</h3>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('user.store') }}" autocomplete="off">
                            @csrf
                            
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="form-group">
                                <label for="utilisateur" class="form-control-label">Nom d'utilisateur</label>
                                <input type="text" name="utilisateur" id="utilisateur" class="form-control" value="{{ old('utilisateur') }}" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="email" class="form-control-label">Adresse email</label>
                                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="password" class="form-control-label">Mot de passe</label>
                                <input type="password" name="password" id="password" class="form-control" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="password_confirmation" class="form-control-label">Confirmation du mot de passe</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                            </div>                            <div class="form-group">
                                <label for="role" class="form-control-label">Rôle</label>
                                <select name="role" id="role" class="form-control" required>
                                    <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>Utilisateur</option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrateur</option>
                                    <option value="super_admin" {{ old('role') == 'super_admin' ? 'selected' : '' }}>Super Administrateur</option>
                                </select>
                            </div>
                            
                            <div class="text-center">
                                <button type="submit" class="btn btn-success mt-4">Créer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        @include('layouts.footers.auth')
    </div>
@endsection
