@extends('layouts.app')

@section('content')
    <div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row">
                    <div class="col">
                        <h1 class="text-white mb-0">Détails du Produit</h1>
                        <p class="text-white">Informations complètes sur le produit</p>
                    </div>
                    <div class="col text-right">
                        <a href="{{ route('produits.index') }}" class="btn btn-white">Retour à la liste</a>
                        <a href="{{ route('produits.edit', $produit) }}" class="btn btn-white">Modifier</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-6">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <h3 class="mb-0">Informations du produit</h3>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4 font-weight-bold">Référence :</div>
                            <div class="col-md-8">{{ $produit->reference }}</div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-4 font-weight-bold">Nom :</div>
                            <div class="col-md-8">{{ $produit->nom }}</div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-4 font-weight-bold">Catégorie :</div>
                            <div class="col-md-8">{{ $produit->categorie->nom }}</div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-4 font-weight-bold">Quantité en stock :</div>
                            <div class="col-md-8">
                                @if ($produit->quantite < 10)
                                    <span class="text-danger font-weight-bold">{{ $produit->quantite }}</span>
                                @else
                                    {{ $produit->quantite }}
                                @endif
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-4 font-weight-bold">Prix unitaire :</div>
                            <div class="col-md-8">{{ number_format($produit->prix, 2) }} MAD</div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-4 font-weight-bold">Valeur en stock :</div>
                            <div class="col-md-8">{{ number_format($produit->quantite * $produit->prix, 2) }} MAD</div>
                        </div>
                        
                        <div class="text-center mt-4">
                            <a href="{{ route('mouvements.create') }}" class="btn btn-primary">Nouveau mouvement de stock</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-6">
                <div class="card shadow">
                    <div class="card-header bg-white border-0">
                        <h3 class="mb-0">Historique des mouvements</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">Date</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Quantité</th>
                                    <th scope="col">Utilisateur</th>
                                    <th scope="col">État</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($produit->mouvementStocks as $mouvement)
                                <tr class="{{ $mouvement->canceled ? 'text-muted bg-light' : '' }}">
                                    <td>{{ $mouvement->date_cmd->format('d/m/Y') }}</td>
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
                                </tr>
                                @endforeach
                                
                                @if(count($produit->mouvementStocks) === 0)
                                <tr>
                                    <td colspan="5" class="text-center">Aucun mouvement pour ce produit</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        @include('layouts.footers.auth')
    </div>
@endsection
