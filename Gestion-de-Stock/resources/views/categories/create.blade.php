@extends('layouts.app')

@section('content')
    <div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row">
                    <div class="col">
                        <h1 class="text-white mb-0">Nouvelle Catégorie</h1>
                        <p class="text-white">Créez une nouvelle catégorie de produits</p>
                    </div>
                    <div class="col text-right">
                        <a href="{{ route('categories.index') }}" class="btn btn-white">Retour à la liste</a>
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
                        <h3 class="mb-0">Détails de la catégorie</h3>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('categories.store') }}" autocomplete="off">
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
                                <label class="form-control-label" for="input-nom">Nom de la catégorie</label>
                                <input type="text" name="nom" id="input-nom" class="form-control form-control-alternative" placeholder="Entrez le nom de la catégorie" value="{{ old('nom') }}" required>
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
