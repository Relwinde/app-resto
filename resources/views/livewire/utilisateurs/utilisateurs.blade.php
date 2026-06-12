<div>
    @include('partials.pages.header', ['pageHeader' => $pageHeader])

    <div class="content">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Liste des utilisateurs</h3>
                <div class="block-options">
                    @can('Créer Utilisateur')
                    <button type="button"
                        wire:click="$dispatch('openModal', { component: 'utilisateurs.modals.create-utilisateur' })"
                        class="btn btn-sm btn-primary">
                        <i class="fa fa-fw fa-plus mr-1"></i> Ajouter un utilisateur
                    </button>
                    @endcan
                </div>
            </div>
            <div class="block-content">

                <div class="row mb-3">
                    <div class="col-sm-5">
                        <div class="input-group">
                            <input wire:model.live="search" type="search"
                                class="form-control form-control-alt"
                                placeholder="Rechercher un utilisateur...">
                            @if ($search)
                                <div class="input-group-append">
                                    <button type="button" wire:click="clear_search" class="btn btn-alt-secondary">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-vcenter font-size-sm">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Rôle(s)</th>
                                <th>Créé le</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($utilisateurs as $utilisateur)
                                <tr>
                                    <td class="font-w600">{{ $utilisateur->name }}</td>
                                    <td>{{ $utilisateur->email }}</td>
                                    <td>
                                        @foreach ($utilisateur->roles as $role)
                                            <span class="badge badge-primary mr-1">{{ $role->name }}</span>
                                        @endforeach
                                        @if ($utilisateur->roles->isEmpty())
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>{{ $utilisateur->created_at->format('d/m/Y') }}</td>
                                    <td class="text-center">
                                        @canany(['Modifier Utilisateur', 'Supprimer Utilisateur'])
                                        <div class="btn-group">
                                            @can('Modifier Utilisateur')
                                            <button type="button"
                                                wire:click="$dispatch('openModal', { component: 'utilisateurs.modals.edit-utilisateur', arguments: { utilisateur: {{ $utilisateur->id }} } })"
                                                class="btn btn-sm btn-alt-secondary" title="Modifier">
                                                <i class="fa fa-fw fa-pencil-alt"></i>
                                            </button>
                                            @endcan
                                            @can('Supprimer Utilisateur')
                                            @if ($utilisateur->id !== auth()->id())
                                            <button type="button"
                                                wire:click="delete({{ $utilisateur->id }})"
                                                wire:confirm="Supprimer l'utilisateur {{ $utilisateur->name }} ?"
                                                class="btn btn-sm btn-alt-danger" title="Supprimer">
                                                <i class="fa fa-fw fa-trash"></i>
                                            </button>
                                            @endif
                                            @endcan
                                        </div>
                                        @endcanany
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Aucun utilisateur trouvé.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $utilisateurs->links() }}
                </div>
            </div>
        </div>
    </div>

</div>
