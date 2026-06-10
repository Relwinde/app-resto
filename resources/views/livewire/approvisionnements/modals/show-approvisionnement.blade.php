<div>
    <div class="block block-rounded mb-0">
        <div class="block-header block-header-default">
            <h3 class="block-title">Détails de l'approvisionnement</h3>
            <div class="block-options">
                <button type="button" wire:click='$dispatch("closeModal")' class="btn btn-sm btn-alt-primary">
                    Fermer
                </button>
            </div>
        </div>

        <div class="block-content">
            <div class="py-sm-3 py-md-4">

                <div class="row mb-4">
                    <div class="col-sm-6">
                        <p class="font-w600 mb-1">Produit</p>
                        <p class="text-muted mb-0">{{ $approvisionnement->product?->name ?? '—' }}</p>
                    </div>
                    <div class="col-sm-6">
                        <p class="font-w600 mb-1">Fournisseur</p>
                        <p class="text-muted mb-0">{{ $approvisionnement->fournisseur?->name ?? '—' }}</p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-sm-6">
                        <p class="font-w600 mb-1">Quantité reçue</p>
                        <p class="text-muted mb-0">
                            {{ number_format($approvisionnement->quantite, 2, ',', ' ') }}
                            <span class="text-muted">{{ $approvisionnement->product?->unite }}</span>
                        </p>
                    </div>
                    <div class="col-sm-6">
                        <p class="font-w600 mb-1">Prix d'achat unitaire</p>
                        <p class="text-muted mb-0">
                            {{ $approvisionnement->prix_achat ? number_format($approvisionnement->prix_achat, 0, ',', ' ') . ' FCFA' : '—' }}
                        </p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-sm-6">
                        <p class="font-w600 mb-1">Date de péremption</p>
                        <p class="text-muted mb-0">
                            {{ $approvisionnement->date_peremption ? $approvisionnement->date_peremption->format('d/m/Y') : '—' }}
                        </p>
                    </div>
                    <div class="col-sm-6">
                        <p class="font-w600 mb-1">Numéro de lot</p>
                        <p class="text-muted mb-0">{{ $approvisionnement->numero_lot ?? '—' }}</p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-12">
                        <p class="font-w600 mb-1">Note</p>
                        <p class="text-muted mb-0">{{ $approvisionnement->note ?? '—' }}</p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-12">
                        <p class="font-w600 mb-2">Fichiers joints</p>
                        @forelse ($approvisionnement->files as $file)
                            <a href="{{ route('files.download', $file) }}"
                               class="btn btn-sm btn-alt-secondary mb-1" target="_blank">
                                <i class="fa fa-fw fa-paperclip"></i>
                                {{ $file->original_name }}
                                <small class="text-muted">({{ number_format($file->size / 1024, 1) }} Ko)</small>
                            </a>
                        @empty
                            <p class="text-muted mb-0">Aucun fichier joint</p>
                        @endforelse
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <p class="font-w600 mb-1">Date d'enregistrement</p>
                        <p class="text-muted mb-0">{{ $approvisionnement->created_at->format('d/m/Y à H:i') }}</p>
                    </div>
                    <div class="col-sm-6">
                        <p class="font-w600 mb-1">Dernière modification</p>
                        <p class="text-muted mb-0">{{ $approvisionnement->updated_at->format('d/m/Y à H:i') }}</p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
