<div>
    <div class="block block-rounded" style="max-width:750px;">
        <div class="block-header block-header-default">
            <h3 class="block-title">Permissions du rôle : <strong>{{ $role->name }}</strong></h3>
            <div class="block-options">
                @can('Modifier Rôle')
                <button type="button" wire:click="save" class="btn btn-sm btn-primary">
                    <i class="fa fa-fw fa-save mr-1"></i> Enregistrer
                </button>
                @endcan
                <div wire:loading wire:target="save" class="spinner-border spinner-border-sm text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <button type="button" wire:click='$dispatch("closeModal")' class="btn btn-sm btn-alt-secondary">Fermer</button>
            </div>
        </div>

        <div class="block-content">
            <div class="py-sm-3 py-md-4">

                <p class="text-muted small mb-4">
                    Cochez les permissions à attribuer à ce rôle.
                    Utilisez le bouton de groupe pour tout sélectionner ou désélectionner d'un module.
                </p>

                @foreach ($permissionGroups as $module => $permissions)
                    @php
                        $nb = count(array_intersect($permissions, $selectedPermissions));
                        $allChecked = $nb === count($permissions);
                    @endphp
                    <div class="mb-4">
                        <div class="d-flex align-items-center justify-content-between mb-2 pb-1 border-bottom">
                            <span class="font-w600 text-dark">
                                {{ $module }}
                                <small class="text-muted font-w400 ml-1">({{ $nb }}/{{ count($permissions) }})</small>
                            </span>
                            <button type="button"
                                wire:click="toggleAll('{{ $module }}')"
                                class="btn btn-xs {{ $allChecked ? 'btn-secondary' : 'btn-alt-primary' }}">
                                {{ $allChecked ? 'Tout décocher' : 'Tout cocher' }}
                            </button>
                        </div>
                        <div class="row">
                            @foreach ($permissions as $perm)
                                <div class="col-sm-6 mb-2">
                                    <label class="d-flex align-items-center font-size-sm" style="cursor:pointer; user-select:none;">
                                        <input type="checkbox"
                                            wire:model.live="selectedPermissions"
                                            value="{{ $perm }}"
                                            class="mr-2">
                                        {{ $perm }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
</div>
