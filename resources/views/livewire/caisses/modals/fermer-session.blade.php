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
                        <span class="sr-only">Loading...</span>
                    </div>
                    <button type="button" wire:click='$dispatch("closeModal")' class="btn btn-sm btn-alt-secondary">
                        Annuler
                    </button>
                </div>
            </div>

            <div class="block-content">
                <div class="py-sm-3 py-md-4">

                    <div class="row mb-4">
                        <div class="col-sm-4 text-center">
                            <p class="text-muted small mb-1">Commandes payées</p>
                            <p class="font-w700 font-size-lg mb-0">{{ $nbCommandes }}</p>
                        </div>
                        <div class="col-sm-4 text-center">
                            <p class="text-muted small mb-1">Total encaissé</p>
                            <p class="font-w700 font-size-lg text-success mb-0">{{ number_format($totalEncaisse, 0, ',', ' ') }} <small class="font-w400">FCFA</small></p>
                        </div>
                        <div class="col-sm-4 text-center">
                            <p class="text-muted small mb-1">Solde actuel caisse</p>
                            <p class="font-w700 font-size-lg mb-0">{{ number_format($soldeCaisse, 0, ',', ' ') }} <small class="font-w400">FCFA</small></p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="fond_fermeture">Fond de fermeture (FCFA) <span class="text-danger">*</span></label>
                        <input wire:model.live="fond_fermeture" type="number" step="1" min="0"
                            class="form-control form-control-alt" id="fond_fermeture" placeholder="0">
                        @error('fond_fermeture') <div class="text-danger small">{{ $message }}</div> @enderror
                        @if ($fond_fermeture !== '' && is_numeric($fond_fermeture))
                            @php $ecart = (float) $fond_fermeture - $soldeCaisse @endphp
                            <div class="mt-1 small {{ $ecart >= 0 ? 'text-success' : 'text-danger' }}">
                                Écart : {{ $ecart >= 0 ? '+' : '' }}{{ number_format($ecart, 0, ',', ' ') }} FCFA
                            </div>
                        @endif
                    </div>

                    <div class="form-group mb-0">
                        <label for="note_fermeture">Note (optionnel)</label>
                        <textarea wire:model="note_fermeture" class="form-control form-control-alt"
                            id="note_fermeture" rows="2" placeholder="Remarques..."></textarea>
                        @error('note_fermeture') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                </div>
            </div>
        </div>
    </form>
</div>
