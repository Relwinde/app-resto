<div>
    @include('partials.pages.header', ['pageHeader' => $pageHeader])

    <div class="content">

        @if ($caisse)
            <div class="row mb-4">
                <div class="col-sm-4">
                    <div class="block block-rounded text-center py-3 mb-0">
                        <p class="text-muted small mb-1">Caisse</p>
                        <p class="font-w700 mb-0">{{ $caisse->nom }}</p>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="block block-rounded text-center py-3 mb-0">
                        <p class="text-muted small mb-1">Solde actuel</p>
                        <p class="font-w700 font-size-h4 text-success mb-0">
                            {{ number_format($caisse->solde_actuel, 0, ',', ' ') }} FCFA
                        </p>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="block block-rounded text-center py-3 mb-0">
                        <p class="text-muted small mb-1">Session</p>
                        <p class="mb-0">
                            @if ($caisse->sessionActive())
                                <span class="badge badge-success">Ouverte</span>
                            @else
                                <span class="badge badge-secondary">Fermée</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Mouvements</h3>
            </div>
            <div class="block-content">

                <div class="row mb-3">
                    <div class="col-sm-3">
                        <select wire:model.live="caisse_id" class="form-control form-control-alt">
                            <option value="">Toutes les caisses</option>
                            @foreach ($caisses as $c)
                                <option value="{{ $c->id }}">{{ $c->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <select wire:model.live="type" class="form-control form-control-alt">
                            <option value="">Tous les types</option>
                            <option value="encaissement">Encaissement</option>
                            <option value="ouverture">Ouverture</option>
                            <option value="fermeture">Fermeture</option>
                            <option value="depot">Dépôt</option>
                            <option value="retrait">Retrait</option>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <input wire:model.live="dateMin" type="date" class="form-control form-control-alt">
                    </div>
                    <div class="col-sm-2">
                        <input wire:model.live="dateMax" type="date" class="form-control form-control-alt">
                    </div>
                    <div class="col-sm-3">
                        @if ($caisse_id || $type || $dateMin || $dateMax)
                            <button type="button" wire:click="clear_filtres" class="btn btn-alt-secondary">
                                <i class="fa fa-filter mr-1"></i> Effacer les filtres
                            </button>
                        @endif
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-vcenter font-size-sm">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Commande</th>
                                <th>Mode</th>
                                <th class="text-right">Solde avant</th>
                                <th class="text-right">Montant</th>
                                <th class="text-right">Solde après</th>
                                <th>Caissier</th>
                                <th>Note</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($mouvements as $mouvement)
                                <tr>
                                    <td>{{ $mouvement->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        @switch($mouvement->type)
                                            @case('encaissement')
                                                <span class="badge badge-success">Encaissement</span>
                                                @break
                                            @case('ouverture')
                                                <span class="badge badge-info">Ouverture</span>
                                                @break
                                            @case('fermeture')
                                                <span class="badge badge-secondary">Fermeture</span>
                                                @break
                                            @case('depot')
                                                <span class="badge badge-primary">Dépôt</span>
                                                @break
                                            @case('retrait')
                                                <span class="badge badge-warning">Retrait</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>
                                        @if ($mouvement->commande)
                                            <span class="font-size-xs">{{ $mouvement->commande->numero }}</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($mouvement->mode_paiement === 'especes')
                                            <i class="fa fa-money-bill-wave text-muted"></i> Espèces
                                        @elseif ($mouvement->mode_paiement === 'mobile_money')
                                            <i class="fa fa-mobile-alt text-muted"></i> Mobile
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="text-right">{{ number_format($mouvement->solde_avant, 0, ',', ' ') }}</td>
                                    <td class="text-right font-w600 {{ $mouvement->type === 'retrait' ? 'text-danger' : 'text-success' }}">
                                        {{ $mouvement->type === 'retrait' ? '-' : '+' }}{{ number_format($mouvement->montant, 0, ',', ' ') }}
                                    </td>
                                    <td class="text-right font-w600">{{ number_format($mouvement->solde_apres, 0, ',', ' ') }}</td>
                                    <td>{{ $mouvement->user->name }}</td>
                                    <td><span class="text-muted">{{ $mouvement->note ?? '—' }}</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">Aucun mouvement enregistré.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $mouvements->links() }}
                </div>

            </div>
        </div>
    </div>
</div>
