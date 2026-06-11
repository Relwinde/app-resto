<div>

    @include('partials.pages.header')

    <div class="content">
        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title">{{ $pageHeader['subtitle'] }}</h3>
                <div class="block-options">
                    @can('Créer Catégorie')
                    <button wire:click="$dispatch('openModal', { component: 'categories.modals.create-categorie' })"
                        class="btn btn-sm btn-primary">
                        <i class="fa fa-plus"></i> Ajouter une catégorie
                    </button>
                    @endcan
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
                            <th class="text-center">Nb. produits</th>
                            <th class="text-center" style="width: 100px;">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($categories as $categorie)
                            <tr>
                                <td>{{ $categorie->name }}</td>
                                <td class="text-center">
                                    <span class="badge badge-primary">{{ $categorie->products_count }}</span>
                                </td>
                                <td class="text-center">
                                    @canany(['Modifier Catégorie', 'Supprimer Catégorie'])
                                    <div class="btn-group">
                                        @can('Modifier Catégorie')
                                        <button
                                            wire:click="$dispatch('openModal', { component: 'categories.modals.edit-categorie', arguments: { categorie: {{ $categorie }} } })"
                                            type="button" class="btn btn-sm btn-light" title="Modifier">
                                            <i class="fa fa-fw fa-pencil-alt"></i>
                                        </button>
                                        @endcan
                                        @can('Supprimer Catégorie')
                                        <a wire:click.prevent="delete({{ $categorie->id }})"
                                            wire:confirm="Êtes-vous sûr de vouloir supprimer cette catégorie ?"
                                            type="button" class="btn btn-sm btn-light" title="Supprimer">
                                            <i class="fa fa-fw fa-trash"></i>
                                        </a>
                                        @endcan
                                    </div>
                                    @endcanany
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">
                                    Aucune catégorie enregistrée
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div>
                    {{ $categories->links() }}
                </div>
            </div>
        </div>
    </div>

</div>
