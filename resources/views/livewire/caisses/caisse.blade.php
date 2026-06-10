<div>
    @include('partials.pages.header', ['pageHeader' => $pageHeader])

    <div class="content">

        @if (! $caisse)
            <div class="alert alert-warning">Aucune caisse active configurée.</div>
        @elseif (! $sessionActive)
            <div class="alert alert-warning d-flex align-items-center justify-content-between">
                <span><i class="fa fa-exclamation-triangle mr-2"></i> Aucune session de caisse ouverte. Ouvrez une session pour enregistrer des ventes.</span>
                <a href="{{ route('caisse.sessions') }}" class="btn btn-sm btn-warning ml-3">Gérer les sessions</a>
            </div>
        @endif

        <div class="row">
            {{-- Colonne Gauche : Catalogue produits --}}
            <div class="col-lg-8">
                <div class="block block-rounded">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Produits</h3>
                    </div>
                    <div class="block-content pb-2">
                        <div class="row mb-3">
                            <div class="col-sm-6">
                                <input wire:model.live="search" type="search"
                                    class="form-control form-control-alt form-control-sm"
                                    placeholder="Rechercher un produit...">
                            </div>
                            <div class="col-sm-6">
                                <select wire:model.live="categorieId" class="form-control form-control-alt form-control-sm">
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
                                        <div class="font-w600 font-size-sm text-dark mb-1" style="line-height:1.3;">{{ $produit->name }}</div>
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
            </div>

            {{-- Colonne Droite : Panier + Total --}}
            <div class="col-lg-4">
                <div class="block block-rounded" style="position:sticky; top:1rem;">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Commande</h3>
                        @if (! empty($panier))
                            <div class="block-options">
                                <button type="button" wire:click="vider" class="btn btn-sm btn-alt-danger" title="Vider">
                                    <i class="fa fa-fw fa-trash"></i>
                                </button>
                            </div>
                        @endif
                    </div>
                    <div class="block-content">

                        <div class="row mb-2">
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
                            <p class="text-center text-muted py-4 small">
                                <i class="fa fa-shopping-cart fa-2x d-block mb-2"></i>
                                Cliquez sur un produit pour l'ajouter
                            </p>
                        @else
                            <div class="mb-3" style="max-height:340px; overflow-y:auto;">
                                <table class="table table-sm table-borderless mb-0">
                                    <tbody>
                                        @foreach ($panier as $id => $item)
                                            <tr>
                                                <td class="pl-0 font-size-sm">
                                                    <div class="font-w600">{{ $item['nom'] }}</div>
                                                    <div class="text-muted">{{ number_format($item['prix_unitaire'], 0, ',', ' ') }} FCFA</div>
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

                            <button type="button" wire:click="ouvrirPaiement"
                                class="btn btn-success btn-block btn-lg"
                                {{ ! $sessionActive ? 'disabled' : '' }}>
                                <i class="fa fa-fw fa-cash-register"></i> Encaisser
                            </button>
                        @endif

                    </div>
                </div>
            </div>
        </div>

    </div>

    <livewire:wire-elements-modal />
</div>
