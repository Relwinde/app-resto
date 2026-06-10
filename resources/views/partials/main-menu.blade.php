<div class="js-sidebar-scroll">
    <!-- Side Navigation -->
    <div class="content-side">
        <ul class="nav-main">

            <!-- ACCUEIL -->
            <li class="nav-main-item">
                <a class="nav-main-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}" wire:navigate>
                    <i class="nav-main-link-icon si si-home"></i>
                    <span class="nav-main-link-name">Accueil</span>
                </a>
            </li>

            <!-- PRODUITS -->
            <li class="nav-main-item">
                <a class="nav-main-link {{ request()->routeIs('produits') ? 'active' : '' }}" href="{{ route('produits') }}" wire:navigate>
                    <i class="nav-main-link-icon fa fa-box"></i>
                    <span class="nav-main-link-name">Produits</span>
                </a>
            </li>

            <!-- CATÉGORIES -->
            <li class="nav-main-item">
                <a class="nav-main-link {{ request()->routeIs('categories') ? 'active' : '' }}" href="{{ route('categories') }}" wire:navigate>
                    <i class="nav-main-link-icon fa fa-tags"></i>
                    <span class="nav-main-link-name">Catégories</span>
                </a>
            </li>

            <!-- APPROVISIONNEMENTS -->
            <li class="nav-main-item">
                <a class="nav-main-link {{ request()->routeIs('approvisionnements') ? 'active' : '' }}" href="{{ route('approvisionnements') }}" wire:navigate>
                    <i class="nav-main-link-icon fa fa-truck"></i>
                    <span class="nav-main-link-name">Approvisionnements</span>
                </a>
            </li>

            <!-- FOURNISSEURS -->
            <li class="nav-main-item">
                <a class="nav-main-link {{ request()->routeIs('fournisseurs') ? 'active' : '' }}" href="{{ route('fournisseurs') }}" wire:navigate>
                    <i class="nav-main-link-icon fa fa-store"></i>
                    <span class="nav-main-link-name">Fournisseurs</span>
                </a>
            </li>

        </ul>
    </div>
</div>
