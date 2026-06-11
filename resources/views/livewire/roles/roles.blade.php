<div>
    @include('partials.pages.header', ['pageHeader' => $pageHeader])

    <div class="content">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Rôles</h3>
                <div class="block-options">
                    @can('Créer Rôle')
                    <button type="button"
                        wire:click="$dispatch('openModal', { component: 'roles.modals.create-role' })"
                        class="btn btn-sm btn-primary">
                        <i class="fa fa-fw fa-plus mr-1"></i> Ajouter un rôle
                    </button>
                    @endcan
                </div>
            </div>
            <div class="block-content">
                <div class="table-responsive">
                    <table class="table table-hover table-vcenter font-size-sm">
                        <thead>
                            <tr>
                                <th>Rôle</th>
                                <th class="text-center">Utilisateurs</th>
                                <th>Permissions attribuées</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($roles as $role)
                                <tr>
                                    <td class="font-w600">{{ $role->name }}</td>
                                    <td class="text-center">{{ $role->users_count }}</td>
                                    <td>
                                        <span class="text-muted">{{ $role->permissions->count() }} permission(s)</span>
                                        @if ($role->permissions->isNotEmpty())
                                            <div class="mt-1">
                                                @foreach ($role->permissions->take(5) as $perm)
                                                    <span class="badge badge-light text-muted border mr-1 mb-1">{{ $perm->name }}</span>
                                                @endforeach
                                                @if ($role->permissions->count() > 5)
                                                    <span class="text-muted small">+{{ $role->permissions->count() - 5 }} autres</span>
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @can('Modifier Rôle')
                                        <button type="button"
                                            wire:click="$dispatch('openModal', { component: 'roles.modals.edit-role', arguments: { role: {{ $role->id }} } })"
                                            class="btn btn-sm btn-alt-primary" title="Gérer les permissions">
                                            <i class="fa fa-fw fa-shield-alt"></i> Permissions
                                        </button>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">Aucun rôle trouvé.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <livewire:wire-elements-modal />
</div>
