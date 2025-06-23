<div class="row align-items-center justify-content-xl-between">
    <div class="col-xl-6">
        <div class="copyright text-center text-xl-left text-muted">
            &copy; {{ now()->year }} <span class="font-weight-bold ml-1">Gestion de Stock</span>
        </div>
    </div>
    <div class="col-xl-6">
        <ul class="nav nav-footer justify-content-center justify-content-xl-end">
            <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link">Tableau de Bord</a>
            </li>
            <li class="nav-item">
                <a href="{{ route('categories.index') }}" class="nav-link">Catégories</a>
            </li>
            <li class="nav-item">
                <a href="{{ route('produits.index') }}" class="nav-link">Produits</a>
            </li>
            <li class="nav-item">
                <a href="{{ route('mouvements.index') }}" class="nav-link">Mouvements</a>
            </li>
        </ul>
    </div>
</div>