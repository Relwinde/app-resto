<?php

namespace App\Livewire\Commandes;

use App\Models\Commande;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class Commandes extends Component
{
    use WithPagination;

    public string $search  = '';
    public string $statut  = '';
    public string $dateMin = '';
    public string $dateMax = '';

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingStatut(): void { $this->resetPage(); }

    public function clear_search(): void
    {
        $this->search  = '';
        $this->statut  = '';
        $this->dateMin = '';
        $this->dateMax = '';
        $this->resetPage();
    }

    public function render()
    {
        Gate::authorize('Voir Commandes');

        $commandes = Commande::with(['caisse', 'user', 'items'])
            ->when($this->search, function ($q) {
                $q->where(function ($q2) {
                    $q2->where('numero', 'like', "%{$this->search}%")
                       ->orWhere('client_nom', 'like', "%{$this->search}%")
                       ->orWhere('table_numero', 'like', "%{$this->search}%");
                });
            })
            ->when($this->statut, fn ($q) => $q->where('statut', $this->statut))
            ->when($this->dateMin, fn ($q) => $q->whereDate('created_at', '>=', $this->dateMin))
            ->when($this->dateMax, fn ($q) => $q->whereDate('created_at', '<=', $this->dateMax))
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $pageHeader = [
            'title'       => 'Commandes',
            'subtitle'    => 'Historique des commandes',
            'breadcrumbs' => [
                ['label' => 'Accueil', 'url' => route('dashboard')],
                ['label' => 'Commandes'],
            ],
        ];

        return view('livewire.commandes.commandes', compact('commandes', 'pageHeader'))
            ->layout('components.layouts.app', ['title' => 'Commandes']);
    }

    #[On('commande-annulee')]
    public function refresh(): void {}
}
