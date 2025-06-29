@extends('layouts.app')

@section('content')
    <div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row">                    <div class="col">
                        <h1 class="text-white mb-0">Gestion des Catégories</h1>
                        <p class="text-white">Gérez les catégories de produits dans votre inventaire</p>
                    </div>
                    <div class="col text-right">
                        @if(Auth::user()->role === 'admin' || Auth::user()->role === 'super_admin')
                        <a href="{{ route('categories.create') }}" class="btn btn-white">Nouvelle Catégorie</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-header border-0">                        <div class="row align-items-center">
                            <div class="col-6">
                                <h3 class="mb-0">Liste des Catégories</h3>
                            </div>
                            <div class="col-6 text-right">
                                <!-- Export Dropdown -->
                                <div class="btn-group mr-2" role="group">
                                    <button type="button" class="btn btn-sm btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-download"></i> Exporter
                                    </button>
                                    <div class="dropdown-menu">
                                        <h6 class="dropdown-header">Page Actuelle</h6>
                                        <a class="dropdown-item" href="{{ route('categories.export.excel', array_merge(request()->query(), ['scope' => 'current'])) }}">
                                            <i class="fas fa-file-excel text-success"></i> Excel (.xlsx)
                                        </a>
                                        <a class="dropdown-item" href="{{ route('categories.export.pdf', array_merge(request()->query(), ['scope' => 'current'])) }}">
                                            <i class="fas fa-file-pdf text-danger"></i> PDF
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <h6 class="dropdown-header">Toutes les Données</h6>
                                        <a class="dropdown-item" href="{{ route('categories.export.excel', array_merge(request()->query(), ['scope' => 'all'])) }}">
                                            <i class="fas fa-file-excel text-success"></i> Excel Complet
                                        </a>
                                        <a class="dropdown-item" href="{{ route('categories.export.pdf', array_merge(request()->query(), ['scope' => 'all'])) }}">
                                            <i class="fas fa-file-pdf text-danger"></i> PDF Complet
                                        </a>
                                    </div>
                                </div>
                                
                                <!-- Filter Button -->
                                <button class="btn btn-sm {{ isset($search) || (isset($sort) && $sort !== 'nom_asc') ? 'btn-danger' : 'btn-primary' }}" type="button" data-toggle="collapse" data-target="#searchCollapse" aria-expanded="false" aria-controls="searchCollapse">
                                    <i class="fas fa-filter"></i> Filtres
                                    @php 
                                        $filterCount = (isset($search) ? 1 : 0) + ((isset($sort) && $sort !== 'nom_asc') ? 1 : 0);
                                    @endphp
                                    @if($filterCount > 0)
                                        <span class="filter-badge">{{ $filterCount }}</span>
                                    @endif
                                </button>
                            </div>
                        </div>
                        
                        <div class="collapse mt-3 filter-collapse" id="searchCollapse">
                            <form action="{{ route('categories.index') }}" method="GET">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="search">Recherche</label>
                                            <input type="text" class="form-control form-control-sm" id="search" name="search" placeholder="Nom de catégorie" value="{{ $search ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="sort">Trier par</label>
                                            <select class="form-control form-control-sm filter-select" id="sort" name="sort">
                                                <option value="nom_asc" {{ isset($sort) && $sort == 'nom_asc' ? 'selected' : '' }}>Nom (A-Z)</option>
                                                <option value="nom_desc" {{ isset($sort) && $sort == 'nom_desc' ? 'selected' : '' }}>Nom (Z-A)</option>
                                                <option value="produits_count" {{ isset($sort) && $sort == 'produits_count' ? 'selected' : '' }}>Nombre de produits</option>
                                                <option value="recent" {{ isset($sort) && $sort == 'recent' ? 'selected' : '' }}>Plus récentes</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2 align-self-end filter-actions">
                                        <button type="submit" class="btn btn-primary btn-sm">Appliquer</button>
                                        <a href="{{ route('categories.index') }}" class="btn btn-secondary btn-sm btn-reset-filters">Réinitialiser</a>
                                    </div>
                                </div>
                            </form>
                            </div>
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
                                    <th scope="col">ID</th>
                                    <th scope="col">Nom</th>
                                    <th scope="col">Nb. Produits</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categories as $categorie)
                                <tr>
                                    <td>{{ $categorie->id }}</td>
                                    <td>{{ $categorie->nom }}</td>
                                    <td>{{ $categorie->produits->count() }}</td>                                    <td>
                                        <div class="dropdown">
                                            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">                                                @if(Auth::user()->role === 'admin' || Auth::user()->role === 'super_admin')
                                                <a class="dropdown-item" href="{{ route('categories.edit', ['categorie' => $categorie->id]) }}">Modifier</a>
                                                @endif
                                                @if(Auth::user()->role === 'super_admin')
                                                <button type="button" class="dropdown-item text-danger" data-toggle="modal" data-target="#deleteModal{{ $categorie->id }}">
                                                    Supprimer
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
                            {{ $categories->links() }}
                        </nav>
                    </div>
                </div>
            </div>
        </div>        
        @include('layouts.footers.auth')
    </div>

@foreach($categories as $categorie)
<!-- Delete Modal -->
<div class="modal fade" id="deleteModal{{ $categorie->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel{{ $categorie->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel{{ $categorie->id }}">Confirmer la suppression</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Êtes-vous sûr de vouloir supprimer la catégorie "<strong>{{ $categorie->nom }}</strong>" ?
                <br><br>
                <small class="text-muted">Cette action est irréversible.</small>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>                <form action="{{ route('categories.destroy', ['categorie' => $categorie->id]) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection
