<div>
    <div class="block block-rounded mb-0">
        <div class="block-header block-header-default">
            <h3 class="block-title">Détails de la perte {{ $perte->numero }}</h3>
            <div class="block-options">
                <button type="button" wire:click='$dispatch("closeModal")' class="btn btn-sm btn-alt-primary">
                    Fermer
                </button>
            </div>
        </div>

        <div class="block-content">
            <div class="py-sm-3 py-md-4">

                <div class="row mb-4">
                    <div class="col-sm-4">
                        <p class="font-w600 mb-1">Motif</p>
                        <p class="text-muted mb-0">{{ $motifs[$perte->motif] ?? $perte->motif }}</p>
                    </div>
                    <div class="col-sm-4">
                        <p class="font-w600 mb-1">Valeur estimée</p>
                        <p class="text-muted mb-0">{{ number_format($perte->valeur_estimee, 0, ',', ' ') }} FCFA</p>
                    </div>
                    <div class="col-sm-4">
                        <p class="font-w600 mb-1">Déclaré par</p>
                        <p class="text-muted mb-0">{{ $perte->user?->name ?? '—' }}</p>
                    </div>
                </div>

                <div class="table-responsive mb-4">
                    <table class="table table-bordered table-striped table-vcenter">
                        <thead>
                            <tr>
                                <th>Produit</th>
                                <th class="text-right">Quantité perdue</th>
                                <th class="text-right">Valeur estimée</th>
                                <th>Note</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($perte->lignes as $ligne)
                                <tr>
                                    <td>{{ $ligne->product?->name ?? '—' }}</td>
                                    <td class="text-right">
                                        {{ number_format(abs($ligne->quantite), 2, ',', ' ') }}
                                        <small class="text-muted">{{ $ligne->product?->unite }}</small>
                                    </td>
                                    <td class="text-right">
                                        {{ number_format(abs($ligne->quantite) * (float) ($ligne->product?->prix_vente ?? 0), 0, ',', ' ') }}
                                    </td>
                                    <td>{{ $ligne->note ?? '—' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Aucun produit</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="row mb-4">
                    <div class="col-12">
                        <p class="font-w600 mb-1">Note</p>
                        <p class="text-muted mb-0">{{ $perte->note ?? '—' }}</p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-12">
                        <p class="font-w600 mb-2">Fichiers joints</p>
                        @forelse ($perte->files as $file)
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
                        <p class="font-w600 mb-1">Date de déclaration</p>
                        <p class="text-muted mb-0">{{ $perte->created_at->format('d/m/Y à H:i') }}</p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
