<div>
    <form wire:submit.prevent="create">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Nouveau rôle</h3>
                <div class="block-options">
                    <button type="submit" class="btn btn-sm btn-primary">Enregistrer</button>
                    <div wire:loading wire:target="create" class="spinner-border spinner-border-sm text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <button type="button" wire:click='$dispatch("closeModal")' class="btn btn-sm btn-alt-secondary">Annuler</button>
                </div>
            </div>
            <div class="block-content">
                <div class="py-sm-3 py-md-4">
                    <div class="form-group mb-0">
                        <label for="name">Nom du rôle <span class="text-danger">*</span></label>
                        <input wire:model="name" type="text" class="form-control form-control-alt" id="name"
                            placeholder="ex : responsable-stock">
                        @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
