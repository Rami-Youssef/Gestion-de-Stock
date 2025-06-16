@extends('layouts.app')

@section('content')
    <div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row">
                    <div class="col">
                        <h1 class="text-white mb-0">Détails du Mouvement</h1>
                        <p class="text-white">Informations complètes sur le mouvement de stock</p>
                    </div>
                    <div class="col text-right">
                        <a href="{{ route('mouvements.index') }}" class="btn btn-white">Retour à la liste</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-8 mx-auto">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">Informations du mouvement</h3>
                        
                        @if($mouvement->canceled)
                            <span class="badge badge-danger">ANNULÉ</span>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4 font-weight-bold">Produit :</div>
                            <div class="col-md-8">
                                <a href="{{ route('produits.show', $mouvement->produit) }}">
                                    {{ $mouvement->produit->nom }} ({{ $mouvement->produit->reference }})
                                </a>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-4 font-weight-bold">Type de mouvement :</div>
                            <div class="col-md-8">
                                @if($mouvement->type === 'entrée')
                                    <span class="badge badge-success">Entrée en stock</span>
                                @else
                                    <span class="badge badge-danger">Sortie de stock</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-4 font-weight-bold">Quantité :</div>
                            <div class="col-md-8">{{ $mouvement->quantite }}</div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-4 font-weight-bold">Date du mouvement :</div>
                            <div class="col-md-8">{{ $mouvement->date_cmd->format('d/m/Y H:i') }}</div>
                        </div>
                        
                        @if($mouvement->date_reception && $mouvement->type === 'entrée')
                        <div class="row mb-3">
                            <div class="col-md-4 font-weight-bold">Date de réception :</div>
                            <div class="col-md-8">{{ $mouvement->date_reception->format('d/m/Y') }}</div>
                        </div>
                        @endif
                        
                        <div class="row mb-3">
                            <div class="col-md-4 font-weight-bold">Enregistré par :</div>
                            <div class="col-md-8">{{ $mouvement->utilisateur->utilisateur }}</div>
                        </div>
                        
                        @if($mouvement->canceled)
                        <div class="alert alert-warning">
                            <strong>Ce mouvement a été annulé.</strong> Son effet sur le stock a été contrebalancé.
                        </div>
                        @endif
                        
                        @if(!$mouvement->canceled && Auth::user()->role === 'admin')
                        <div class="text-center mt-4">
                            <form action="{{ route('mouvements.cancel', $mouvement) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir annuler ce mouvement?')">
                                    Annuler ce mouvement
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        @include('layouts.footers.auth')
    </div>
@endsection
