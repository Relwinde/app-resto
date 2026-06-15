<div>
    <form wire:submit.prevent="creerDepot">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Ajouter un dépôt</h3>
                <div class="block-options">
                    <button type="submit" class="btn btn-sm btn-success">
                        <i class="fa fa-fw fa-plus"></i> Enregistrer le dépôt
                    </button>
                    <div wire:loading wire:target="creerDepot" class="spinner-border spinner-border-sm text-success" role="status">
                        <span class="sr-only">Chargement...</span>
                    </div>
                    <button type="button" wire:click='$dispatch("closeModal")' class="btn btn-sm btn-alt-secondary">
                        Annuler
                    </button>
                </div>
            </div>

            <div class="block-content">
                <div class="py-sm-3 py-md-4">

                    {{-- Montant --}}
                    <div class="form-group">
                        <label for="montant">Montant <span class="text-danger">*</span></label>
                        <input type="number" wire:model="montant" id="montant"
                            class="form-control form-control-alt @error('montant') is-invalid @enderror"
                            placeholder="Montant du dépôt" min="1" step="1">
                        @error('montant')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Caisse --}}
                    <div class="form-group">
                        <label for="caisse_id">Caisse <span class="text-danger">*</span></label>
                        <select wire:model="caisse_id" id="caisse_id"
                            class="form-control form-control-alt @error('caisse_id') is-invalid @enderror">
                            <option value="">-- Sélectionner une caisse --</option>
                            @foreach ($caisses as $caisse)
                                <option value="{{ $caisse->id }}">
                                    {{ $caisse->nom }} ({{ number_format($caisse->solde_actuel, 0, ',', ' ') }} FCFA)
                                </option>
                            @endforeach
                        </select>
                        @error('caisse_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Note --}}
                    <div class="form-group mb-0">
                        <label for="note">Note (optionnel)</label>
                        <textarea wire:model="note" id="note"
                            class="form-control form-control-alt @error('note') is-invalid @enderror"
                            placeholder="Ex: Apport initial, Retour de dépôt bancaire..."
                            rows="3"></textarea>
                        @error('note')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>
            </div>
        </div>
    </form>
</div>
