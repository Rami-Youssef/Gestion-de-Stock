@extends('layouts.app')

@section('content')
    @include('users.partials.header', [
        'title' => $user->utilisateur,
        'description' => 'Détails de l\'utilisateur',
        'class' => 'col-lg-7'
    ])   

    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-4 order-xl-2 mb-5 mb-xl-0">
                <div class="card card-profile shadow">
                    <div class="row justify-content-center">
                        <div class="col-lg-3 order-lg-2">                            <div class="card-profile-image">
                                <a href="#">
                                    <img src="{{ asset('argon') }}/img/theme/default-avatar-detailed.svg" class="rounded-circle">
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-header text-center border-0 pt-8 pt-md-4 pb-0 pb-md-4">
                        <div class="d-flex justify-content-center">
                            <h5>{{ __('Informations utilisateur') }}</h5>
                        </div>
                    </div>
                    <div class="card-body pt-0 pt-md-4">
                        <div class="text-center">
                            <h3>
                                {{ $user->utilisateur }}
                            </h3>
                            <div class="h5 mt-4">
                                <i class="ni business_briefcase-24 mr-2"></i>{{ __('Rôle: ') . $user->role }}
                            </div>
                            <div>
                                <i class="ni ni-email-83 mr-2"></i>{{ $user->email }}
                            </div>
                            <hr class="my-4" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-8 order-xl-1">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <h3 class="mb-0">{{ __('Activité récente') }}</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <h6 class="heading-small text-muted mb-4">{{ __('Mouvements de stock') }}</h6>
                        
                        <div class="table-responsive">
                            <table class="table align-items-center table-flush">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">Date</th>
                                        <th scope="col">Produit</th>
                                        <th scope="col">Type</th>
                                        <th scope="col">Quantité</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($user->mouvementStocks()->orderBy('date_cmd', 'desc')->take(5)->get() as $mouvement)
                                    <tr>
                                        <td>{{ $mouvement->date_cmd->format('d/m/Y H:i') }}</td>
                                        <td>{{ $mouvement->produit->nom }}</td>
                                        <td>
                                            <span class="badge badge-{{ $mouvement->type == 'entrée' ? 'success' : 'danger' }}">
                                                {{ $mouvement->type }}
                                            </span>
                                        </td>
                                        <td>{{ $mouvement->quantite }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        @include('layouts.footers.auth')
    </div>
@endsection
