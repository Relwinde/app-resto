<div>
    @include('partials.pages.header', ['pageHeader' => $pageHeader])

    <div class="content">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Bons de caisse</h3>
                <div class="block-options">
                    @can('Créer Dépense')
                    <button type="button"
                        wire:click="$dispatch('openModal', { component: 'caisses.modals.create-depense' })"
                        class="btn btn-sm btn-primary">
                        <i class="fa fa-plus mr-1"></i> Nouveau bon
                    </button>
                    @endcan
                </div>
            </div>

            <div class="block-content">
                <div class="row mb-3">
                    <div class="col-sm-5">
                        <input wire:model.live="search" type="text" class="form-control form-control-alt"
                            placeholder="Rechercher motif, bénéficiaire...">
                    </div>
                    <div class="col-sm-3">
                        <select wire:model.live="statut" class="form-control form-control-alt">
                            <option value="">Tous les statuts</option>
                            <option value="edite">Édité</option>
                            <option value="en_attente">En attente</option>
                            <option value="valide">Validé</option>
                            <option value="paye">Payé</option>
                        </select>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-vcenter font-size-sm">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Motif</th>
                                <th>Bénéficiaire</th>
                                <th class="text-right">Montant</th>
                                <th>Créé par</th>
                                <th>Statut</th>
                                <th>Caisse</th>
                                <th>Fichiers</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($depenses as $depense)
                                <tr>
                                    <td class="text-muted">{{ $depense->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="font-w600">{{ $depense->motif }}</td>
                                    <td>{{ $depense->beneficiaire ?? '—' }}</td>
                                    <td class="text-right font-w600 text-danger">
                                        {{ number_format($depense->montant, 0, ',', ' ') }} FCFA
                                    </td>
                                    <td>{{ $depense->user->name }}</td>
                                    <td>
                                        @switch($depense->statut)
                                            @case('edite')
                                                <span class="badge badge-secondary">Édité</span>
                                                @break
                                            @case('en_attente')
                                                <span class="badge badge-warning">En attente</span>
                                                @break
                                            @case('valide')
                                                <span class="badge badge-primary">Validé</span>
                                                @break
                                            @case('paye')
                                                <span class="badge badge-success">Payé</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>
                                        @if ($depense->caisse)
                                            <span class="font-size-sm">{{ $depense->caisse->nom }}</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($depense->files->isNotEmpty())
                                            @foreach ($depense->files as $file)
                                                <a href="{{ route('files.download', $file) }}" target="_blank"
                                                    class="badge badge-light border mr-1 mb-1"
                                                    title="{{ $file->original_name }}">
                                                    <i class="fa fa-paperclip mr-1"></i>{{ Str::limit($file->original_name, 20) }}
                                                </a>
                                            @endforeach
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($depense->estEdite())
                                            @can('Modifier Dépense')
                                            <button type="button"
                                                wire:click="$dispatch('openModal', { component: 'caisses.modals.edit-depense', arguments: { depenseId: {{ $depense->id }} } })"
                                                class="btn btn-sm btn-alt-secondary mr-1">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            @endcan
                                            @can('Soumettre Dépense')
                                            <button type="button"
                                                wire:click="soumettre({{ $depense->id }})"
                                                wire:confirm="Soumettre ce bon pour validation ?"
                                                class="btn btn-sm btn-alt-warning mr-1">
                                                <i class="fa fa-paper-plane mr-1"></i> Soumettre
                                            </button>
                                            @endcan
                                            @can('Supprimer Dépense')
                                            <button type="button"
                                                wire:click="delete({{ $depense->id }})"
                                                wire:confirm="Supprimer ce bon définitivement ?"
                                                class="btn btn-sm btn-alt-danger">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                            @endcan
                                        @elseif ($depense->estEnAttente())
                                            @can('Valider Dépense')
                                            <button type="button"
                                                wire:click="valider({{ $depense->id }})"
                                                wire:confirm="Valider ce bon de caisse ?"
                                                class="btn btn-sm btn-alt-primary">
                                                <i class="fa fa-check mr-1"></i> Valider
                                            </button>
                                            @endcan
                                        @elseif ($depense->estValide())
                                            @can('Payer Dépense')
                                            <button type="button"
                                                wire:click="$dispatch('openModal', { component: 'caisses.modals.payer-depense', arguments: { depenseId: {{ $depense->id }} } })"
                                                class="btn btn-sm btn-success">
                                                <i class="fa fa-money-bill-wave mr-1"></i> Payer
                                            </button>
                                            @endcan
                                        @else
                                            <span class="text-muted small">
                                                Payé le {{ $depense->paye_le?->format('d/m/Y') }}
                                                @if ($depense->payePar) par {{ $depense->payePar->name }} @endif
                                            </span>
                                        @endif
                                        @can('Modifier Dépense')
                                        <button type="button"
                                            wire:click="$dispatch('openModal', { component: 'caisses.modals.attach-file-depense', arguments: { depenseId: {{ $depense->id }} } })"
                                            class="btn btn-sm btn-alt-secondary ml-1" title="Joindre un fichier">
                                            <i class="fa fa-paperclip"></i>
                                        </button>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">Aucun bon de caisse enregistré.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $depenses->links() }}
                </div>
            </div>
        </div>
    </div>

</div>
