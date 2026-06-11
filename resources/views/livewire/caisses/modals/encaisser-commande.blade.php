<div>
    <div class="block block-rounded block-themed mb-0">
        <div class="block-header bg-primary">
            <h3 class="block-title">
                <i class="fa fa-money-bill-wave mr-2"></i>
                Encaisser — {{ $commande->numero }}
            </h3>
            <div class="block-options">
                <button type="button" class="btn-block-option" wire:click="closeModal">
                    <i class="fa fa-fw fa-times"></i>
                </button>
            </div>
        </div>

        <div class="block-content">

            {{-- Récapitulatif de la commande --}}
            <div class="mb-4">
                @if ($commande->table_numero || $commande->client_nom)
                    <p class="text-muted mb-2">
                        @if ($commande->table_numero)
                            <i class="fa fa-utensils mr-1"></i> Table {{ $commande->table_numero }}
                        @endif
                        @if ($commande->client_nom)
                            <span class="ml-2">— {{ $commande->client_nom }}</span>
                        @endif
                    </p>
                @endif

                <table class="table table-sm table-borderless mb-0">
                    <tbody>
                        @foreach ($commande->items as $item)
                            <tr>
                                <td class="pl-0 text-muted font-size-sm">{{ (int) $item->quantite }}×</td>
                                <td class="font-size-sm">{{ $item->produit->name }}</td>
                                <td class="text-right pr-0 font-size-sm text-muted">
                                    {{ number_format($item->sous_total, 0, ',', ' ') }} FCFA
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="border-top">
                            <td colspan="2" class="pl-0 font-w700 font-size-h5">Total</td>
                            <td class="text-right pr-0 font-w700 font-size-h5 text-success">
                                {{ number_format($commande->montant_total, 0, ',', ' ') }} FCFA
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- Mode de paiement --}}
            <div class="form-group mb-3">
                <label class="font-w600">Mode de paiement</label>
                <div class="d-flex gap-3">
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio"name="mode_paiement" id="mode_especes" wire:model.live="mode_paiement"
                            value="especes" class="custom-control-input form-control">
                        <label class="custom-control-label" for="mode_especes">
                            <i class="fa fa-coins mr-1"></i> Espèces
                        </label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" name="mode_paiement" id="mode_mobile" wire:model.live="mode_paiement"
                            value="mobile_money" class="custom-control-input form-control">
                        <label class="custom-control-label" for="mode_mobile">
                            <i class="fa fa-mobile-alt mr-1"></i> Mobile Money
                        </label>
                    </div>
                </div>
                @error('mode_paiement')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            {{-- Champs selon le mode --}}
            @if ($mode_paiement === 'especes')
                <div class="form-group mb-3">
                    <label class="font-w600" for="montant_recu">Montant reçu (FCFA)</label>
                    <input type="number" id="montant_recu" wire:model.live="montant_recu"
                        class="form-control @error('montant_recu') is-invalid @enderror"
                        min="{{ $commande->montant_total }}" step="1" placeholder="0">
                    @error('montant_recu')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                @if ($monnaie_rendue > 0)
                    <div class="alert alert-success py-2 mb-3">
                        <i class="fa fa-coins mr-2"></i>
                        Monnaie à rendre : <strong>{{ number_format($monnaie_rendue, 0, ',', ' ') }} FCFA</strong>
                    </div>
                @endif
            @else
                <div class="form-group mb-3">
                    <label class="font-w600" for="reference_mobile">Référence transaction (optionnel)</label>
                    <input type="text" id="reference_mobile" wire:model="reference_mobile"
                        class="form-control @error('reference_mobile') is-invalid @enderror"
                        placeholder="Ex: TXN-123456">
                    @error('reference_mobile')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            @endif

            {{-- Note --}}
            <div class="form-group mb-3">
                <label for="note_enc">Note (optionnel)</label>
                <textarea id="note_enc" wire:model="note" rows="2"
                    class="form-control" placeholder="Remarque sur ce paiement..."></textarea>
            </div>

        </div>

        <div class="block-content block-content-full bg-body-light d-flex justify-content-between">
            <button type="button" wire:click="closeModal" class="btn btn-alt-secondary">
                <i class="fa fa-fw fa-arrow-left"></i> Retour
            </button>
            <button type="button" wire:click="encaisser"
                wire:loading.attr="disabled"
                class="btn btn-success">
                <span wire:loading.remove wire:target="encaisser">
                    <i class="fa fa-fw fa-check"></i> Valider l'encaissement
                </span>
                <span wire:loading wire:target="encaisser">
                    <i class="fa fa-fw fa-spinner fa-spin"></i> Traitement...
                </span>
            </button>
        </div>
    </div>
</div>
