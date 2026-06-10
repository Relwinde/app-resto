<div>

    @include('partials.pages.header')

    <div class="content">
        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title">{{ $pageHeader['subtitle'] }}</h3>
                <div class="block-options">
                    <button wire:click="$dispatch('openModal', { component: 'approvisionnements.modals.create-approvisionnement' })"
                        class="btn btn-sm btn-primary">
                        <i class="fa fa-plus"></i> Nouvel approvisionnement
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
                        placeholder="Recherche par produit ou fournisseur..." id="search-input">
                </div>

                <table class="table table-bordered table-striped table-vcenter table-responsive-md">
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th>Fournisseur</th>
                            <th class="text-right">Quantité</th>
                            <th class="text-right">Prix achat</th>
                            <th class="text-center">Date péremption</th>
                            <th>N° lot</th>
                            <th>Note</th>
                            <th class="text-center">Date</th>
                            <th class="text-center" style="width: 100px;">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($approvisionnements as $appro)
                            <tr>
                                <td>{{ $appro->product?->name ?? '—' }}</td>
                                <td>{{ $appro->fournisseur?->name ?? '—' }}</td>
                                <td class="text-right">
                                    {{ number_format($appro->quantite, 2, ',', ' ') }}
                                    <small class="text-muted">{{ $appro->product?->unite }}</small>
                                </td>
                                <td class="text-right">
                                    {{ $appro->prix_achat ? number_format($appro->prix_achat, 0, ',', ' ') : '—' }}
                                </td>
                                <td class="text-center">
                                    {{ $appro->date_peremption ? $appro->date_peremption->format('d/m/Y') : '—' }}
                                </td>
                                <td>{{ $appro->numero_lot ?? '—' }}</td>
                                <td>{{ $appro->note ?? '—' }}</td>
                                <td class="text-center">{{ $appro->created_at->format('d/m/Y') }}</td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button
                                            wire:click="$dispatch('openModal', { component: 'approvisionnements.modals.show-approvisionnement', arguments: { approvisionnement: {{ $appro }} } })"
                                            type="button" class="btn btn-sm btn-light" title="Voir les détails">
                                            <i class="fa fa-fw fa-eye"></i>
                                        </button>
                                        <button
                                            wire:click="$dispatch('openModal', { component: 'approvisionnements.modals.edit-approvisionnement', arguments: { approvisionnement: {{ $appro }} } })"
                                            type="button" class="btn btn-sm btn-light" title="Modifier">
                                            <i class="fa fa-fw fa-pencil-alt"></i>
                                        </button>
                                        <a wire:click.prevent="delete({{ $appro->id }})"
                                            wire:confirm="Supprimer cet approvisionnement ? Le stock du produit sera recalculé."
                                            type="button" class="btn btn-sm btn-light" title="Supprimer">
                                            <i class="fa fa-fw fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted">
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
