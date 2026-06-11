<div>
    <form wire:submit.prevent="fermer">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Fermer la session de caisse</h3>
                <div class="block-options">
                    <button type="submit" class="btn btn-sm btn-danger">
                        <i class="fa fa-fw fa-lock"></i> Fermer
                    </button>
                    <div wire:loading wire:target="fermer" class="spinner-border spinner-border-sm text-danger" role="status">
                        <span class="sr-only">Chargement...</span>
                    </div>
                    <button type="button" wire:click='$dispatch("closeModal")' class="btn btn-sm btn-alt-secondary">
                        Annuler
                    </button>
                </div>
            </div>

            <div class="block-content">
                <div class="py-sm-3 py-md-4">

                    {{-- Récapitulatif de session --}}
                    <div class="row text-center mb-4">
                        <div class="col-sm-4">
                            <p class="text-muted small mb-1">Commandes payées</p>
                            <p class="font-w700 font-size-lg mb-0">{{ $nbCommandes }}</p>
                        </div>
                        <div class="col-sm-4">
                            <p class="text-muted small mb-1">
                                <i class="fa fa-coins text-warning mr-1"></i> Total espèces
                            </p>
                            <p class="font-w700 font-size-lg text-success mb-0">
                                {{ number_format($totalEspeces, 0, ',', ' ') }} <small class="font-w400">FCFA</small>
                            </p>
                        </div>
                        <div class="col-sm-4">
                            <p class="text-muted small mb-1">
                                <i class="fa fa-mobile-alt text-primary mr-1"></i> Total mobile
                            </p>
                            <p class="font-w700 font-size-lg text-success mb-0">
                                {{ number_format($totalMobile, 0, ',', ' ') }} <small class="font-w400">FCFA</small>
                            </p>
                        </div>
                    </div>

                    {{-- Soldes actuels --}}
                    <div class="row mb-4">
                        <div class="col-sm-6">
                            <div class="bg-body-light rounded p-3 text-center">
                                <p class="text-muted small mb-1"><i class="fa fa-coins text-warning mr-1"></i> Solde espèces</p>
                                <p class="font-w700 mb-0">{{ number_format($soldeEspeces, 0, ',', ' ') }} FCFA</p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="bg-body-light rounded p-3 text-center">
                                <p class="text-muted small mb-1"><i class="fa fa-mobile-alt text-primary mr-1"></i> Solde mobile</p>
                                <p class="font-w700 mb-0">{{ number_format($soldeMobile, 0, ',', ' ') }} FCFA</p>
                            </div>
                        </div>
                    </div>

                    {{-- Fonds de fermeture --}}
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="fond_fermeture_especes">
                                    <i class="fa fa-coins text-warning mr-1"></i>
                                    Fond de fermeture — Espèces (FCFA) <span class="text-danger">*</span>
                                </label>
                                <input wire:model.live="fond_fermeture_especes" type="number" step="1" min="0"
                                    class="form-control form-control-alt @error('fond_fermeture_especes') is-invalid @enderror"
                                    id="fond_fermeture_especes" placeholder="0">
                                @error('fond_fermeture_especes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if ($fond_fermeture_especes !== '' && is_numeric($fond_fermeture_especes))
                                    @php $ecartE = (float) $fond_fermeture_especes - $soldeEspeces @endphp
                                    <div class="mt-1 small {{ $ecartE >= 0 ? 'text-success' : 'text-danger' }}">
                                        Écart : {{ $ecartE >= 0 ? '+' : '' }}{{ number_format($ecartE, 0, ',', ' ') }} FCFA
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="fond_fermeture_mobile">
                                    <i class="fa fa-mobile-alt text-primary mr-1"></i>
                                    Fond de fermeture — Mobile Money (FCFA) <span class="text-danger">*</span>
                                </label>
                                <input wire:model.live="fond_fermeture_mobile" type="number" step="1" min="0"
                                    class="form-control form-control-alt @error('fond_fermeture_mobile') is-invalid @enderror"
                                    id="fond_fermeture_mobile" placeholder="0">
                                @error('fond_fermeture_mobile')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if ($fond_fermeture_mobile !== '' && is_numeric($fond_fermeture_mobile))
                                    @php $ecartM = (float) $fond_fermeture_mobile - $soldeMobile @endphp
                                    <div class="mt-1 small {{ $ecartM >= 0 ? 'text-success' : 'text-danger' }}">
                                        Écart : {{ $ecartM >= 0 ? '+' : '' }}{{ number_format($ecartM, 0, ',', ' ') }} FCFA
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-0">
                        <label for="note_fermeture">Note (optionnel)</label>
                        <textarea wire:model="note_fermeture" class="form-control form-control-alt"
                            id="note_fermeture" rows="2" placeholder="Remarques..."></textarea>
                        @error('note_fermeture')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                </div>
            </div>
        </div>
    </form>
</div>
