<div>
    <form wire:submit.prevent="update">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Modifier le bon de caisse</h3>
                <div class="block-options">
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="fa fa-fw fa-save"></i> Enregistrer
                    </button>
                    <div wire:loading wire:target="update" class="spinner-border spinner-border-sm text-primary" role="status">
                        <span class="sr-only">Chargement...</span>
                    </div>
                    <button type="button" wire:click='$dispatch("closeModal")' class="btn btn-sm btn-alt-secondary">
                        Annuler
                    </button>
                </div>
            </div>

            <div class="block-content">
                <div class="py-sm-3 py-md-4">

                    <div class="form-group">
                        <label for="motif">Motif <span class="text-danger">*</span></label>
                        <input wire:model="motif" type="text" class="form-control form-control-alt @error('motif') is-invalid @enderror"
                            id="motif">
                        @error('motif')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="montant">Montant (FCFA) <span class="text-danger">*</span></label>
                        <input wire:model="montant" type="number" min="1" step="1"
                            class="form-control form-control-alt @error('montant') is-invalid @enderror"
                            id="montant">
                        @error('montant')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="beneficiaire">Bénéficiaire</label>
                        <input wire:model="beneficiaire" type="text" class="form-control form-control-alt @error('beneficiaire') is-invalid @enderror"
                            id="beneficiaire">
                        @error('beneficiaire')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-0">
                        <label for="note">Note</label>
                        <textarea wire:model="note" class="form-control form-control-alt @error('note') is-invalid @enderror"
                            id="note" rows="2"></textarea>
                        @error('note')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>
            </div>
        </div>
    </form>
</div>
