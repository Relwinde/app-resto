<div>
    @include('partials.pages.header', ['pageHeader' => $pageHeader])

    <div class="content">

        {{-- Alertes --}}
        @if (! $caisseEspeces && ! $caisseMobile)
            <div class="alert alert-warning">
                <i class="fa fa-exclamation-triangle mr-2"></i> Aucune caisse active configurée.
            </div>
        @elseif (! $sessionActive)
            <div class="alert alert-warning d-flex align-items-center justify-content-between">
                <span><i class="fa fa-exclamation-triangle mr-2"></i> Aucune session ouverte. Ouvrez une session pour enregistrer des ventes.</span>
                @can('Ouvrir Session Caisse')
                <button type="button"
                    wire:click="$dispatch('openModal', { component: 'caisses.modals.ouvrir-session' })"
                    class="btn btn-sm btn-warning ml-3">
                    <i class="fa fa-fw fa-unlock mr-1"></i> Ouvrir session
                </button>
                @endcan
            </div>
        @endif

        {{-- Status bar --}}
        @if ($caisseEspeces || $caisseMobile)
            <div class="block block-rounded mb-3">
                <div class="block-content py-3">
                    <div class="row align-items-center">

                        {{-- Caisse Espèces --}}
                        @if ($caisseEspeces)
                        <div class="col-md-5 {{ $caisseMobile ? 'border-right' : '' }}">
                            <div class="d-flex align-items-center">
                                <i class="fa fa-coins fa-lg text-warning mr-3"></i>
                                <div>
                                    <strong>{{ $caisseEspeces->nom }}</strong>
                                    <div class="text-muted small">
                                        Solde : <strong class="text-dark">{{ number_format($caisseEspeces->solde_actuel, 0, ',', ' ') }} FCFA</strong>
                                        @if ($sessionActive)
                                            &mdash; <span class="badge badge-success">Session ouverte</span>
                                            <span class="ml-1">depuis {{ $sessionActive->created_at->format('H:i') }} ({{ $sessionActive->user->name }})</span>
                                        @else
                                            &mdash; <span class="badge badge-secondary">Aucune session</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        {{-- Caisse Mobile Money --}}
                        @if ($caisseMobile)
                        <div class="col-md-4">
                            <div class="d-flex align-items-center">
                                <i class="fa fa-mobile-alt fa-lg text-primary mr-3"></i>
                                <div>
                                    <strong>{{ $caisseMobile->nom }}</strong>
                                    <div class="text-muted small">
                                        Solde : <strong class="text-dark">{{ number_format($caisseMobile->solde_actuel, 0, ',', ' ') }} FCFA</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        {{-- Actions session --}}
                        <div class="col-md-3 text-right">
                            @if ($sessionActive)
                                @can('Fermer Session Caisse')
                                <button type="button"
                                    wire:click="$dispatch('openModal', { component: 'caisses.modals.fermer-session' })"
                                    class="btn btn-sm btn-alt-danger">
                                    <i class="fa fa-fw fa-lock"></i> Fermer session
                                </button>
                                @endcan
                            @else
                                @can('Ouvrir Session Caisse')
                                <button type="button"
                                    wire:click="$dispatch('openModal', { component: 'caisses.modals.ouvrir-session' })"
                                    class="btn btn-sm btn-alt-success">
                                    <i class="fa fa-fw fa-unlock"></i> Ouvrir session
                                </button>
                                @endcan
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        @endif

        <div class="row">

            {{-- Colonne gauche --}}
            <div class="col-lg-8">

                {{-- Onglets --}}
                <ul class="nav nav-tabs nav-tabs-block mb-0" style="border-bottom: none;">
                    @can('Voir Activité Caisse')
                    <li class="nav-item">
                        <a class="nav-link {{ $vue === 'commandes' ? 'active' : '' }}"
                            wire:click.prevent="$set('vue', 'commandes')"
                            href="#" role="tab">
                            <i class="fa fa-list-ul mr-1"></i>
                            Commandes en cours
                            @if ($commandes->count() > 0)
                                <span class="badge badge-{{ $commandes->count() > 0 ? 'warning' : 'secondary' }} ml-1">
                                    {{ $commandes->count() }}
                                </span>
                            @endif
                        </a>
                    </li>
                    @endcan
                    @can('Enregistrer Commande')
                    <li class="nav-item">
                        <a class="nav-link {{ $vue === 'produits' ? 'active' : '' }}"
                            wire:click.prevent="$set('vue', 'produits')"
                            href="#" role="tab">
                            <i class="fa fa-plus-circle mr-1"></i>
                            Nouvelle commande
                        </a>
                    </li>
                    @endcan
                </ul>

                {{-- Panel : Commandes en cours --}}
                @can('Voir Activité Caisse')
                @if ($vue === 'commandes')
                <div wire:poll.10s class="pt-3">
                    @if ($commandes->isEmpty())
                        <div class="block block-rounded">
                            <div class="block-content py-5 text-center text-muted">
                                <i class="fa fa-clipboard-list fa-3x mb-3 d-block opacity-50"></i>
                                <h5>Aucune commande en cours</h5>
                                <p class="mb-3">Créez une nouvelle commande depuis l'onglet ci-dessus.</p>
                                @can('Enregistrer Commande')
                                <button type="button" wire:click="$set('vue', 'produits')"
                                    class="btn btn-sm btn-alt-primary">
                                    <i class="fa fa-plus mr-1"></i> Nouvelle commande
                                </button>
                                @endcan
                            </div>
                        </div>
                    @else
                        <div class="row row-deck">
                            @foreach ($commandes as $commande)
                                @php
                                    $badgeClass = match($commande->statut) {
                                        'en_attente'     => 'badge-warning',
                                        'en_preparation' => 'badge-info',
                                        'servie'         => 'badge-primary',
                                        default          => 'badge-secondary',
                                    };
                                    $borderColor = match($commande->statut) {
                                        'en_attente'     => '#f0a30a',
                                        'en_preparation' => '#70b9eb',
                                        'servie'         => '#375a7f',
                                        default          => '#e4e7ed',
                                    };
                                    $libelleStatut = match($commande->statut) {
                                        'en_attente'     => 'En attente',
                                        'en_preparation' => 'En préparation',
                                        'servie'         => 'Servie',
                                        default          => $commande->statut,
                                    };
                                @endphp
                                <div class="col-sm-6 col-xl-4 mb-4">
                                    <div class="block block-rounded h-100 mb-0"
                                        style="border-top: 3px solid {{ $borderColor }};">
                                        <div class="block-content pt-3 pb-0">

                                            {{-- En-tête --}}
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <div>
                                                    <span class="font-w700 text-dark">{{ $commande->numero }}</span>
                                                    <span class="badge {{ $badgeClass }} ml-2">{{ $libelleStatut }}</span>
                                                </div>
                                                <small class="text-muted"
                                                    title="{{ $commande->created_at->format('d/m/Y H:i:s') }}">
                                                    {{ $commande->created_at->diffForHumans() }}
                                                </small>
                                            </div>

                                            {{-- Table / client / serveur --}}
                                            <div class="mb-2 font-size-sm">
                                                @if ($commande->table_numero || $commande->client_nom)
                                                    <div>
                                                        @if ($commande->table_numero)
                                                            <i class="fa fa-utensils text-muted mr-1"></i>
                                                            <span>Table {{ $commande->table_numero }}</span>
                                                        @endif
                                                        @if ($commande->client_nom)
                                                            <span class="text-muted ml-1">— {{ $commande->client_nom }}</span>
                                                        @endif
                                                    </div>
                                                @endif
                                                <div class="text-muted">
                                                    <i class="fa fa-user mr-1"></i>{{ $commande->user->name }}
                                                    <span class="ml-2">
                                                        <i class="fa fa-clock mr-1"></i>{{ $commande->created_at->format('H:i') }}
                                                    </span>
                                                </div>
                                            </div>

                                            {{-- Articles --}}
                                            <div class="mb-3">
                                                <ul class="list-unstyled mb-0 font-size-sm">
                                                    @foreach ($commande->items as $item)
                                                        <li class="d-flex justify-content-between py-1 border-bottom">
                                                            <span>
                                                                <span class="badge badge-secondary mr-1">×{{ (int) $item->quantite }}</span>
                                                                {{ $item->produit?->name ?? '—' }}
                                                            </span>
                                                            <span class="text-muted font-size-xs">
                                                                {{ number_format($item->sous_total, 0, ',', ' ') }}
                                                            </span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                                <div class="d-flex justify-content-between mt-2 font-w700">
                                                    <span>Total</span>
                                                    <span class="text-success">
                                                        {{ number_format($commande->montant_total, 0, ',', ' ') }} FCFA
                                                    </span>
                                                </div>
                                            </div>

                                        </div>

                                        {{-- Actions --}}
                                        <div class="block-content block-content-full pt-0">
                                            <div class="d-flex flex-wrap gap-1">

                                                @can('Changer Statut Commande')
                                                    @if ($commande->statut === 'en_attente')
                                                        <button type="button"
                                                            wire:click="changerStatut({{ $commande->id }}, 'en_preparation')"
                                                            class="btn btn-sm btn-alt-info flex-grow-1">
                                                            <i class="fa fa-fw fa-fire"></i> En préparation
                                                        </button>
                                                    @elseif ($commande->statut === 'en_preparation')
                                                        <button type="button"
                                                            wire:click="changerStatut({{ $commande->id }}, 'servie')"
                                                            class="btn btn-sm btn-alt-primary flex-grow-1">
                                                            <i class="fa fa-fw fa-check-circle"></i> Servie
                                                        </button>
                                                    @endif
                                                @endcan

                                                @can('Encaisser Commande')
                                                    @if ($sessionActive)
                                                        <button type="button"
                                                            wire:click="$dispatch('openModal', { component: 'caisses.modals.encaisser-commande', arguments: { commande: {{ $commande->id }} } })"
                                                            class="btn btn-sm btn-success flex-grow-1">
                                                            <i class="fa fa-fw fa-money-bill-wave"></i> Encaisser
                                                        </button>
                                                    @endif
                                                @endcan

                                                @can('Annuler Commande')
                                                    <button type="button"
                                                        wire:click="$dispatch('openModal', { component: 'commandes.modals.annuler-commande', arguments: { commande: {{ $commande->id }} } })"
                                                        class="btn btn-sm btn-alt-danger"
                                                        title="Annuler">
                                                        <i class="fa fa-fw fa-ban"></i>
                                                    </button>
                                                @endcan

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                @endif
                @endcan

                {{-- Panel : Nouvelle commande (catalogue produits) --}}
                @can('Enregistrer Commande')
                @if ($vue === 'produits')
                <div class="block block-rounded mt-0" style="border-top-left-radius: 0;">
                    <div class="block-content pb-2">
                        <div class="row mb-3">
                            <div class="col-sm-6">
                                <input wire:model.live="search" type="search"
                                    class="form-control form-control-alt form-control-sm"
                                    placeholder="Rechercher un produit...">
                            </div>
                            <div class="col-sm-6">
                                <select wire:model.live="categorieId"
                                    class="form-control form-control-alt form-control-sm">
                                    <option value="0">Toutes les catégories</option>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row row-deck">
                            @forelse ($produits as $produit)
                                <div class="col-6 col-sm-4 col-md-3 mb-3">
                                    <button type="button"
                                        wire:click="ajouterProduit({{ $produit->id }})"
                                        class="btn btn-block btn-alt-secondary text-left p-2 h-100"
                                        style="border: 1px solid #e4e7ed;">
                                        <div class="font-w600 font-size-sm text-dark mb-1"
                                            style="line-height:1.3;">{{ $produit->name }}</div>
                                        <div class="text-success font-size-sm font-w600">
                                            {{ number_format($produit->prix_vente, 0, ',', ' ') }} FCFA
                                        </div>
                                        <div class="text-muted font-size-xs">{{ $produit->category?->name }}</div>
                                    </button>
                                </div>
                            @empty
                                <div class="col-12 text-center text-muted py-4">Aucun produit trouvé.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
                @endif
                @endcan

            </div>

            {{-- Colonne droite : Panier (toujours visible) --}}
            <div class="col-lg-4">
                <div class="block block-rounded" style="position:sticky; top:1rem;">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">
                            <i class="fa fa-shopping-cart mr-2"></i> Panier
                        </h3>
                        @if (! empty($panier))
                            <div class="block-options">
                                <button type="button" wire:click="vider"
                                    class="btn btn-sm btn-alt-danger" title="Vider le panier">
                                    <i class="fa fa-fw fa-trash"></i>
                                </button>
                            </div>
                        @endif
                    </div>
                    <div class="block-content">

                        <div class="row mb-3">
                            <div class="col-6">
                                <input wire:model="table_numero" type="text"
                                    class="form-control form-control-sm form-control-alt"
                                    placeholder="Table n°">
                            </div>
                            <div class="col-6">
                                <input wire:model="client_nom" type="text"
                                    class="form-control form-control-sm form-control-alt"
                                    placeholder="Nom client">
                            </div>
                        </div>

                        @if (empty($panier))
                            <p class="text-center text-muted py-3 small">
                                <i class="fa fa-shopping-cart fa-2x d-block mb-2 opacity-50"></i>
                                @if ($vue === 'commandes')
                                    @can('Enregistrer Commande')
                                    <button type="button" wire:click="$set('vue', 'produits')"
                                        class="btn btn-sm btn-alt-primary mt-2">
                                        <i class="fa fa-plus mr-1"></i> Ajouter des produits
                                    </button>
                                    @endcan
                                @else
                                    Cliquez sur un produit pour l'ajouter
                                @endif
                            </p>
                        @else
                            <div class="mb-3" style="max-height: 320px; overflow-y: auto;">
                                <table class="table table-sm table-borderless mb-0">
                                    <tbody>
                                        @foreach ($panier as $id => $item)
                                            <tr>
                                                <td class="pl-0 font-size-sm">
                                                    <div class="font-w600">{{ $item['nom'] }}</div>
                                                    <div class="text-muted">
                                                        {{ number_format($item['prix_unitaire'], 0, ',', ' ') }} FCFA
                                                    </div>
                                                </td>
                                                <td class="text-center align-middle" style="white-space:nowrap;">
                                                    <button type="button" wire:click="decrementer({{ $id }})"
                                                        class="btn btn-xs btn-alt-secondary px-1">
                                                        <i class="fa fa-minus"></i>
                                                    </button>
                                                    <span class="mx-1 font-w600">{{ $item['quantite'] }}</span>
                                                    <button type="button" wire:click="incrementer({{ $id }})"
                                                        class="btn btn-xs btn-alt-secondary px-1">
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                                </td>
                                                <td class="text-right align-middle pr-0 font-size-sm font-w600">
                                                    {{ number_format($item['sous_total'], 0, ',', ' ') }}
                                                </td>
                                                <td class="align-middle pr-0">
                                                    <button type="button" wire:click="retirer({{ $id }})"
                                                        class="btn btn-xs btn-alt-danger">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-between align-items-center border-top pt-3 mb-3">
                                <span class="font-w700 font-size-h5">TOTAL</span>
                                <span class="font-w700 font-size-h5 text-success">
                                    {{ number_format($total, 0, ',', ' ') }} FCFA
                                </span>
                            </div>

                            @can('Enregistrer Commande')
                            <button type="button" wire:click="enregistrerCommande"
                                wire:loading.attr="disabled"
                                class="btn btn-primary btn-block btn-lg"
                                {{ ! $sessionActive ? 'disabled' : '' }}>
                                <span wire:loading.remove wire:target="enregistrerCommande">
                                    <i class="fa fa-fw fa-check"></i> Enregistrer la commande
                                </span>
                                <span wire:loading wire:target="enregistrerCommande">
                                    <i class="fa fa-fw fa-spinner fa-spin"></i> Enregistrement...
                                </span>
                            </button>
                            @endcan
                        @endif

                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
