<div>
    <form wire:submit.prevent="create">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Nouveau Produit</h3>
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
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="name">Nom du produit <span class="text-danger">*</span></label>
                                <input wire:model="name" type="text" class="form-control form-control-alt"
                                    id="name" placeholder="Nom du produit...">
                                @error('name')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="unite">Unité de mesure <span class="text-danger">*</span></label>
                                <input wire:model="unite" type="text" class="form-control form-control-alt"
                                    id="unite" placeholder="kg, litre, pièce...">
                                @error('unite')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="prix_vente">Prix de vente <span class="text-danger">*</span></label>
                                <input wire:model="prix_vente" type="number" step="0.01" min="0"
                                    class="form-control form-control-alt" id="prix_vente" placeholder="0.00">
                                @error('prix_vente')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="prix_achat">Prix d'achat</label>
                                <input wire:model="prix_achat" type="number" step="0.01" min="0"
                                    class="form-control form-control-alt" id="prix_achat" placeholder="0.00">
                                @error('prix_achat')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="category_id">Catégorie</label>
                                <select wire:model="category_id" class="form-control form-control-alt" id="category_id">
                                    <option value="">-- Aucune catégorie --</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="d-block">Approvisionnable</label>
                                <div class="custom-control custom-switch mt-2">
                                    <input wire:model="is_suppliable" type="checkbox"
                                        class="custom-control-input" id="is_suppliable">
                                    <label class="custom-control-label" for="is_suppliable">Oui</label>
                                </div>
                                @error('is_suppliable')
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
