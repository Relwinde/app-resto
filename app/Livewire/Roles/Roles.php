<?php

namespace App\Livewire\Roles;

use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class Roles extends Component
{
    public $restaurantId;

    public function mount($restaurantId): void
    {
        $this->restaurantId = $restaurantId;
    }

    #[On('role-created')]
    #[On('role-updated')]
    public function render()
    {
        Gate::authorize('Voir Rôles');

        $roles = Role::withCount('users')
            ->with('permissions')
            ->where('name', '!=', 'super-admin')
            ->orderBy('name')
            ->get();

        $pageHeader = [
            'title'       => 'Rôles',
            'subtitle'    => 'Gestion des rôles et permissions',
            'breadcrumbs' => [
                ['label' => 'Accueil', 'url' => route('app.dashboard', $this->restaurantId)],
                ['label' => 'Rôles'],
            ],
        ];

        return view('livewire.roles.roles', compact('roles', 'pageHeader'))
            ->layout('components.layouts.app', ['title' => 'Rôles']);
    }
}
