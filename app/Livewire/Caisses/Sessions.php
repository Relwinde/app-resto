<?php

namespace App\Livewire\Caisses;

use App\Models\Caisse;
use App\Models\SessionCaisse;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class Sessions extends Component
{
    use WithPagination;

    #[On('session-ouverte')]
    #[On('session-fermee')]
    public function render()
    {
        Gate::authorize('Voir Sessions Caisse');

        $sessions = SessionCaisse::with(['caisse', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $caisse         = Caisse::where('statut', 'active')->first();
        $sessionActive  = $caisse?->sessionActive();

        $pageHeader = [
            'title'       => 'Sessions de Caisse',
            'subtitle'    => 'Ouverture et fermeture des sessions',
            'breadcrumbs' => [
                ['label' => 'Accueil', 'url' => route('dashboard')],
                ['label' => 'Caisse', 'url' => route('caisse')],
                ['label' => 'Sessions'],
            ],
        ];

        return view('livewire.caisses.sessions', compact('sessions', 'caisse', 'sessionActive', 'pageHeader'))
            ->layout('components.layouts.app', ['title' => 'Sessions de caisse']);
    }

    #[On('session-ouverte')]
    #[On('session-fermee')]
    public function refresh(): void {}
}
