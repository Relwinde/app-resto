<?php

namespace App\Livewire\Categories;

use App\Models\Category;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;

class Categories extends Component
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
        Gate::authorize('Supprimer Catégorie');
        $categorie = Category::forRestaurant($this->restaurantId)->find($id);
        if ($categorie) {
            $categorie->delete();
            $this->dispatch('categorie-deleted');
        }
    }

    #[On('categorie-created')]
    #[On('categorie-updated')]
    #[On('categorie-deleted')]
    public function render()
    {
        Gate::authorize('Voir Catégories');

        $categories = Category::withCount('products')
            ->forRestaurant($this->restaurantId)
            ->where('name', 'like', "%{$this->search}%")
            ->orderBy('name')
            ->paginate(10);

        $pageHeader = [
            'title'       => 'Catégories',
            'subtitle'    => 'Liste des catégories',
            'breadcrumbs' => [
                ['label' => 'Accueil', 'url' => route('app.dashboard', $this->restaurantId)],
                ['label' => 'Catégories'],
            ],
        ];

        return view('livewire.categories.categories', [
            'categories'    => $categories,
            'pageHeader'    => $pageHeader,
            'restaurantId'  => $this->restaurantId,
        ])->layout('components.layouts.app', ['title' => 'Catégories']);
    }
}
