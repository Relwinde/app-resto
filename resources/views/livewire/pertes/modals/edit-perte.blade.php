<div>
    <form wire:submit.prevent="save">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Modifier la perte {{ $perte->numero }}</h3>
                <div class="block-options">
                    @can('Modifier Perte')
                    <button type="submit" class="btn btn-sm btn-primary" wire:loading.attr="disabled" wire:target="save">
                        Enregistrer
                    </button>
                    @endcan
                    <div wire:loading wire:target="save" class="spinner-border spinner-border-sm text-primary" role="status">
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
                                <select wire:model="motif" class="form-control form-control-alt" id="motif">
                                    @foreach ($motifs as $valeur => $libelle)
                                        <option value="{{ $valeur }}">{{ $libelle }}</option>
                                    @endforeach
                                </select>
                                @error('motif')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive mb-2">
                        <table class="table table-bordered table-vcenter">
                            <thead>
                                <tr>
                                    <th>Produit</th>
                                    <th style="min-width: 110px;">Quantité perdue <span class="text-danger">*</span></th>
                                    <th style="min-width: 200px;">Note</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($lignes as $index => $ligne)
                                    <tr wire:key="ligne-{{ $ligne['id'] }}">
                                        <td>{{ $ligne['nom_produit'] }} <small class="text-muted">({{ $ligne['unite'] }})</small></td>
                                        <td>
                                            <input wire:model="lignes.{{ $index }}.quantite" type="number" step="0.01" min="0.01"
                                                class="form-control form-control-sm">
                                            @error("lignes.$index.quantite")
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </td>
                                        <td>
                                            <input wire:model="lignes.{{ $index }}.note" type="text" class="form-control form-control-sm">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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

                </div>
            </div>
        </div>
    </form>
</div>
