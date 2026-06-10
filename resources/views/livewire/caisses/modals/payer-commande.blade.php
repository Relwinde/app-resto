<div>
    <form wire:submit.prevent="payer">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Encaissement</h3>
                <div class="block-options">
                    <button type="submit" class="btn btn-sm btn-success">
                        <i class="fa fa-fw fa-check"></i> Valider
                    </button>
                    <div wire:loading wire:target="payer" class="spinner-border spinner-border-sm text-success" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <button type="button" wire:click='$dispatch("closeModal")' class="btn btn-sm btn-alt-secondary">
                        Annuler
                    </button>
                </div>
            </div>

            <div class="block-content">
                <div class="py-sm-3 py-md-4">

                    {{-- Récap panier --}}
                    <div class="mb-4">
                        <table class="table table-sm table-borderless mb-2">
                            <tbody>
                                @foreach ($panier as $item)
                                    <tr>
                                        <td class="pl-0">{{ $item['nom'] }}</td>
                                        <td class="text-center">×{{ $item['quantite'] }}</td>
                                        <td class="text-right pr-0">{{ number_format($item['sous_total'], 0, ',', ' ') }} FCFA</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-between border-top pt-2 font-w700 font-size-h5">
                            <span>TOTAL</span>
                            <span class="text-success">{{ number_format($total, 0, ',', ' ') }} FCFA</span>
                        </div>
                    </div>

                    {{-- Infos commande --}}
                    @if ($table_numero || $client_nom)
                        <div class="mb-3 text-muted small">
                            @if ($table_numero) <span><i class="fa fa-utensils mr-1"></i> Table {{ $table_numero }}</span> @endif
                            @if ($table_numero && $client_nom) &mdash; @endif
                            @if ($client_nom) <span><i class="fa fa-user mr-1"></i> {{ $client_nom }}</span> @endif
                        </div>
                    @endif

                    {{-- Mode de paiement --}}
                    <div class="form-group">
                        <label>Mode de paiement <span class="text-danger">*</span></label>
                        <div class="d-flex gap-2">
                            <div class="custom-control custom-radio mr-3">
                                <input type="radio" wire:model.live="mode_paiement" value="especes"
                                    id="mode_especes" class="custom-control-input">
                                <label class="custom-control-label" for="mode_especes">
                                    <i class="fa fa-money-bill-wave mr-1"></i> Espèces
                                </label>
                            </div>
                            <div class="custom-control custom-radio">
                                <input type="radio" wire:model.live="mode_paiement" value="mobile_money"
                                    id="mode_mobile" class="custom-control-input">
                                <label class="custom-control-label" for="mode_mobile">
                                    <i class="fa fa-mobile-alt mr-1"></i> Mobile Money
                                </label>
                            </div>
                        </div>
                        @error('mode_paiement') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    {{-- Espèces --}}
                    @if ($mode_paiement === 'especes')
                        <div class="form-group">
                            <label for="montant_recu">Montant reçu (FCFA) <span class="text-danger">*</span></label>
                            <input wire:model.live="montant_recu" type="number" step="1" min="{{ $total }}"
                                class="form-control form-control-alt" id="montant_recu"
                                placeholder="{{ number_format($total, 0) }}">
                            @error('montant_recu') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>
                        @if ($montant_recu !== '' && is_numeric($montant_recu) && (float) $montant_recu >= $total)
                            <div class="alert alert-success py-2 text-center font-w700">
                                Monnaie à rendre : {{ number_format($monnaie_rendue, 0, ',', ' ') }} FCFA
                            </div>
                        @endif
                    @endif

                    {{-- Mobile Money --}}
                    @if ($mode_paiement === 'mobile_money')
                        <div class="form-group">
                            <label for="reference_mobile">Référence de transaction</label>
                            <input wire:model="reference_mobile" type="text"
                                class="form-control form-control-alt" id="reference_mobile"
                                placeholder="Numéro de transaction / ref...">
                            @error('reference_mobile') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>
                    @endif

                    <div class="form-group mb-0">
                        <label for="note">Note (optionnel)</label>
                        <input wire:model="note" type="text" class="form-control form-control-alt"
                            id="note" placeholder="Remarque...">
                    </div>

                </div>
            </div>
        </div>
    </form>
</div>
