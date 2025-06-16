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
                        <h3 class="mb-0">Liste des Produits</h3>
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
                                    <td>{{ number_format($produit->prix, 2) }} €</td>
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
