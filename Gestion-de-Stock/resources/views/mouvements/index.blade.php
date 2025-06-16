@extends('layouts.app')

@section('content')
    <div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row">
                    <div class="col">
                        <h1 class="text-white mb-0">Mouvements de Stock</h1>
                        <p class="text-white">Suivez les entrées et sorties de stock</p>
                    </div>
                    <div class="col text-right">
                        <a href="{{ route('mouvements.create') }}" class="btn btn-white">Nouveau Mouvement</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <h3 class="mb-0">Historique des Mouvements</h3>
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
                                    <th scope="col">Date</th>
                                    <th scope="col">Produit</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Quantité</th>
                                    <th scope="col">Utilisateur</th>
                                    <th scope="col">État</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($mouvements as $mouvement)
                                <tr class="{{ $mouvement->canceled ? 'text-muted bg-light' : '' }}">
                                    <td>{{ $mouvement->date_cmd->format('d/m/Y H:i') }}</td>
                                    <td>{{ $mouvement->produit->nom }} <small class="text-muted">({{ $mouvement->produit->reference }})</small></td>
                                    <td>
                                        @if ($mouvement->type === 'entrée')
                                            <span class="badge badge-success">Entrée</span>
                                        @else
                                            <span class="badge badge-danger">Sortie</span>
                                        @endif
                                    </td>
                                    <td>{{ $mouvement->quantite }}</td>
                                    <td>{{ $mouvement->utilisateur->utilisateur }}</td>
                                    <td>
                                        @if ($mouvement->canceled)
                                            <span class="badge badge-secondary">Annulé</span>
                                        @else
                                            <span class="badge badge-primary">Validé</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                <a class="dropdown-item" href="{{ route('mouvements.show', $mouvement) }}">Voir</a>
                                                @if(!$mouvement->canceled && Auth::user()->role === 'admin')
                                                <form action="{{ route('mouvements.cancel', $mouvement) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item" onclick="return confirm('Êtes-vous sûr de vouloir annuler ce mouvement?')">Annuler</button>
                                                </form>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        @include('layouts.footers.auth')
    </div>
@endsection
