@extends('layouts.app')

@section('content')
    <div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row">
                    <div class="col">
                        <h1 class="text-white mb-0">Nouveau Mouvement</h1>
                        <p class="text-white">Enregistrez une entrée ou sortie de stock</p>
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
            <div class="col-xl-12">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <h3 class="mb-0">Détails du mouvement</h3>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('mouvements.store') }}" autocomplete="off">
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
                            
                            @if(session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-produit">Produit</label>
                                        <select name="produit_id" id="input-produit" class="form-control form-control-alternative" required>
                                            <option value="">Sélectionnez un produit</option>
                                            @foreach ($produits as $produit)
                                                <option value="{{ $produit->id }}" {{ old('produit_id') == $produit->id ? 'selected' : '' }} data-stock="{{ $produit->quantite }}">
                                                    {{ $produit->nom }} ({{ $produit->reference }}) - Stock: {{ $produit->quantite }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-type">Type de mouvement</label>
                                        <select name="type" id="input-type" class="form-control form-control-alternative" required>
                                            <option value="entrée" {{ old('type') == 'entrée' ? 'selected' : '' }}>Entrée en stock</option>
                                            <option value="sortie" {{ old('type') == 'sortie' ? 'selected' : '' }}>Sortie de stock</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-quantite">Quantité</label>
                                        <input type="number" name="quantite" id="input-quantite" class="form-control form-control-alternative" placeholder="Quantité" value="{{ old('quantite', 1) }}" min="1" required>
                                        <small class="text-muted stock-warning d-none text-danger">Attention: La quantité dépasse le stock disponible.</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-date">Date du mouvement</label>
                                        <input type="date" name="date_cmd" id="input-date" class="form-control form-control-alternative" value="{{ old('date_cmd', date('Y-m-d')) }}" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-control-label" for="input-date-reception">Date de réception (pour les entrées)</label>
                                <input type="date" name="date_reception" id="input-date-reception" class="form-control form-control-alternative" value="{{ old('date_reception') }}">
                                <small class="text-muted">Laissez vide si la réception est immédiate</small>
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

@push('js')
<script>
    $(document).ready(function() {
        function checkStock() {
            var selectedOption = $('#input-produit option:selected');
            var currentStock = parseInt(selectedOption.attr('data-stock') || 0);
            var quantity = parseInt($('#input-quantite').val() || 0);
            var type = $('#input-type').val();
            
            if (type === 'sortie' && quantity > currentStock) {
                $('.stock-warning').removeClass('d-none');
            } else {
                $('.stock-warning').addClass('d-none');
            }
        }
        
        $('#input-produit, #input-quantite, #input-type').on('change', checkStock);
        
        $('#input-type').on('change', function() {
            var type = $(this).val();
            if (type === 'entrée') {
                $('#input-date-reception').parent().show();
            } else {
                $('#input-date-reception').parent().hide();
            }
        });
        
        // Initial check
        checkStock();
        
        // Initial date reception visibility
        if ($('#input-type').val() !== 'entrée') {
            $('#input-date-reception').parent().hide();
        }
    });
</script>
@endpush
