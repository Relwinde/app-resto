<div>
    <form wire:submit.prevent="ouvrir">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Ouvrir une session de caisse</h3>
                <div class="block-options">
                    <button type="submit" class="btn btn-sm btn-success">
                        <i class="fa fa-fw fa-unlock"></i> Ouvrir
                    </button>
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

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="fond_especes">
                                    <i class="fa fa-coins text-warning mr-1"></i>
                                    Fond d'ouverture — Espèces (FCFA) <span class="text-danger">*</span>
                                </label>
                                <input wire:model="fond_especes" type="number" step="1" min="0"
                                    class="form-control form-control-alt @error('fond_especes') is-invalid @enderror"
                                    id="fond_especes" placeholder="0">
                                @error('fond_especes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="fond_mobile">
                                    <i class="fa fa-mobile-alt text-primary mr-1"></i>
                                    Fond d'ouverture — Mobile Money (FCFA) <span class="text-danger">*</span>
                                </label>
                                <input wire:model="fond_mobile" type="number" step="1" min="0"
                                    class="form-control form-control-alt @error('fond_mobile') is-invalid @enderror"
                                    id="fond_mobile" placeholder="0">
                                @error('fond_mobile')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
