<div>
    <form wire:submit.prevent="create">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Nouvel Approvisionnement</h3>
                <div class="block-options">
                    <button type="submit" class="btn btn-sm btn-primary">
                        Enregistrer
                    </button>
                    <div wire:loading wire:target="create" class="spinner-border spinner-border-sm text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <button type="button" wire:click='$dispatch("closeModal")' class="btn btn-sm btn-alt-primary">
                        Annuler
                    </button>
                </div>
            </div>

            <div class="block-content">
                <div class="py-sm-3 py-md-5">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="product_id">Produit <span class="text-danger">*</span></label>
                                <select wire:model="product_id" class="form-control form-control-alt" id="product_id">
                                    <option value="">-- Sélectionner un produit --</option>
                                    @foreach ($produits as $produit)
                                        <option value="{{ $produit->id }}">{{ $produit->name }} ({{ $produit->unite }})</option>
                                    @endforeach
                                </select>
                                @error('product_id')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fournisseur_id">Fournisseur</label>
                                <select wire:model="fournisseur_id" class="form-control form-control-alt" id="fournisseur_id">
                                    <option value="">-- Aucun fournisseur --</option>
                                    @foreach ($fournisseurs as $fournisseur)
                                        <option value="{{ $fournisseur->id }}">{{ $fournisseur->name }}</option>
                                    @endforeach
                                </select>
                                @error('fournisseur_id')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="quantite">Quantité reçue <span class="text-danger">*</span></label>
                                <input wire:model="quantite" type="number" step="0.01" min="0.01"
                                    class="form-control form-control-alt" id="quantite" placeholder="0.00">
                                @error('quantite')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="prix_achat">Prix d'achat unitaire</label>
                                <input wire:model="prix_achat" type="number" step="0.01" min="0"
                                    class="form-control form-control-alt" id="prix_achat" placeholder="0.00">
                                @error('prix_achat')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="date_peremption">Date de péremption</label>
                                <input wire:model="date_peremption" type="date"
                                    class="form-control form-control-alt" id="date_peremption">
                                @error('date_peremption')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="numero_lot">Numéro de lot</label>
                                <input wire:model="numero_lot" type="text"
                                    class="form-control form-control-alt" id="numero_lot" placeholder="Lot n°...">
                                @error('numero_lot')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="note">Note</label>
                                <textarea wire:model="note" class="form-control form-control-alt"
                                    id="note" rows="2" placeholder="Remarques..."></textarea>
                                @error('note')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="fichier">Fichier joint <span class="text-danger">*</span> <small class="text-muted">(PDF ou image — max 10 Mo)</small></label>
                                <input wire:model="fichier" type="file" class="form-control-file"
                                    id="fichier" accept=".pdf,.jpg,.jpeg,.png">
                                <div wire:loading wire:target="fichier" class="text-muted small mt-1">
                                    <i class="fa fa-spinner fa-spin"></i> Chargement...
                                </div>
                                @error('fichier')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            @if ($fichier)
                                @php $ext = strtolower(pathinfo($fichier->getClientOriginalName(), PATHINFO_EXTENSION)) @endphp
                                <div class="p-2 border rounded bg-body-light position-relative">
                                    <button type="button" wire:click="removeFile"
                                        class="btn btn-sm btn-danger position-absolute"
                                        style="top: 6px; right: 6px; z-index: 1;"
                                        title="Retirer le fichier">
                                        <i class="fa fa-fw fa-times"></i>
                                    </button>
                                    @if (in_array($ext, ['jpg', 'jpeg', 'png']))
                                        <img src="{{ $fichier->temporaryUrl() }}" alt="Aperçu"
                                             class="img-fluid rounded d-block mx-auto"
                                             style="max-height: 220px; object-fit: contain;">
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
        </div>
    </form>
</div>
