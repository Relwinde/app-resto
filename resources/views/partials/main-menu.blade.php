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

            @canany(['Voir Produits', 'Voir Catégories', 'Voir Fournisseurs', 'Voir Approvisionnements'])
            <li class="nav-main-heading">Stock</li>

            @can('Voir Produits')
            <!-- PRODUITS -->
            <li class="nav-main-item">
                <a class="nav-main-link {{ request()->routeIs('produits') ? 'active' : '' }}" href="{{ route('produits') }}" wire:navigate>
                    <i class="nav-main-link-icon fa fa-box"></i>
                    <span class="nav-main-link-name">Produits</span>
                </a>
            </li>
            @endcan

            @can('Voir Catégories')
            <!-- CATÉGORIES -->
            <li class="nav-main-item">
                <a class="nav-main-link {{ request()->routeIs('categories') ? 'active' : '' }}" href="{{ route('categories') }}" wire:navigate>
                    <i class="nav-main-link-icon fa fa-tags"></i>
                    <span class="nav-main-link-name">Catégories</span>
                </a>
            </li>
            @endcan

            @can('Voir Approvisionnements')
            <!-- APPROVISIONNEMENTS -->
            <li class="nav-main-item">
                <a class="nav-main-link {{ request()->routeIs('approvisionnements') ? 'active' : '' }}" href="{{ route('approvisionnements') }}" wire:navigate>
                    <i class="nav-main-link-icon fa fa-truck"></i>
                    <span class="nav-main-link-name">Approvisionnements</span>
                </a>
            </li>
            @endcan

            @can('Voir Fournisseurs')
            <!-- FOURNISSEURS -->
            <li class="nav-main-item">
                <a class="nav-main-link {{ request()->routeIs('fournisseurs') ? 'active' : '' }}" href="{{ route('fournisseurs') }}" wire:navigate>
                    <i class="nav-main-link-icon fa fa-store"></i>
                    <span class="nav-main-link-name">Fournisseurs</span>
                </a>
            </li>
            @endcan
            @endcanany

            @canany(['Voir Caisse', 'Voir Activité Caisse', 'Voir Sessions Caisse', 'Voir Journal Caisse', 'Voir Commandes'])
            <!-- SÉPARATEUR -->
            <li class="nav-main-heading">Ventes</li>

            @canany(['Voir Caisse', 'Voir Activité Caisse', 'Voir Sessions Caisse', 'Voir Journal Caisse'])
            <!-- CAISSE -->
            <li class="nav-main-item {{ request()->routeIs('caisse*') ? 'open' : '' }}">
                <a class="nav-main-link nav-main-link-submenu {{ request()->routeIs('caisse*') ? 'active' : '' }}" data-toggle="submenu" aria-haspopup="true" aria-expanded="{{ request()->routeIs('caisse*') ? 'true' : 'false' }}" href="#">
                    <i class="nav-main-link-icon fa fa-cash-register"></i>
                    <span class="nav-main-link-name">Caisse</span>
                </a>
                <ul class="nav-main-submenu">
                    @can('Voir Caisse')
                    <li class="nav-main-item">
                        <a class="nav-main-link {{ request()->routeIs('caisse') ? 'active' : '' }}" href="{{ route('caisse') }}" wire:navigate>
                            <i class="nav-main-link-icon fa fa-desktop"></i>
                            <span class="nav-main-link-name">Interface caisse</span>
                        </a>
                    </li>
                    @endcan
                    @can('Voir Sessions Caisse')
                    <li class="nav-main-item">
                        <a class="nav-main-link {{ request()->routeIs('caisse.sessions') ? 'active' : '' }}" href="{{ route('caisse.sessions') }}" wire:navigate>
                            <i class="nav-main-link-icon fa fa-clock"></i>
                            <span class="nav-main-link-name">Sessions</span>
                        </a>
                    </li>
                    @endcan
                    @can('Voir Journal Caisse')
                    <li class="nav-main-item">
                        <a class="nav-main-link {{ request()->routeIs('caisse.mouvements') ? 'active' : '' }}" href="{{ route('caisse.mouvements') }}" wire:navigate>
                            <i class="nav-main-link-icon fa fa-list-alt"></i>
                            <span class="nav-main-link-name">Journal</span>
                        </a>
                    </li>
                    @endcan
                </ul>
            </li>
            @endcanany

            @can('Voir Commandes')
            <!-- COMMANDES -->
            <li class="nav-main-item">
                <a class="nav-main-link {{ request()->routeIs('commandes') ? 'active' : '' }}" href="{{ route('commandes') }}" wire:navigate>
                    <i class="nav-main-link-icon fa fa-receipt"></i>
                    <span class="nav-main-link-name">Commandes</span>
                </a>
            </li>
            @endcan
            @endcanany

            @canany(['Voir Utilisateurs', 'Voir Rôles'])
            <!-- ADMINISTRATION -->
            <li class="nav-main-heading">Administration</li>

            @can('Voir Utilisateurs')
            <li class="nav-main-item">
                <a class="nav-main-link {{ request()->routeIs('utilisateurs') ? 'active' : '' }}" href="{{ route('utilisateurs') }}" wire:navigate>
                    <i class="nav-main-link-icon fa fa-users"></i>
                    <span class="nav-main-link-name">Utilisateurs</span>
                </a>
            </li>
            @endcan

            @can('Voir Rôles')
            <li class="nav-main-item">
                <a class="nav-main-link {{ request()->routeIs('roles') ? 'active' : '' }}" href="{{ route('roles') }}" wire:navigate>
                    <i class="nav-main-link-icon fa fa-user-shield"></i>
                    <span class="nav-main-link-name">Rôles</span>
                </a>
            </li>
            @endcan
            @endcanany

        </ul>
    </div>
</div>
