@extends('layouts.app')

@section('content')
    <div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row">
                    <div class="col">
                        <h1 class="text-white mb-0">Gestion des Produits</h1>
                        <p class="text-white">Gérez les produits dans votre inventaire</p>
                    </div>
                    <div class="col text-right">
                        <a href="{{ route('produits.create') }}" class="btn btn-white">Nouveau Produit</a>
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
                                <h3 class="mb-0">Liste des Produits</h3>
                            </div>                            <div class="col-4 text-right">
                                <button class="btn btn-sm {{ isset($search) || isset($category) || (isset($sort) && $sort !== 'nom_asc') ? 'btn-danger' : 'btn-primary' }}" type="button" data-toggle="collapse" data-target="#searchCollapse" aria-expanded="false" aria-controls="searchCollapse">
                                    <i class="fas fa-filter"></i> Filtres
                                    @php 
                                        $filterCount = (isset($search) ? 1 : 0) + (isset($category) ? 1 : 0) + ((isset($sort) && $sort !== 'nom_asc') ? 1 : 0);
                                    @endphp
                                    @if($filterCount > 0)
                                        <span class="filter-badge">{{ $filterCount }}</span>
                                    @endif
                                </button>
                            </div>
                        </div>
                          <div class="collapse mt-3 filter-collapse" id="searchCollapse">                            <form action="{{ route('produits.index') }}" method="GET">
                                <div class="row">
                                    <div class="col-md-3">                                        
                                        <div class="form-group">
                                            <label for="search">Recherche</label>
                                            <input type="text" class="form-control form-control-sm" id="search" name="search" placeholder="Nom ou référence" value="{{ $search ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="category">Catégorie</label>
                                            <select class="form-control form-control-sm filter-select" id="category" name="category">
                                                <option value="">Toutes les catégories</option>
                                                @foreach($categories as $cat)
                                                    <option value="{{ $cat->id }}" {{ isset($category) && $category == $cat->id ? 'selected' : '' }}>{{ $cat->nom }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="sort">Trier par</label>
                                            <select class="form-control form-control-sm filter-select" id="sort" name="sort">
                                                <option value="nom_asc" {{ isset($sort) && $sort == 'nom_asc' ? 'selected' : '' }}>Nom (A-Z)</option>
                                                <option value="nom_desc" {{ isset($sort) && $sort == 'nom_desc' ? 'selected' : '' }}>Nom (Z-A)</option>
                                                <option value="prix_asc" {{ isset($sort) && $sort == 'prix_asc' ? 'selected' : '' }}>Prix (croissant)</option>
                                                <option value="prix_desc" {{ isset($sort) && $sort == 'prix_desc' ? 'selected' : '' }}>Prix (décroissant)</option>
                                                <option value="quantite_desc" {{ isset($sort) && $sort == 'quantite_desc' ? 'selected' : '' }}>Quantité (élevée d'abord)</option>
                                                <option value="quantite_asc" {{ isset($sort) && $sort == 'quantite_asc' ? 'selected' : '' }}>Quantité (faible d'abord)</option>
                                                <option value="categorie_asc" {{ isset($sort) && $sort == 'categorie_asc' ? 'selected' : '' }}>Catégorie (A-Z)</option>
                                                <option value="categorie_desc" {{ isset($sort) && $sort == 'categorie_desc' ? 'selected' : '' }}>Catégorie (Z-A)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2 align-self-end filter-actions">
                                        <button type="submit" class="btn btn-primary btn-sm">Appliquer</button>
                                        <a href="{{ route('produits.index') }}" class="btn btn-secondary btn-sm btn-reset-filters">Réinitialiser</a>
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
                                    <th scope="col">Référence</th>
                                    <th scope="col">Nom</th>
                                    <th scope="col">Catégorie</th>
                                    <th scope="col">Stock</th>
                                    <th scope="col">Prix</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($produits as $produit)
                                <tr>
                                    <td>{{ $produit->reference }}</td>
                                    <td>{{ $produit->nom }}</td>
                                    <td>{{ $produit->categorie->nom }}</td>
                                    <td>
                                        @if ($produit->quantite < 10)
                                            <span class="text-danger">{{ $produit->quantite }}</span>
                                        @else
                                            {{ $produit->quantite }}
                                        @endif
                                    </td>
                                    <td>{{ number_format($produit->prix, 2) }} MAD</td>
                                    <td>
                                        <div class="dropdown">
                                            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                <a class="dropdown-item" href="{{ route('produits.show', $produit) }}">Voir</a>
                                                <a class="dropdown-item" href="{{ route('produits.edit', $produit) }}">Modifier</a>
                                                <form action="{{ route('produits.destroy', $produit) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit?')">Supprimer</button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer py-4">
                        <nav class="d-flex justify-content-center" aria-label="...">
                            {{ $produits->links() }}
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        
        @include('layouts.footers.auth')
    </div>
@endsection
