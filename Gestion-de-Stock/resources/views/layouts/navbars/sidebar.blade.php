<nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-white" id="sidenav-main">
    <div class="container-fluid">
        <!-- Toggler -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>        <!-- Brand -->
        <a class="navbar-brand pt-0" href="{{ route('home') }}" style="margin-left: -10px;">
            <div class="brand-text">
                <h1 class="h4 mb-0 text-primary font-weight-bold text-uppercase">Gestion de</h1>
                <h1 class="h4 mb-0 text-primary font-weight-bold text-uppercase" style="margin-top: -5px;">Stock</h1>
            </div>
        </a>
        <!-- User -->
        <ul class="nav align-items-center d-md-none">
            <li class="nav-item dropdown">
                <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="media align-items-center">
                        <span class="avatar avatar-sm rounded-circle">
                        <img alt="Avatar par défaut" src="{{ asset('argon') }}/img/theme/default-avatar.svg">
                        </span>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
                    <div class=" dropdown-header noti-title">
                        <h6 class="text-overflow m-0">{{ __('Bienvenue!') }}</h6>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="dropdown-item">
                        <i class="ni ni-single-02"></i>
                        <span>{{ __('Mon profil') }}</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                        <i class="ni ni-user-run"></i>
                        <span>{{ __('Déconnexion') }}</span>
                    </a>
                </div>
            </li>
        </ul>
        <!-- Collapse -->
        <div class="collapse navbar-collapse" id="sidenav-collapse-main">
            <!-- Collapse header -->
            <div class="navbar-collapse-header d-md-none">
                <div class="row">                    <div class="col-6 collapse-brand">
                        <a href="{{ route('home') }}">
                            <h2 class="h5 mb-0 text-primary font-weight-bold text-uppercase">Gestion de Stock</h2>
                        </a>
                    </div>
                    <div class="col-6 collapse-close">
                        <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle sidenav">
                            <span></span>
                            <span></span>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Navigation -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('dashboard') }}">
                        <i class="ni ni-tv-2 text-primary"></i> {{ __('Tableau de bord') }}
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('produits.index') }}">
                        <i class="ni ni-box-2 text-success"></i>
                        <span class="nav-link-text">{{ __('Produits') }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('categories.index') }}">
                        <i class="ni ni-tag text-primary"></i>
                        <span class="nav-link-text">{{ __('Catégories') }}</span>
                    </a>
                </li>                <li class="nav-item">
                    <a class="nav-link" href="{{ route('mouvements.index') }}">
                        <i class="ni ni-delivery-fast text-orange"></i>
                        <span class="nav-link-text">{{ __('Mouvements de Stock') }}</span>
                    </a>
                </li>
                  @if(Auth::user()->role === 'super_admin')
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('user.index') }}">
                        <i class="ni ni-circle-08 text-info"></i>
                        <span class="nav-link-text">{{ __('Gestion des Utilisateurs') }}</span>
                    </a>
                </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
