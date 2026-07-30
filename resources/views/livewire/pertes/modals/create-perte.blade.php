<div>
    <form wire:submit.prevent="create">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Nouvelle déclaration de perte</h3>
                <div class="block-options">
                    @can('Créer Perte')
                    <button type="submit" class="btn btn-sm btn-primary" wire:loading.attr="disabled" wire:target="create">
                        Enregistrer
                    </button>
                    @endcan
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
                                <label for="motif">Motif <span class="text-danger">*</span></label>
                                <select wire:model="motif" class="form-control form-control-alt @error('motif') is-invalid @enderror" id="motif">
                                    <option value="">-- Sélectionner un motif --</option>
                                    @foreach ($motifs as $valeur => $libelle)
                                        <option value="{{ $valeur }}">{{ $libelle }}</option>
                                    @endforeach
                                </select>
                                @error('motif')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    @error('lignes')
                        <div class="text-danger small mb-2">{{ $message }}</div>
                    @enderror

                    <div class="table-responsive mb-2">
                        <table class="table table-bordered table-vcenter">
                            <thead>
                                <tr>
                                    <th style="min-width: 200px;">Produit <span class="text-danger">*</span></th>
                                    <th style="min-width: 110px;">Quantité perdue <span class="text-danger">*</span></th>
                                    <th style="min-width: 200px;">Note</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($lignes as $index => $ligne)
                                    <tr wire:key="ligne-{{ $index }}">
                                        <td>
                                            <select wire:model="lignes.{{ $index }}.product_id" class="form-control form-control-sm">
                                                <option value="">-- Produit --</option>
                                                @foreach ($produits as $produit)
                                                    <option value="{{ $produit->id }}">{{ $produit->name }} ({{ $produit->unite }})</option>
                                                @endforeach
                                            </select>
                                            @error("lignes.$index.product_id")
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </td>
                                        <td>
                                            <input wire:model="lignes.{{ $index }}.quantite" type="number" step="0.01" min="0.01"
                                                class="form-control form-control-sm" placeholder="0.00">
                                            @error("lignes.$index.quantite")
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </td>
                                        <td>
                                            <input wire:model="lignes.{{ $index }}.note" type="text" class="form-control form-control-sm" placeholder="Détail (optionnel)">
                                        </td>
                                        <td class="text-center">
                                            <button type="button" wire:click="retirerLigne({{ $index }})"
                                                class="btn btn-sm btn-danger" @if(count($lignes) <= 1) disabled @endif>
                                                <i class="fa fa-fw fa-times"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <button type="button" wire:click="ajouterLigne" class="btn btn-sm btn-alt-primary">
                                <i class="fa fa-plus mr-1"></i> Ajouter un produit
                            </button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="note">Note</label>
                                <textarea wire:model="note" class="form-control form-control-alt"
                                    id="note" rows="2" placeholder="Description de l'incident..."></textarea>
                                @error('note')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="fichier">Fichier joint <small class="text-muted">(photo/justificatif, optionnel — max 10 Mo)</small></label>
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
