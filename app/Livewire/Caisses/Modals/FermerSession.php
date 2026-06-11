<?php

namespace App\Livewire\Caisses\Modals;

use App\Models\Caisse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use LivewireUI\Modal\ModalComponent;

class FermerSession extends ModalComponent
{
    public string $fond_fermeture_especes = '';
    public string $fond_fermeture_mobile  = '';
    public string $note_fermeture         = '';

    public function mount(): void
    {
        $caisseEspeces = Caisse::where('type', 'especes')->where('statut', 'active')->first();
        $caisseMobile  = Caisse::where('type', 'mobile_money')->where('statut', 'active')->first();

        $this->fond_fermeture_especes = (string) (int) ($caisseEspeces?->solde_actuel ?? 0);
        $this->fond_fermeture_mobile  = (string) (int) ($caisseMobile?->solde_actuel ?? 0);
    }

    public function render()
    {
        $caisseEspeces  = Caisse::where('type', 'especes')->where('statut', 'active')->first();
        $caisseMobile   = Caisse::where('type', 'mobile_money')->where('statut', 'active')->first();
        $sessionEspeces = $caisseEspeces?->sessionActive();
        $sessionMobile  = $caisseMobile?->sessionActive();

        $totalEspeces = $caisseEspeces
            ? (float) $caisseEspeces->mouvements()
                ->where('type', 'encaissement')
                ->when($sessionEspeces, fn ($q) => $q->where('created_at', '>=', $sessionEspeces->created_at))
                ->sum('montant')
            : 0;

        $totalMobile = $caisseMobile
            ? (float) $caisseMobile->mouvements()
                ->where('type', 'encaissement')
                ->when($sessionMobile, fn ($q) => $q->where('created_at', '>=', $sessionMobile->created_at))
                ->sum('montant')
            : 0;
        $soldeEspeces = (float) ($caisseEspeces?->solde_actuel ?? 0);
        $soldeMobile  = (float) ($caisseMobile?->solde_actuel ?? 0);
        $nbCommandes  = $sessionEspeces
            ? $sessionEspeces->commandes()->where('statut', 'payee')->count()
            : 0;

        return view('livewire.caisses.modals.fermer-session', compact(
            'totalEspeces', 'totalMobile', 'soldeEspeces', 'soldeMobile', 'nbCommandes'
        ));
    }

    public function fermer(): void
    {
        Gate::authorize('Fermer Session Caisse');

        $this->validate(
            [
                'fond_fermeture_especes' => ['required', 'numeric', 'min:0'],
                'fond_fermeture_mobile'  => ['required', 'numeric', 'min:0'],
                'note_fermeture'         => ['nullable', 'string', 'max:500'],
            ],
            [
                'fond_fermeture_especes.required' => 'Le fond de fermeture espèces est obligatoire.',
                'fond_fermeture_especes.min'      => 'Le fond espèces doit être positif ou nul.',
                'fond_fermeture_mobile.required'  => 'Le fond de fermeture mobile money est obligatoire.',
                'fond_fermeture_mobile.min'       => 'Le fond mobile doit être positif ou nul.',
            ]
        );

        $caisseEspeces  = Caisse::where('type', 'especes')->where('statut', 'active')->first();
        $caisseMobile   = Caisse::where('type', 'mobile_money')->where('statut', 'active')->first();
        $sessionEspeces = $caisseEspeces?->sessionActive();
        $sessionMobile  = $caisseMobile?->sessionActive();

        if (! $sessionEspeces && ! $sessionMobile) {
            $this->addError('fond_fermeture_especes', 'Aucune session ouverte à fermer.');
            return;
        }

        $note = $this->note_fermeture ?: null;

        DB::transaction(function () use ($sessionEspeces, $sessionMobile, $note) {
            if ($sessionEspeces) {
                $sessionEspeces->fermer((float) $this->fond_fermeture_especes, $note);
            }
            if ($sessionMobile) {
                $sessionMobile->fermer((float) $this->fond_fermeture_mobile, $note);
            }
        });

        $this->dispatch('session-fermee');
        $this->closeModal();
    }
}
