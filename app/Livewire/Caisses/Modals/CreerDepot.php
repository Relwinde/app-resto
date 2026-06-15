<?php

namespace App\Livewire\Caisses\Modals;

use App\Models\Caisse;
use Illuminate\Support\Facades\Gate;
use LivewireUI\Modal\ModalComponent;

class CreerDepot extends ModalComponent
{
    public string $montant = '';
    public string $caisse_id = '';
    public string $note = '';

    public function creerDepot(): void
    {
        Gate::authorize('Enregistrer Dépôt');

        if (! Caisse::sessionOuverte()) {
            $this->dispatch('notify', message: 'Aucune session de caisse ouverte.', type: 'error');
            $this->closeModal();
            return;
        }

        $this->validate([
            'montant'   => ['required', 'numeric', 'min:1'],
            'caisse_id' => ['required', 'exists:caisses,id'],
        ], [
            'montant.required'   => 'Veuillez entrer un montant.',
            'montant.numeric'    => 'Le montant doit être un nombre.',
            'montant.min'        => 'Le montant doit être supérieur à 0.',
            'caisse_id.required' => 'Veuillez sélectionner une caisse.',
        ]);

        $caisse = Caisse::findOrFail($this->caisse_id);
        $montant = (float) $this->montant;

        $caisse->deposer($montant, $this->note ?: null);

        $this->dispatch('notify', message: 'Dépôt de ' . number_format($montant, 0, ',', ' ') . ' FCFA enregistré.', type: 'success');
        $this->dispatch('depot-enregistre');
        $this->closeModal();
    }

    public function render()
    {
        $caisses = Caisse::where('statut', 'active')->orderBy('nom')->get();

        return view('livewire.caisses.modals.creer-depot', compact('caisses'));
    }
}
