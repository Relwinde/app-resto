<div>

    @include('partials.pages.header')

    <div class="content">
        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title">{{ $pageHeader['subtitle'] }}</h3>
                <div class="block-options">
                    @can('Créer Perte')
                    <button wire:click="$dispatch('openModal', { component: 'pertes.modals.create-perte' })"
                        class="btn btn-sm btn-primary">
                        <i class="fa fa-plus"></i> Nouvelle perte
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
                        placeholder="Recherche par produit..." id="search-input">
                </div>

                <table class="table table-bordered table-striped table-vcenter table-responsive-md">
                    <thead>
                        <tr>
                            <th>Numéro</th>
                            <th>Motif</th>
                            <th class="text-center">Produits</th>
                            <th class="text-right">Valeur estimée</th>
                            <th>Déclaré par</th>
                            <th class="text-center">Date</th>
                            <th class="text-center" style="width: 100px;">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($pertes as $perte)
                            <tr>
                                <td>{{ $perte->numero }}</td>
                                <td>{{ $motifs[$perte->motif] ?? $perte->motif }}</td>
                                <td class="text-center">{{ $perte->lignes->count() }}</td>
                                <td class="text-right">
                                    {{ number_format($perte->valeur_estimee, 0, ',', ' ') }} FCFA
                                </td>
                                <td>{{ $perte->user?->name ?? '—' }}</td>
                                <td class="text-center">{{ $perte->created_at->format('d/m/Y') }}</td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button
                                            wire:click="$dispatch('openModal', { component: 'pertes.modals.show-perte', arguments: { perte: {{ $perte }} } })"
                                            type="button" class="btn btn-sm btn-light" title="Voir les détails">
                                            <i class="fa fa-fw fa-eye"></i>
                                        </button>
                                        @can('Modifier Perte')
                                        <button
                                            wire:click="$dispatch('openModal', { component: 'pertes.modals.edit-perte', arguments: { perte: {{ $perte }} } })"
                                            type="button" class="btn btn-sm btn-light" title="Modifier">
                                            <i class="fa fa-fw fa-pencil-alt"></i>
                                        </button>
                                        @endcan
                                        @can('Supprimer Perte')
                                        <a wire:click.prevent="delete({{ $perte->id }})"
                                            wire:confirm="Supprimer cette déclaration de perte ? Le stock des produits concernés sera recrédité."
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
                                    Aucune perte enregistrée
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div>
                    {{ $pertes->links() }}
                </div>
            </div>
        </div>
    </div>

</div>
