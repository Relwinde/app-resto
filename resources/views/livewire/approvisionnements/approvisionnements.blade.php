<div>

    @include('partials.pages.header')

    <div class="content">
        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title">{{ $pageHeader['subtitle'] }}</h3>
                <div class="block-options">
                    @can('Créer Approvisionnement')
                    @if ($sessionOuverte)
                    <button wire:click="$dispatch('openModal', { component: 'approvisionnements.modals.create-approvisionnement' })"
                        class="btn btn-sm btn-primary">
                        <i class="fa fa-plus"></i> Nouvel approvisionnement
                    </button>
                    @else
                    <button class="btn btn-sm btn-secondary" disabled
                        title="Ouvrez une session de caisse pour créer un approvisionnement">
                        <i class="fa fa-lock mr-1"></i> Nouvel approvisionnement
                    </button>
                    @endif
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
                        placeholder="Recherche par produit ou fournisseur..." id="search-input">
                </div>

                <table class="table table-bordered table-striped table-vcenter table-responsive-md">
                    <thead>
                        <tr>
                            <th>Numéro</th>
                            <th>Fournisseur</th>
                            <th>Caisse</th>
                            <th class="text-center">Produits</th>
                            <th class="text-right">Montant total</th>
                            <th class="text-center">Date</th>
                            <th class="text-center" style="width: 100px;">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($approvisionnements as $appro)
                            <tr>
                                <td>{{ $appro->numero }}</td>
                                <td>{{ $appro->fournisseur?->name ?? '—' }}</td>
                                <td>{{ $appro->caisse?->nom ?? '—' }}</td>
                                <td class="text-center">{{ $appro->lignes->count() }}</td>
                                <td class="text-right">
                                    {{ number_format($appro->montant_total, 0, ',', ' ') }} FCFA
                                </td>
                                <td class="text-center">{{ $appro->created_at->format('d/m/Y') }}</td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button
                                            wire:click="$dispatch('openModal', { component: 'approvisionnements.modals.show-approvisionnement', arguments: { approvisionnement: {{ $appro }} } })"
                                            type="button" class="btn btn-sm btn-light" title="Voir les détails">
                                            <i class="fa fa-fw fa-eye"></i>
                                        </button>
                                        @can('Modifier Approvisionnement')
                                        <button
                                            wire:click="$dispatch('openModal', { component: 'approvisionnements.modals.edit-approvisionnement', arguments: { approvisionnement: {{ $appro }} } })"
                                            type="button" class="btn btn-sm btn-light" title="Modifier">
                                            <i class="fa fa-fw fa-pencil-alt"></i>
                                        </button>
                                        @endcan
                                        @can('Supprimer Approvisionnement')
                                        <a wire:click.prevent="delete({{ $appro->id }})"
                                            wire:confirm="Supprimer cet approvisionnement ? Le stock des produits sera recalculé et la caisse sera recréditée du montant total."
                                            type="button" class="btn btn-sm btn-light" title="Supprimer">
                                            <i class="fa fa-fw fa-trash"></i>
                                        </a>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">
                                    Aucun approvisionnement enregistré
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div>
                    {{ $approvisionnements->links() }}
                </div>
            </div>
        </div>
    </div>

</div>
