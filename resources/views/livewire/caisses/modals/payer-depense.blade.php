<div>
    <form wire:submit.prevent="payer">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Payer le bon de caisse</h3>
                <div class="block-options">
                    <button type="submit" class="btn btn-sm btn-success">
                        <i class="fa fa-fw fa-money-bill-wave"></i> Confirmer le paiement
                    </button>
                    <div wire:loading wire:target="payer" class="spinner-border spinner-border-sm text-success" role="status">
                        <span class="sr-only">Chargement...</span>
                    </div>
                    <button type="button" wire:click='$dispatch("closeModal")' class="btn btn-sm btn-alt-secondary">
                        Annuler
                    </button>
                </div>
            </div>

            <div class="block-content">
                <div class="py-sm-3 py-md-4">

                    @if ($depense)
                    {{-- Récapitulatif --}}
                    <div class="bg-body-light rounded p-3 mb-4">
                        <div class="row">
                            <div class="col-sm-4">
                                <p class="text-muted small mb-1">Motif</p>
                                <p class="font-w600 mb-0">{{ $depense->motif }}</p>
                            </div>
                            @if ($depense->beneficiaire)
                            <div class="col-sm-4">
                                <p class="text-muted small mb-1">Bénéficiaire</p>
                                <p class="font-w600 mb-0">{{ $depense->beneficiaire }}</p>
                            </div>
                            @endif
                            <div class="col-sm-4">
                                <p class="text-muted small mb-1">Montant</p>
                                <p class="font-w700 font-size-lg text-danger mb-0">
                                    {{ number_format($depense->montant, 0, ',', ' ') }} <small class="font-w400">FCFA</small>
                                </p>
                            </div>
                        </div>
                        @if ($depense->validePar)
                        <div class="mt-2 pt-2 border-top">
                            <p class="text-muted small mb-0">
                                Validé par <strong>{{ $depense->validePar->name }}</strong>
                                le {{ $depense->valide_le?->format('d/m/Y à H:i') }}
                            </p>
                        </div>
                        @endif
                    </div>

                    {{-- Sélection caisse --}}
                    <div class="form-group mb-0">
                        <label for="caisse_id">Caisse de paiement <span class="text-danger">*</span></label>
                        <select wire:model="caisse_id" id="caisse_id"
                            class="form-control form-control-alt @error('caisse_id') is-invalid @enderror">
                            <option value="">-- Sélectionner une caisse --</option>
                            @foreach ($caisses as $caisse)
                                <option value="{{ $caisse->id }}">
                                    {{ $caisse->nom }} — {{ number_format($caisse->solde_actuel, 0, ',', ' ') }} FCFA
                                </option>
                            @endforeach
                        </select>
                        @error('caisse_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </form>
</div>
