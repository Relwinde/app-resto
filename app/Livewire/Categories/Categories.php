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
        Gate::authorize('Supprimer Catégorie');
        $categorie = Category::find($id);
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
            ->where('name', 'like', "%{$this->search}%")
            ->orderBy('name')
            ->paginate(10);

        $pageHeader = [
            'title'       => 'Catégories',
            'subtitle'    => 'Liste des catégories',
            'breadcrumbs' => [
                ['label' => 'Accueil', 'url' => route('dashboard')],
                ['label' => 'Catégories'],
            ],
        ];

        return view('livewire.categories.categories', [
            'categories' => $categories,
            'pageHeader' => $pageHeader,
        ])->layout('components.layouts.app', ['title' => 'Catégories']);
    }
}
