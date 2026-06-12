<div>
    <form wire:submit.prevent="annuler">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Annuler la commande {{ $commande->numero }}</h3>
                <div class="block-options">
                    @can('Annuler Commande')
                    <button type="submit" class="btn btn-sm btn-danger">
                        <i class="fa fa-fw fa-ban"></i> Confirmer l'annulation
                    </button>
                    @endcan
                    <div wire:loading wire:target="annuler" class="spinner-border spinner-border-sm text-danger" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <button type="button" wire:click='$dispatch("closeModal")' class="btn btn-sm btn-alt-secondary">
                        Retour
                    </button>
                </div>
            </div>

            <div class="block-content">
                <div class="py-sm-3 py-md-4">

                    <div class="alert alert-warning mb-4">
                        <i class="fa fa-exclamation-triangle mr-2"></i>
                        Vous êtes sur le point d'annuler la commande <strong>{{ $commande->numero }}</strong>
                        d'un montant de <strong>{{ number_format($commande->montant_total, 0, ',', ' ') }} FCFA</strong>.
                        Cette action est irréversible.
                    </div>

                    <div class="form-group mb-0">
                        <label for="motif">Motif d'annulation (optionnel)</label>
                        <textarea wire:model="motif" class="form-control form-control-alt"
                            id="motif" rows="3" placeholder="Raison de l'annulation..."></textarea>
                        @error('motif') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                </div>
            </div>
        </div>
    </form>
</div>
