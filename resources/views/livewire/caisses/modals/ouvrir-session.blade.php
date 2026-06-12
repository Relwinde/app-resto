<div>
    <form wire:submit.prevent="ouvrir">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Ouvrir une session de caisse</h3>
                <div class="block-options">
                    @can('Ouvrir Session Caisse')
                    <button type="submit" class="btn btn-sm btn-success">
                        <i class="fa fa-fw fa-unlock"></i> Ouvrir
                    </button>
                    @endcan
                    <div wire:loading wire:target="ouvrir" class="spinner-border spinner-border-sm text-success" role="status">
                        <span class="sr-only">Chargement...</span>
                    </div>
                    <button type="button" wire:click='$dispatch("closeModal")' class="btn btn-sm btn-alt-secondary">
                        Annuler
                    </button>
                </div>
            </div>

            <div class="block-content">
                <div class="py-sm-3 py-md-4">

                    {{-- Soldes actuels (lecture seule) --}}
                    <div class="row mb-4">
                        <div class="col-sm-6">
                            <div class="bg-body-light rounded p-3 text-center">
                                <p class="text-muted small mb-1">
                                    <i class="fa fa-coins text-warning mr-1"></i> Solde Espèces
                                </p>
                                <p class="font-w700 font-size-lg mb-0">
                                    {{ number_format($soldeEspeces, 0, ',', ' ') }} <small class="font-w400">FCFA</small>
                                </p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="bg-body-light rounded p-3 text-center">
                                <p class="text-muted small mb-1">
                                    <i class="fa fa-mobile-alt text-primary mr-1"></i> Solde Mobile Money
                                </p>
                                <p class="font-w700 font-size-lg mb-0">
                                    {{ number_format($soldeMobile, 0, ',', ' ') }} <small class="font-w400">FCFA</small>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-0">
                        <label for="note_ouverture">Note (optionnel)</label>
                        <textarea wire:model="note_ouverture" class="form-control form-control-alt"
                            id="note_ouverture" rows="2" placeholder="Remarques..."></textarea>
                        @error('note_ouverture')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                </div>
            </div>
        </div>
    </form>
</div>
