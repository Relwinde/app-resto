<?php

namespace App\Livewire\Caisses\Modals;

use App\Models\Caisse;
use App\Models\SessionCaisse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use LivewireUI\Modal\ModalComponent;

class OuvrirSession extends ModalComponent
{
    public string $fond_especes   = '';
    public string $fond_mobile    = '';
    public string $note_ouverture = '';

    public function ouvrir(): void
    {
        Gate::authorize('Ouvrir Session Caisse');

        $this->validate(
            [
                'fond_especes' => ['required', 'numeric', 'min:0'],
                'fond_mobile'  => ['required', 'numeric', 'min:0'],
                'note_ouverture' => ['nullable', 'string', 'max:500'],
            ],
            [
                'fond_especes.required' => 'Le fond d\'ouverture espèces est obligatoire.',
                'fond_especes.min'      => 'Le fond espèces doit être positif ou nul.',
                'fond_mobile.required'  => 'Le fond d\'ouverture mobile money est obligatoire.',
                'fond_mobile.min'       => 'Le fond mobile doit être positif ou nul.',
            ]
        );

        $caisseEspeces = Caisse::where('type', 'especes')->where('statut', 'active')->firstOrFail();
        $caisseMobile  = Caisse::where('type', 'mobile_money')->where('statut', 'active')->firstOrFail();

        if ($caisseEspeces->sessionActive()) {
            $this->addError('fond_especes', 'Une session est déjà ouverte pour la caisse espèces.');
            return;
        }
        if ($caisseMobile->sessionActive()) {
            $this->addError('fond_mobile', 'Une session est déjà ouverte pour la caisse mobile money.');
            return;
        }

        DB::transaction(function () use ($caisseEspeces, $caisseMobile) {
            $note = $this->note_ouverture ?: null;

            foreach ([
                [$caisseEspeces, (float) $this->fond_especes],
                [$caisseMobile,  (float) $this->fond_mobile],
            ] as [$caisse, $fond]) {
                $soldeAvant = (float) $caisse->solde_actuel;

                $session = SessionCaisse::create([
                    'caisse_id'      => $caisse->id,
                    'user_id'        => auth()->id(),
                    'fond_ouverture' => $fond,
                    'statut'         => 'ouverte',
                    'note_ouverture' => $note,
                ]);

                $caisse->mouvements()->create([
                    'session_caisse_id' => $session->id,
                    'user_id'           => auth()->id(),
                    'type'              => 'ouverture',
                    'montant'           => $fond,
                    'solde_avant'       => $soldeAvant,
                    'solde_apres'       => $soldeAvant + $fond,
                    'note'              => $note,
                ]);

                $caisse->update(['solde_actuel' => $soldeAvant + $fond]);
            }
        });

        $this->dispatch('session-ouverte');
        $this->closeModal();
    }
}
