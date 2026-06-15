<?php

namespace App\Livewire\Produits;

use App\Models\Product;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;

class Produits extends Component
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
        Gate::authorize('Supprimer Produit');
        $produit = Product::forRestaurant($this->restaurantId)->find($id);
        if ($produit) {
            $produit->delete();
            $this->dispatch('produit-deleted');
        }
    }

    #[On('produit-created')]
    #[On('produit-updated')]
    #[On('produit-deleted')]
    public function render()
    {
        Gate::authorize('Voir Produits');

        $produits = Product::with('category')
            ->forRestaurant($this->restaurantId)
            ->where('name', 'like', "%{$this->search}%")
            ->orderBy('name')
            ->paginate(10);

        $pageHeader = [
            'title'       => 'Produits',
            'subtitle'    => 'Liste des produits',
            'breadcrumbs' => [
                ['label' => 'Accueil', 'url' => route('app.dashboard', $this->restaurantId)],
                ['label' => 'Produits'],
            ],
        ];

        return view('livewire.produits.produits', [
            'produits'      => $produits,
            'pageHeader'    => $pageHeader,
            'restaurantId'  => $this->restaurantId,
        ])->layout('components.layouts.app', ['title' => 'Produits']);
    }
}
