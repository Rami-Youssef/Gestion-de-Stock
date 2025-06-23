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
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">Historique des Mouvements</h3>
                            </div>                            <div class="col-4 text-right">
                                <button class="btn btn-sm {{ isset($search) || isset($type) || (isset($sort) && $sort !== 'date_desc') ? 'btn-danger' : 'btn-primary' }}" type="button" data-toggle="collapse" data-target="#searchCollapse" aria-expanded="false" aria-controls="searchCollapse">
                                    <i class="fas fa-filter"></i> Filtres
                                    @php 
                                        $filterCount = (isset($search) ? 1 : 0) + (isset($type) ? 1 : 0) + ((isset($sort) && $sort !== 'date_desc') ? 1 : 0);
                                    @endphp
                                    @if($filterCount > 0)
                                        <span class="filter-badge">{{ $filterCount }}</span>
                                    @endif
                                </button>
                            </div>
                        </div>
                          <div class="collapse mt-3 filter-collapse" id="searchCollapse">
                            <form action="{{ route('mouvements.index') }}" method="GET">                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="search">Produit</label>
                                            <input type="text" class="form-control form-control-sm" id="search" name="search" placeholder="Nom du produit" value="{{ $search ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="type">Type de mouvement</label>
                                            <select class="form-control form-control-sm filter-select" id="type" name="type">
                                                <option value="">Tous les types</option>
                                                <option value="entrée" {{ isset($type) && $type == 'entrée' ? 'selected' : '' }}>Entrée</option>
                                                <option value="sortie" {{ isset($type) && $type == 'sortie' ? 'selected' : '' }}>Sortie</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="sort">Trier par</label>
                                            <select class="form-control form-control-sm filter-select" id="sort" name="sort">
                                                <option value="date_desc" {{ isset($sort) && $sort == 'date_desc' ? 'selected' : '' }}>Date (récente d'abord)</option>
                                                <option value="date_asc" {{ isset($sort) && $sort == 'date_asc' ? 'selected' : '' }}>Date (ancienne d'abord)</option>
                                                <option value="produit_asc" {{ isset($sort) && $sort == 'produit_asc' ? 'selected' : '' }}>Produit (A-Z)</option>
                                                <option value="produit_desc" {{ isset($sort) && $sort == 'produit_desc' ? 'selected' : '' }}>Produit (Z-A)</option>
                                                <option value="quantite_desc" {{ isset($sort) && $sort == 'quantite_desc' ? 'selected' : '' }}>Quantité (élevée d'abord)</option>
                                                <option value="quantite_asc" {{ isset($sort) && $sort == 'quantite_asc' ? 'selected' : '' }}>Quantité (faible d'abord)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2 align-self-end filter-actions">
                                        <button type="submit" class="btn btn-primary btn-sm">Appliquer</button>
                                        <a href="{{ route('mouvements.index') }}" class="btn btn-secondary btn-sm btn-reset-filters">Réinitialiser</a>
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
                                            </a>                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                <a class="dropdown-item" href="{{ route('mouvements.show', $mouvement) }}">Voir</a>
                                                @if(!$mouvement->canceled && Auth::user()->role === 'admin')
                                                <button type="button" class="dropdown-item text-warning" data-toggle="modal" data-target="#cancelModal{{ $mouvement->id }}">
                                                    Annuler
                                                </button>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer py-4">
                        <nav class="d-flex justify-content-center" aria-label="...">
                            {{ $mouvements->links() }}
                        </nav>
                    </div>
                </div>
            </div>
        </div>        
        @include('layouts.footers.auth')
    </div>

@foreach($mouvements as $mouvement)
@if(!$mouvement->canceled && Auth::user()->role === 'admin')
<!-- Cancel Modal -->
<div class="modal fade" id="cancelModal{{ $mouvement->id }}" tabindex="-1" role="dialog" aria-labelledby="cancelModalLabel{{ $mouvement->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelModalLabel{{ $mouvement->id }}">Confirmer l'annulation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Êtes-vous sûr de vouloir annuler ce mouvement de stock ?
                <br><br>
                <strong>Produit:</strong> {{ $mouvement->produit->nom }}<br>
                <strong>Type:</strong> {{ ucfirst($mouvement->type) }}<br>
                <strong>Quantité:</strong> {{ $mouvement->quantite }}<br>
                <br>
                <small class="text-muted">Cette action marquera le mouvement comme annulé et ajustera les stocks en conséquence.</small>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <form action="{{ route('mouvements.cancel', $mouvement) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-warning">Annuler le mouvement</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
@endforeach
@endsection
