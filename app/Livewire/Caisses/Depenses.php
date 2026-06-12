<?php

namespace App\Livewire\Caisses;

use App\Models\Depense;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class Depenses extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statut = '';

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingStatut(): void { $this->resetPage(); }

    public function soumettre(int $id): void
    {
        Gate::authorize('Soumettre Dépense');

        $depense = Depense::findOrFail($id);

        if (! $depense->estEdite()) {
            $this->dispatch('notify', message: 'Ce bon ne peut pas être soumis dans son état actuel.', type: 'error');
            return;
        }

        $depense->update(['statut' => 'en_attente']);
        $this->dispatch('depense-soumise');
        $this->dispatch('notify', message: 'Bon soumis pour validation.', type: 'success');
    }

    public function valider(int $id): void
    {
        Gate::authorize('Valider Dépense');

        $depense = Depense::findOrFail($id);

        if (! $depense->estEnAttente()) {
            $this->dispatch('notify', message: 'Ce bon ne peut pas être validé dans son état actuel.', type: 'error');
            return;
        }

        $depense->update([
            'statut'     => 'valide',
            'valide_par' => auth()->id(),
            'valide_le'  => now(),
        ]);

        $this->dispatch('depense-validee');
        $this->dispatch('notify', message: 'Bon validé avec succès.', type: 'success');
    }

    public function delete(int $id): void
    {
        Gate::authorize('Supprimer Dépense');

        $depense = Depense::findOrFail($id);

        if (! $depense->estEdite()) {
            $this->dispatch('notify', message: 'Seuls les bons en état « Édité » peuvent être supprimés.', type: 'error');
            return;
        }

        $depense->delete();
        $this->dispatch('depense-supprimee');
        $this->dispatch('notify', message: 'Bon supprimé.', type: 'success');
    }

    #[On('depense-created')]
    #[On('depense-modifiee')]
    #[On('depense-soumise')]
    #[On('depense-validee')]
    #[On('depense-payee')]
    #[On('depense-supprimee')]
    public function render()
    {
        Gate::authorize('Voir Dépenses');

        $depenses = Depense::with(['caisse', 'user', 'validePar', 'payePar', 'files'])
            ->when($this->search, fn ($q) => $q->where('motif', 'like', "%{$this->search}%")
                ->orWhere('beneficiaire', 'like', "%{$this->search}%"))
            ->when($this->statut, fn ($q) => $q->where('statut', $this->statut))
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $pageHeader = [
            'title'       => 'Dépenses',
            'subtitle'    => 'Bons de caisse',
            'breadcrumbs' => [
                ['label' => 'Accueil', 'url' => route('dashboard')],
                ['label' => 'Caisse', 'url' => route('caisse')],
                ['label' => 'Dépenses'],
            ],
        ];

        return view('livewire.caisses.depenses', compact('depenses', 'pageHeader'))
            ->layout('components.layouts.app', ['title' => 'Dépenses']);
    }
}
