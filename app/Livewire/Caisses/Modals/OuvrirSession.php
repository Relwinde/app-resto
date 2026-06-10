<?php

namespace App\Livewire\Caisses\Modals;

use App\Models\Caisse;
use App\Models\SessionCaisse;
use LivewireUI\Modal\ModalComponent;

class OuvrirSession extends ModalComponent
{
    public $caisse_id       = '';
    public $fond_ouverture  = '';
    public $note_ouverture  = '';

    public function mount(): void
    {
        $caisse = Caisse::where('statut', 'active')->first();
        if ($caisse) {
            $this->caisse_id = $caisse->id;
        }
    }

    public function render()
    {
        return view('livewire.caisses.modals.ouvrir-session', [
            'caisses' => Caisse::where('statut', 'active')->orderBy('nom')->get(),
        ]);
    }

    public function ouvrir(): void
    {
        $this->validate(
            [
                'caisse_id'      => ['required', 'exists:caisses,id'],
                'fond_ouverture' => ['required', 'numeric', 'min:0'],
                'note_ouverture' => ['nullable', 'string', 'max:500'],
            ],
            [
                'caisse_id.required'      => 'Veuillez sélectionner une caisse.',
                'fond_ouverture.required' => 'Le fond d\'ouverture est obligatoire.',
                'fond_ouverture.min'      => 'Le fond d\'ouverture doit être positif ou nul.',
            ]
        );

        $caisse = Caisse::findOrFail($this->caisse_id);

        if ($caisse->sessionActive()) {
            $this->addError('caisse_id', 'Cette caisse a déjà une session ouverte.');
            return;
        }

        $session = SessionCaisse::create([
            'caisse_id'      => $this->caisse_id,
            'user_id'        => auth()->id(),
            'fond_ouverture' => $this->fond_ouverture,
            'statut'         => 'ouverte',
            'note_ouverture' => $this->note_ouverture ?: null,
        ]);

        $soldeAvant = (float) $caisse->solde_actuel;
        $fond       = (float) $this->fond_ouverture;

        $caisse->mouvements()->create([
            'session_caisse_id' => $session->id,
            'user_id'           => auth()->id(),
            'type'              => 'ouverture',
            'montant'           => $fond,
            'solde_avant'       => $soldeAvant,
            'solde_apres'       => $soldeAvant + $fond,
            'note'              => $this->note_ouverture ?: null,
        ]);

        $caisse->update(['solde_actuel' => $soldeAvant + $fond]);

        $this->dispatch('session-ouverte');
        $this->closeModal();
    }
}
