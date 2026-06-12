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

                    <div class="form-group">
                        <label for="note">Note</label>
                        <textarea wire:model="note" class="form-control form-control-alt @error('note') is-invalid @enderror"
                            id="note" rows="2"></textarea>
                        @error('note')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Fichiers déjà joints --}}
                    @if ($depense && $depense->files->isNotEmpty())
                        <div class="form-group">
                            <label>Fichiers joints</label>
                            <div>
                                @foreach ($depense->files as $file)
                                    <a href="{{ route('files.download', $file) }}" target="_blank"
                                        class="badge badge-light border mr-1 mb-1 font-size-sm">
                                        <i class="fa fa-paperclip mr-1"></i>{{ $file->original_name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="form-group mb-0">
                        <label for="fichier">Ajouter un fichier <small class="text-muted">(PDF ou image — max 10 Mo)</small></label>
                        <input wire:model="fichier" type="file" class="form-control-file"
                            id="fichier" accept=".pdf,.jpg,.jpeg,.png">
                        <div wire:loading wire:target="fichier" class="text-muted small mt-1">
                            <i class="fa fa-spinner fa-spin"></i> Chargement...
                        </div>
                        @error('fichier')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror

                        @if ($fichier)
                            @php $ext = strtolower(pathinfo($fichier->getClientOriginalName(), PATHINFO_EXTENSION)) @endphp
                            <div class="p-2 border rounded bg-body-light position-relative mt-2">
                                <button type="button" wire:click="removeFile"
                                    class="btn btn-sm btn-danger position-absolute"
                                    style="top: 6px; right: 6px; z-index: 1;" title="Retirer le fichier">
                                    <i class="fa fa-fw fa-times"></i>
                                </button>
                                @if (in_array($ext, ['jpg', 'jpeg', 'png']))
                                    <img src="{{ $fichier->temporaryUrl() }}" alt="Aperçu"
                                         class="img-fluid rounded d-block mx-auto" style="max-height: 220px; object-fit: contain;">
                                @elseif ($ext === 'pdf')
                                    <embed src="{{ $fichier->temporaryUrl() }}" type="application/pdf"
                                           width="100%" height="220px" class="rounded">
                                @endif
                                <p class="small text-muted mt-2 mb-0 text-center">
                                    <i class="fa fa-paperclip"></i> {{ $fichier->getClientOriginalName() }}
                                </p>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </form>
</div>
