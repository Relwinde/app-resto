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
                        <span class="sr-only">Loading...</span>
                    </div>
                    <button type="button" wire:click='$dispatch("closeModal")' class="btn btn-sm btn-alt-secondary">
                        Annuler
                    </button>
                </div>
            </div>

            <div class="block-content">
                <div class="py-sm-3 py-md-4">

                    <div class="form-group">
                        <label for="caisse_id">Caisse <span class="text-danger">*</span></label>
                        <select wire:model="caisse_id" class="form-control form-control-alt" id="caisse_id">
                            <option value="">-- Sélectionner --</option>
                            @foreach ($caisses as $caisse)
                                <option value="{{ $caisse->id }}">{{ $caisse->nom }}</option>
                            @endforeach
                        </select>
                        @error('caisse_id') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label for="fond_ouverture">Fond d'ouverture (FCFA) <span class="text-danger">*</span></label>
                        <input wire:model="fond_ouverture" type="number" step="1" min="0"
                            class="form-control form-control-alt" id="fond_ouverture" placeholder="0">
                        @error('fond_ouverture') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group mb-0">
                        <label for="note_ouverture">Note (optionnel)</label>
                        <textarea wire:model="note_ouverture" class="form-control form-control-alt"
                            id="note_ouverture" rows="2" placeholder="Remarques..."></textarea>
                        @error('note_ouverture') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                </div>
            </div>
        </div>
    </form>
</div>
