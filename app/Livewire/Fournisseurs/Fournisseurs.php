<?php

namespace App\Livewire\Fournisseurs;

use App\Models\Fournisseur;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;

class Fournisseurs extends Component
{
    use WithPagination;

    public $restaurantId;
    public string $search = '';

    public function mount($restaurantId): void
    {
        $this->restaurantId = $restaurantId;
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function clear_search(): void
    {
        $this->search = '';
    }

    public function delete(int $id): void
    {
        Gate::authorize('Supprimer Fournisseur');
        $fournisseur = Fournisseur::forRestaurant($this->restaurantId)->find($id);
        if ($fournisseur) {
            $fournisseur->delete();
            $this->dispatch('fournisseur-deleted');
        }
    }

    #[On('fournisseur-created')]
    #[On('fournisseur-updated')]
    #[On('fournisseur-deleted')]
    public function render()
    {
        Gate::authorize('Voir Fournisseurs');

        $fournisseurs = Fournisseur::withCount('stockMovements')
            ->forRestaurant($this->restaurantId)
            ->where(function ($query) {
                $query->where('name', 'like', "%{$this->search}%")
                      ->orWhere('phone', 'like', "%{$this->search}%");
            })
            ->orderBy('name')
            ->paginate(10);

        $pageHeader = [
            'title'       => 'Fournisseurs',
            'subtitle'    => 'Liste des fournisseurs',
            'breadcrumbs' => [
                ['label' => 'Accueil', 'url' => route('app.dashboard', $this->restaurantId)],
                ['label' => 'Fournisseurs'],
            ],
        ];

        return view('livewire.fournisseurs.fournisseurs', [
            'fournisseurs'  => $fournisseurs,
            'pageHeader'    => $pageHeader,
            'restaurantId'  => $this->restaurantId,
        ])->layout('components.layouts.app', ['title' => 'Fournisseurs']);
    }
}
