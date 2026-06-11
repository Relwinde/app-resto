<?php

namespace App\Livewire\Caisses\Modals;

use App\Models\SessionCaisse;
use Illuminate\Support\Facades\Gate;
use LivewireUI\Modal\ModalComponent;

class FermerSession extends ModalComponent
{
    public SessionCaisse $session;

    public $fond_fermeture  = '';
    public $note_fermeture  = '';

    public function render()
    {
        $totalEncaisse   = $this->session->totalEncaisse();
        $nbCommandes     = $this->session->commandes()->where('statut', 'payee')->count();
        $soldeCaisse     = (float) $this->session->caisse->solde_actuel;

        return view('livewire.caisses.modals.fermer-session', compact('totalEncaisse', 'nbCommandes', 'soldeCaisse'));
    }

    public function fermer(): void
    {
        Gate::authorize('Fermer Session Caisse');

        $this->validate(
            [
                'fond_fermeture' => ['required', 'numeric', 'min:0'],
                'note_fermeture' => ['nullable', 'string', 'max:500'],
            ],
            [
                'fond_fermeture.required' => 'Le fond de fermeture est obligatoire.',
                'fond_fermeture.min'      => 'Le fond de fermeture doit être positif ou nul.',
            ]
        );

        $this->session->fermer((float) $this->fond_fermeture, $this->note_fermeture ?: null);

        $this->dispatch('session-fermee');
        $this->closeModal();
    }
}
