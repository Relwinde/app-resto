<div>
    <form wire:submit.prevent="fermer">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Fermer la session de caisse</h3>
                <div class="block-options">
                    @can('Fermer Session Caisse')
                    <button type="submit" class="btn btn-sm btn-danger">
                        <i class="fa fa-fw fa-lock"></i> Fermer
                    </button>
                    @endcan
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

                    {{-- Soldes de fermeture (lecture seule) --}}
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
