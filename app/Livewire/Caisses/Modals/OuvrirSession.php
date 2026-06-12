<?php

namespace App\Livewire\Caisses\Modals;

use App\Models\Caisse;
use App\Models\SessionCaisse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use LivewireUI\Modal\ModalComponent;

class OuvrirSession extends ModalComponent
{
    public string $note_ouverture = '';

    public function render()
    {
        $caisseEspeces = Caisse::where('type', 'especes')->where('statut', 'active')->first();
        $caisseMobile  = Caisse::where('type', 'mobile_money')->where('statut', 'active')->first();

        return view('livewire.caisses.modals.ouvrir-session', [
            'soldeEspeces' => (float) ($caisseEspeces?->solde_actuel ?? 0),
            'soldeMobile'  => (float) ($caisseMobile?->solde_actuel ?? 0),
        ]);
    }

    public function ouvrir(): void
    {
        Gate::authorize('Ouvrir Session Caisse');

        $this->validate(
            ['note_ouverture' => ['nullable', 'string', 'max:500']]
        );

        $caisseEspeces = Caisse::where('type', 'especes')->where('statut', 'active')->firstOrFail();
        $caisseMobile  = Caisse::where('type', 'mobile_money')->where('statut', 'active')->firstOrFail();

        if ($caisseEspeces->sessionActive()) {
            $this->dispatch('notify', message: 'Une session est déjà ouverte pour la caisse espèces.', type: 'error');
            return;
        }
        if ($caisseMobile->sessionActive()) {
            $this->dispatch('notify', message: 'Une session est déjà ouverte pour la caisse mobile money.', type: 'error');
            return;
        }

        DB::transaction(function () use ($caisseEspeces, $caisseMobile) {
            $note = $this->note_ouverture ?: null;

            foreach ([$caisseEspeces, $caisseMobile] as $caisse) {
                $fond = (float) $caisse->solde_actuel;

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
                    'solde_avant'       => $fond,
                    'solde_apres'       => $fond,
                    'note'              => $note,
                ]);
            }
        });

        $this->dispatch('session-ouverte');
        $this->closeModal();
    }
}
