@extends('layouts.app')

@section('content')
    <div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row">
                    <div class="col">
                        <h1 class="text-white mb-0">Nouveau Produit</h1>
                        <p class="text-white">Ajoutez un nouveau produit à votre inventaire</p>
                    </div>
                    <div class="col text-right">
                        <a href="{{ route('produits.index') }}" class="btn btn-white">Retour à la liste</a>
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
                        <h3 class="mb-0">Détails du produit</h3>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('produits.store') }}" autocomplete="off">
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

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-nom">Nom du produit</label>
                                        <input type="text" name="nom" id="input-nom" class="form-control form-control-alternative" placeholder="Nom du produit" value="{{ old('nom') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-reference">Référence</label>
                                        <input type="text" name="reference" id="input-reference" class="form-control form-control-alternative" placeholder="Référence du produit" value="{{ old('reference') }}" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-quantite">Quantité initiale</label>
                                        <input type="number" name="quantite" id="input-quantite" class="form-control form-control-alternative" placeholder="Quantité initiale" value="{{ old('quantite', 0) }}" min="0" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-prix">Prix unitaire (MAD)</label>
                                        <input type="number" name="prix" id="input-prix" class="form-control form-control-alternative" placeholder="Prix unitaire" value="{{ old('prix', 0) }}" min="0" step="0.01" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-control-label" for="input-categorie">Catégorie</label>
                                <select name="categorie_id" id="input-categorie" class="form-control form-control-alternative" required>
                                    <option value="">Sélectionnez une catégorie</option>
                                    @foreach ($categories as $categorie)
                                        <option value="{{ $categorie->id }}" {{ old('categorie_id') == $categorie->id ? 'selected' : '' }}>
                                            {{ $categorie->nom }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="text-center">
                                <button type="submit" class="btn btn-success mt-4">Enregistrer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        @include('layouts.footers.auth')
    </div>
@endsection
