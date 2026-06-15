<?php

namespace App\Livewire\Caisses\Modals;

use App\Models\Caisse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use LivewireUI\Modal\ModalComponent;

class FermerSession extends ModalComponent
{
    public string $note_fermeture = '';

    public function render()
    {
        $restaurantId   = auth()->user()->restaurant_id;
        $caisseEspeces  = Caisse::forRestaurant($restaurantId)->where('type', 'especes')->where('statut', 'active')->first();
        $caisseMobile   = Caisse::forRestaurant($restaurantId)->where('type', 'mobile_money')->where('statut', 'active')->first();
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

        $restaurantId = auth()->user()->restaurant_id;

        $this->validate(
            ['note_fermeture' => ['nullable', 'string', 'max:500']]
        );

        $caisseEspeces  = Caisse::forRestaurant($restaurantId)->where('type', 'especes')->where('statut', 'active')->first();
        $caisseMobile   = Caisse::forRestaurant($restaurantId)->where('type', 'mobile_money')->where('statut', 'active')->first();
        $sessionEspeces = $caisseEspeces?->sessionActive();
        $sessionMobile  = $caisseMobile?->sessionActive();

        if (! $sessionEspeces && ! $sessionMobile) {
            $this->dispatch('notify', message: 'Aucune session ouverte à fermer.', type: 'error');
            return;
        }

        $note = $this->note_fermeture ?: null;

        DB::transaction(function () use ($sessionEspeces, $sessionMobile, $caisseEspeces, $caisseMobile, $note) {
            if ($sessionEspeces) {
                $sessionEspeces->fermer((float) $caisseEspeces->solde_actuel, $note);
            }
            if ($sessionMobile) {
                $sessionMobile->fermer((float) $caisseMobile->solde_actuel, $note);
            }
        });

        $this->dispatch('session-fermee');
        $this->closeModal();
    }
}
