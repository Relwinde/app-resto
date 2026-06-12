<?php

namespace App\Livewire\Caisses\Modals;

use App\Models\Caisse;
use App\Models\Depense;
use Illuminate\Support\Facades\Gate;
use LivewireUI\Modal\ModalComponent;

class PayerDepense extends ModalComponent
{
    public int    $depenseId = 0;
    public string $caisse_id = '';

    public function mount(int $depenseId): void
    {
        if (! Caisse::sessionOuverte()) {
            $this->dispatch('notify', message: 'Aucune session de caisse ouverte.', type: 'error');
            $this->closeModal();
            return;
        }

        $depense = Depense::find($depenseId);

        if (! $depense || ! $depense->estValide()) {
            $this->dispatch('notify', message: 'Ce bon ne peut pas être payé dans son état actuel.', type: 'error');
            $this->closeModal();
            return;
        }

        $this->depenseId = $depenseId;
    }

    public function payer(): void
    {
        Gate::authorize('Payer Dépense');

        if (! Caisse::sessionOuverte()) {
            $this->dispatch('notify', message: 'Aucune session de caisse ouverte.', type: 'error');
            $this->closeModal();
            return;
        }

        $this->validate([
            'caisse_id' => ['required', 'exists:caisses,id'],
        ], [
            'caisse_id.required' => 'Veuillez sélectionner une caisse.',
        ]);

        $depense = Depense::findOrFail($this->depenseId);

        if (! $depense->estValide()) {
            $this->dispatch('notify', message: 'Ce bon ne peut plus être payé.', type: 'error');
            $this->closeModal();
            return;
        }

        $caisse  = Caisse::findOrFail($this->caisse_id);
        $montant = (float) $depense->montant;

        if ($montant > (float) $caisse->solde_actuel) {
            $this->addError('caisse_id',
                'Solde insuffisant dans « ' . $caisse->nom . ' » ('
                . number_format($caisse->solde_actuel, 0, ',', ' ') . ' FCFA disponible).'
            );
            return;
        }

        $caisse->retirer($montant, $depense->motif, null, $depense->id, 'depense');

        $depense->update([
            'statut'           => 'paye',
            'caisse_id'        => $caisse->id,
            'session_caisse_id' => $caisse->sessionActive()?->id,
            'paye_par'         => auth()->id(),
            'paye_le'          => now(),
        ]);

        $this->dispatch('depense-payee');
        $this->closeModal();
    }

    public function render()
    {
        $depense = Depense::find($this->depenseId);
        $caisses = Caisse::where('statut', 'active')->orderBy('nom')->get();

        return view('livewire.caisses.modals.payer-depense', compact('depense', 'caisses'));
    }
}
