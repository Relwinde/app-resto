<?php

namespace App\Livewire\Utilisateurs;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class Utilisateurs extends Component
{
    use WithPagination;

    public $restaurantId;
    public string $search = '';

    public function mount($restaurantId): void
    {
        $this->restaurantId = $restaurantId;
    }

    public function updatingSearch(): void { $this->resetPage(); }

    public function clear_search(): void
    {
        $this->search = '';
        $this->resetPage();
    }

    public function delete(int $id): void
    {
        Gate::authorize('Supprimer Utilisateur');

        if ($id === auth()->id()) {
            return;
        }

        User::forRestaurant($this->restaurantId)->find($id)?->delete();
        $this->dispatch('utilisateur-deleted');
    }

    #[On('utilisateur-created')]
    #[On('utilisateur-updated')]
    #[On('utilisateur-deleted')]
    public function render()
    {
        Gate::authorize('Voir Utilisateurs');

        $utilisateurs = User::with('roles')
            ->forRestaurant($this->restaurantId)
            ->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%");
            })
            ->orderBy('name')
            ->paginate(15);

        $pageHeader = [
            'title'       => 'Utilisateurs',
            'subtitle'    => 'Gestion des utilisateurs',
            'breadcrumbs' => [
                ['label' => 'Accueil', 'url' => route('app.dashboard', $this->restaurantId)],
                ['label' => 'Utilisateurs'],
            ],
        ];

        return view('livewire.utilisateurs.utilisateurs', compact('utilisateurs', 'pageHeader'))
            ->layout('components.layouts.app', ['title' => 'Utilisateurs']);
    }
}
