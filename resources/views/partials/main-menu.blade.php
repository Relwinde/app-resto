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

            <!-- SÉPARATEUR -->
            <li class="nav-main-heading">Ventes</li>

            <!-- CAISSE -->
            <li class="nav-main-item {{ request()->routeIs('caisse*') ? 'open' : '' }}">
                <a class="nav-main-link nav-main-link-submenu {{ request()->routeIs('caisse*') ? 'active' : '' }}" data-toggle="submenu" aria-haspopup="true" aria-expanded="{{ request()->routeIs('caisse*') ? 'true' : 'false' }}" href="#">
                    <i class="nav-main-link-icon fa fa-cash-register"></i>
                    <span class="nav-main-link-name">Caisse</span>
                </a>
                <ul class="nav-main-submenu">
                    <li class="nav-main-item">
                        <a class="nav-main-link {{ request()->routeIs('caisse') ? 'active' : '' }}" href="{{ route('caisse') }}" wire:navigate>
                            <i class="nav-main-link-icon fa fa-desktop"></i>
                            <span class="nav-main-link-name">Interface caisse</span>
                        </a>
                    </li>
                    <li class="nav-main-item">
                        <a class="nav-main-link {{ request()->routeIs('caisse.sessions') ? 'active' : '' }}" href="{{ route('caisse.sessions') }}" wire:navigate>
                            <i class="nav-main-link-icon fa fa-clock"></i>
                            <span class="nav-main-link-name">Sessions</span>
                        </a>
                    </li>
                    <li class="nav-main-item">
                        <a class="nav-main-link {{ request()->routeIs('caisse.mouvements') ? 'active' : '' }}" href="{{ route('caisse.mouvements') }}" wire:navigate>
                            <i class="nav-main-link-icon fa fa-list-alt"></i>
                            <span class="nav-main-link-name">Journal</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- COMMANDES -->
            <li class="nav-main-item">
                <a class="nav-main-link {{ request()->routeIs('commandes') ? 'active' : '' }}" href="{{ route('commandes') }}" wire:navigate>
                    <i class="nav-main-link-icon fa fa-receipt"></i>
                    <span class="nav-main-link-name">Commandes</span>
                </a>
            </li>

        </ul>
    </div>
</div>
