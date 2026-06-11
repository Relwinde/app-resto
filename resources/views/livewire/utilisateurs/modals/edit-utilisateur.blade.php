<div>
    <form wire:submit.prevent="save">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Modifier l'utilisateur</h3>
                <div class="block-options">
                    <button type="submit" class="btn btn-sm btn-primary">Enregistrer</button>
                    <div wire:loading wire:target="save" class="spinner-border spinner-border-sm text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <button type="button" wire:click='$dispatch("closeModal")' class="btn btn-sm btn-alt-secondary">Annuler</button>
                </div>
            </div>
            <div class="block-content">
                <div class="py-sm-3 py-md-4">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Nom <span class="text-danger">*</span></label>
                                <input wire:model="name" type="text" class="form-control form-control-alt" id="name">
                                @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email <span class="text-danger">*</span></label>
                                <input wire:model="email" type="email" class="form-control form-control-alt" id="email">
                                @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password">Nouveau mot de passe <small class="text-muted">(laisser vide pour ne pas changer)</small></label>
                                <input wire:model="password" type="password" class="form-control form-control-alt" id="password" placeholder="Minimum 8 caractères">
                                @error('password') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="role">Rôle <span class="text-danger">*</span></label>
                                <select wire:model="role" class="form-control form-control-alt" id="role">
                                    <option value="">-- Sélectionner un rôle --</option>
                                    @foreach ($roles as $r)
                                        <option value="{{ $r->name }}">{{ $r->name }}</option>
                                    @endforeach
                                </select>
                                @error('role') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </form>
</div>
