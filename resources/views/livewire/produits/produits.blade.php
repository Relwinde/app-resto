<div>

    @include('partials.pages.header')

    <div class="content">
        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title">{{ $pageHeader['subtitle'] }}</h3>
                <div class="block-options">
                    <button wire:click="$dispatch('openModal', { component: 'produits.modals.create-produit' })"
                        class="btn btn-sm btn-primary">
                        <i class="fa fa-plus"></i> Ajouter un produit
                    </button>
                </div>
            </div>

            <div class="block-content block-content-full">
                <div class="input-group p-3">
                    @if ($search != null && $search != '')
                        <div class="input-group-prepend">
                            <button wire:click="clear_search" type="button" class="btn btn-alt-danger">
                                <i class="fa fa-fw fa-times-circle"></i>
                            </button>
                        </div>
                    @endif
                    <input wire:model.live.debounce.500ms="search" type="text" class="form-control"
                        placeholder="Recherche par nom..." id="search-input" name="search-input">
                </div>

                <table class="table table-bordered table-striped table-vcenter table-responsive-md">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Catégorie</th>
                            <th>Prix vente</th>
                            <th>Prix achat</th>
                            <th>Unité</th>
                            <th class="text-center">Approvisionnable</th>
                            <th class="text-center" style="width: 100px;">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($produits as $produit)
                            <tr>
                                <td>{{ $produit->name }}</td>
                                <td>{{ $produit->category?->name ?? '-' }}</td>
                                <td>{{ number_format($produit->prix_vente, 0, ',', ' ') }}</td>
                                <td>{{ $produit->prix_achat ? number_format($produit->prix_achat, 0, ',', ' ') : '-' }}</td>
                                <td>{{ $produit->unite }}</td>
                                <td class="text-center">
                                    @if ($produit->is_suppliable)
                                        <span class="badge badge-success">Oui</span>
                                    @else
                                        <span class="badge badge-secondary">Non</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button
                                            wire:click="$dispatch('openModal', { component: 'produits.modals.edit-produit', arguments: { produit: {{ $produit }} } })"
                                            type="button" class="btn btn-sm btn-light" title="Modifier">
                                            <i class="fa fa-fw fa-pencil-alt"></i>
                                        </button>
                                        <a wire:click.prevent="delete({{ $produit->id }})"
                                            wire:confirm="Êtes-vous sûr de vouloir supprimer ce produit ?"
                                            type="button" class="btn btn-sm btn-light" title="Supprimer">
                                            <i class="fa fa-fw fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">
                                    Aucun produit enregistré
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div>
                    {{ $produits->links() }}
                </div>
            </div>
        </div>
    </div>

</div>
