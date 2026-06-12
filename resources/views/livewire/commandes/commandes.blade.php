<div>
    @include('partials.pages.header', ['pageHeader' => $pageHeader])

    <div class="content">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Liste des commandes</h3>
            </div>
            <div class="block-content">

                <div class="row mb-3">
                    <div class="col-sm-4">
                        <div class="input-group">
                            <input wire:model.live="search" type="search"
                                class="form-control form-control-alt"
                                placeholder="N° commande, client, table...">
                            @if ($search)
                                <div class="input-group-append">
                                    <button type="button" wire:click="clear_search" class="btn btn-alt-secondary">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <select wire:model.live="statut" class="form-control form-control-alt">
                            <option value="">Tous les statuts</option>
                            <option value="en_attente">En attente</option>
                            <option value="en_preparation">En préparation</option>
                            <option value="servie">Servie</option>
                            <option value="payee">Payée</option>
                            <option value="annulee">Annulée</option>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <input wire:model.live="dateMin" type="date" class="form-control form-control-alt"
                            placeholder="Date début">
                    </div>
                    <div class="col-sm-2">
                        <input wire:model.live="dateMax" type="date" class="form-control form-control-alt"
                            placeholder="Date fin">
                    </div>
                    <div class="col-sm-2">
                        @if ($search || $statut || $dateMin || $dateMax)
                            <button type="button" wire:click="clear_search" class="btn btn-alt-secondary btn-block">
                                <i class="fa fa-filter mr-1"></i> Effacer
                            </button>
                        @endif
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-vcenter font-size-sm">
                        <thead>
                            <tr>
                                <th>N° Commande</th>
                                <th>Table / Client</th>
                                <th class="text-center">Articles</th>
                                <th class="text-right">Total</th>
                                <th>Caissier</th>
                                <th>Date</th>
                                <th class="text-center">Statut</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($commandes as $commande)
                                <tr>
                                    <td><span class="font-w600">{{ $commande->numero }}</span></td>
                                    <td>
                                        @if ($commande->table_numero)
                                            <span class="text-muted">Table {{ $commande->table_numero }}</span>
                                        @endif
                                        @if ($commande->client_nom)
                                            <div class="font-size-xs text-muted">{{ $commande->client_nom }}</div>
                                        @endif
                                        @if (! $commande->table_numero && ! $commande->client_nom)
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $commande->items->count() }}</td>
                                    <td class="text-right font-w600">{{ number_format($commande->montant_total, 0, ',', ' ') }} FCFA</td>
                                    <td>{{ $commande->user->name }}</td>
                                    <td>{{ $commande->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="text-center">
                                        @switch($commande->statut)
                                            @case('en_attente')
                                                <span class="badge badge-warning">En attente</span>
                                                @break
                                            @case('en_preparation')
                                                <span class="badge badge-info">En préparation</span>
                                                @break
                                            @case('servie')
                                                <span class="badge badge-primary">Servie</span>
                                                @break
                                            @case('payee')
                                                <span class="badge badge-success">Payée</span>
                                                @break
                                            @case('annulee')
                                                <span class="badge badge-danger">Annulée</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td class="text-center">
                                        @can('Voir Détail Commande')
                                        <button type="button"
                                            wire:click="$dispatch('openModal', { component: 'commandes.modals.show-commande', arguments: { commande: {{ $commande->id }} } })"
                                            class="btn btn-sm btn-alt-secondary" title="Voir">
                                            <i class="fa fa-fw fa-eye"></i>
                                        </button>
                                        @endcan
                                        @can('Annuler Commande')
                                        @if ($commande->estModifiable())
                                            <button type="button"
                                                wire:click="$dispatch('openModal', { component: 'commandes.modals.annuler-commande', arguments: { commande: {{ $commande->id }} } })"
                                                class="btn btn-sm btn-alt-danger" title="Annuler">
                                                <i class="fa fa-fw fa-ban"></i>
                                            </button>
                                        @endif
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">Aucune commande trouvée.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $commandes->links() }}
                </div>
            </div>
        </div>
    </div>

</div>
