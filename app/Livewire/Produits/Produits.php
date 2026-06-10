<?php

namespace App\Livewire\Produits;

use App\Models\Product;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;

class Produits extends Component
{
    use WithPagination;

    public string $search = '';

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
        $produit = Product::find($id);
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
        $produits = Product::with('category')
            ->where('name', 'like', "%{$this->search}%")
            ->orderBy('name')
            ->paginate(10);

        $pageHeader = [
            'title'       => 'Produits',
            'subtitle'    => 'Liste des produits',
            'breadcrumbs' => [
                ['label' => 'Accueil', 'url' => route('dashboard')],
                ['label' => 'Produits'],
            ],
        ];

        return view('livewire.produits.produits', [
            'produits'   => $produits,
            'pageHeader' => $pageHeader,
        ])->layout('components.layouts.app', ['title' => 'Produits']);
    }
}
