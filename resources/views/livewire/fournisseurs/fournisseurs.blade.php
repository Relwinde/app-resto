<div>

    @include('partials.pages.header')

    <div class="content">
        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title">{{ $pageHeader['subtitle'] }}</h3>
                <div class="block-options">
                    <button wire:click="$dispatch('openModal', { component: 'fournisseurs.modals.create-fournisseur' })"
                        class="btn btn-sm btn-primary">
                        <i class="fa fa-plus"></i> Ajouter un fournisseur
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
                        placeholder="Recherche par nom ou téléphone..." id="search-input">
                </div>

                <table class="table table-bordered table-striped table-vcenter table-responsive-md">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Téléphone</th>
                            <th class="text-center">Nb. approvisionnements</th>
                            <th class="text-center" style="width: 100px;">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($fournisseurs as $fournisseur)
                            <tr>
                                <td>{{ $fournisseur->name }}</td>
                                <td>{{ $fournisseur->phone ?? '—' }}</td>
                                <td class="text-center">
                                    <span class="badge badge-primary">{{ $fournisseur->stock_movements_count }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button
                                            wire:click="$dispatch('openModal', { component: 'fournisseurs.modals.edit-fournisseur', arguments: { fournisseur: {{ $fournisseur }} } })"
                                            type="button" class="btn btn-sm btn-light" title="Modifier">
                                            <i class="fa fa-fw fa-pencil-alt"></i>
                                        </button>
                                        <a wire:click.prevent="delete({{ $fournisseur->id }})"
                                            wire:confirm="Êtes-vous sûr de vouloir supprimer ce fournisseur ?"
                                            type="button" class="btn btn-sm btn-light" title="Supprimer">
                                            <i class="fa fa-fw fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">
                                    Aucun fournisseur enregistré
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div>
                    {{ $fournisseurs->links() }}
                </div>
            </div>
        </div>
    </div>

</div>
