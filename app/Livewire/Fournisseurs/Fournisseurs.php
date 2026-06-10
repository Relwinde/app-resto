<?php

namespace App\Livewire\Fournisseurs;

use App\Models\Fournisseur;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;

class Fournisseurs extends Component
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
        $fournisseur = Fournisseur::find($id);
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
        $fournisseurs = Fournisseur::withCount('stockMovements')
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
                ['label' => 'Accueil', 'url' => route('dashboard')],
                ['label' => 'Fournisseurs'],
            ],
        ];

        return view('livewire.fournisseurs.fournisseurs', [
            'fournisseurs' => $fournisseurs,
            'pageHeader'   => $pageHeader,
        ])->layout('components.layouts.app', ['title' => 'Fournisseurs']);
    }
}
