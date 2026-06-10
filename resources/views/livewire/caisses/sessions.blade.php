<div>
    @include('partials.pages.header', ['pageHeader' => $pageHeader])

    <div class="content">

        @if ($caisse)
            <div class="block block-rounded mb-4">
                <div class="block-content py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="mb-0">{{ $caisse->nom }}</h5>
                            <p class="text-muted mb-0 small">
                                Solde actuel :
                                <strong class="text-dark">{{ number_format($caisse->solde_actuel, 0, ',', ' ') }} FCFA</strong>
                                &mdash;
                                @if ($sessionActive)
                                    <span class="badge badge-success">Session ouverte</span>
                                    <span class="text-muted small ml-1">
                                        depuis {{ $sessionActive->created_at->format('d/m/Y à H:i') }}
                                        ({{ $sessionActive->user->name }})
                                    </span>
                                @else
                                    <span class="badge badge-secondary">Aucune session active</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            @if ($sessionActive)
                                <button type="button"
                                    wire:click="$dispatch('openModal', { component: 'caisses.modals.fermer-session', arguments: { session: {{ $sessionActive->id }} } })"
                                    class="btn btn-sm btn-danger">
                                    <i class="fa fa-fw fa-lock"></i> Fermer la session
                                </button>
                            @else
                                <button type="button"
                                    wire:click="$dispatch('openModal', { component: 'caisses.modals.ouvrir-session' })"
                                    class="btn btn-sm btn-success">
                                    <i class="fa fa-fw fa-unlock"></i> Ouvrir une session
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Historique des sessions</h3>
            </div>
            <div class="block-content">
                <div class="table-responsive">
                    <table class="table table-hover table-vcenter font-size-sm">
                        <thead>
                            <tr>
                                <th>Caisse</th>
                                <th>Caissier</th>
                                <th>Ouverture</th>
                                <th class="text-right">Fond ouverture</th>
                                <th>Fermeture</th>
                                <th class="text-right">Fond fermeture</th>
                                <th class="text-right">Total encaissé</th>
                                <th class="text-center">Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($sessions as $session)
                                <tr>
                                    <td>{{ $session->caisse->nom }}</td>
                                    <td>{{ $session->user->name }}</td>
                                    <td>{{ $session->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="text-right">{{ number_format($session->fond_ouverture, 0, ',', ' ') }}</td>
                                    <td>{{ $session->ferme_le ? $session->ferme_le->format('d/m/Y H:i') : '—' }}</td>
                                    <td class="text-right">
                                        {{ $session->fond_fermeture !== null ? number_format($session->fond_fermeture, 0, ',', ' ') : '—' }}
                                    </td>
                                    <td class="text-right">{{ number_format($session->totalEncaisse(), 0, ',', ' ') }}</td>
                                    <td class="text-center">
                                        @if ($session->statut === 'ouverte')
                                            <span class="badge badge-success">Ouverte</span>
                                        @else
                                            <span class="badge badge-secondary">Fermée</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">Aucune session enregistrée.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $sessions->links() }}
                </div>
            </div>
        </div>

    </div>

    <livewire:wire-elements-modal />
</div>
