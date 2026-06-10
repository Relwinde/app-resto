<div>
    <div class="block block-rounded mb-0">
        <div class="block-header block-header-default">
            <h3 class="block-title">Commande {{ $commande->numero }}</h3>
            <div class="block-options">
                <button type="button" wire:click='$dispatch("closeModal")' class="btn btn-sm btn-alt-secondary">
                    Fermer
                </button>
            </div>
        </div>

        <div class="block-content">
            <div class="py-sm-3 py-md-4">

                <div class="row mb-4">
                    <div class="col-sm-3">
                        <p class="font-w600 mb-1">Caisse</p>
                        <p class="text-muted mb-0">{{ $commande->caisse->nom }}</p>
                    </div>
                    <div class="col-sm-3">
                        <p class="font-w600 mb-1">Caissier</p>
                        <p class="text-muted mb-0">{{ $commande->user->name }}</p>
                    </div>
                    <div class="col-sm-3">
                        <p class="font-w600 mb-1">Table / Client</p>
                        <p class="text-muted mb-0">
                            {{ $commande->table_numero ? 'Table ' . $commande->table_numero : '' }}
                            {{ $commande->client_nom ?? '' }}
                            {{ ! $commande->table_numero && ! $commande->client_nom ? '—' : '' }}
                        </p>
                    </div>
                    <div class="col-sm-3">
                        <p class="font-w600 mb-1">Statut</p>
                        <p class="mb-0">
                            @switch($commande->statut)
                                @case('en_attente') <span class="badge badge-warning">En attente</span> @break
                                @case('en_preparation') <span class="badge badge-info">En préparation</span> @break
                                @case('servie') <span class="badge badge-primary">Servie</span> @break
                                @case('payee') <span class="badge badge-success">Payée</span> @break
                                @case('annulee') <span class="badge badge-danger">Annulée</span> @break
                            @endswitch
                        </p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-12">
                        <p class="font-w600 mb-2">Articles</p>
                        <table class="table table-sm table-bordered mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Produit</th>
                                    <th class="text-center">Qté</th>
                                    <th class="text-right">Prix unit.</th>
                                    <th class="text-right">Sous-total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($commande->items as $item)
                                    <tr>
                                        <td>{{ $item->produit?->name ?? '—' }}</td>
                                        <td class="text-center">{{ number_format($item->quantite, 0) }}</td>
                                        <td class="text-right">{{ number_format($item->prix_unitaire, 0, ',', ' ') }}</td>
                                        <td class="text-right font-w600">{{ number_format($item->sous_total, 0, ',', ' ') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-right font-w700">TOTAL</td>
                                    <td class="text-right font-w700 text-success">{{ number_format($commande->montant_total, 0, ',', ' ') }} FCFA</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                @if ($commande->mouvement)
                    <div class="row mb-4">
                        <div class="col-12">
                            <p class="font-w600 mb-2">Paiement</p>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="text-muted small mb-1">Mode</p>
                                    <p class="mb-0">
                                        {{ $commande->mouvement->mode_paiement === 'especes' ? 'Espèces' : 'Mobile Money' }}
                                    </p>
                                </div>
                                @if ($commande->mouvement->montant_recu)
                                    <div class="col-sm-3">
                                        <p class="text-muted small mb-1">Reçu</p>
                                        <p class="mb-0">{{ number_format($commande->mouvement->montant_recu, 0, ',', ' ') }} FCFA</p>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="text-muted small mb-1">Monnaie rendue</p>
                                        <p class="mb-0">{{ number_format($commande->mouvement->monnaie_rendue, 0, ',', ' ') }} FCFA</p>
                                    </div>
                                @endif
                                @if ($commande->mouvement->reference_mobile)
                                    <div class="col-sm-3">
                                        <p class="text-muted small mb-1">Référence</p>
                                        <p class="mb-0">{{ $commande->mouvement->reference_mobile }}</p>
                                    </div>
                                @endif
                                <div class="col-sm-3">
                                    <p class="text-muted small mb-1">Date</p>
                                    <p class="mb-0">{{ $commande->mouvement->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($commande->note)
                    <div class="row">
                        <div class="col-12">
                            <p class="font-w600 mb-1">Note</p>
                            <p class="text-muted mb-0">{{ $commande->note }}</p>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
