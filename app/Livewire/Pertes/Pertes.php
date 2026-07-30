<?php

namespace App\Livewire\Pertes;

use App\Models\Perte;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;

class Pertes extends Component
{
    use WithPagination;

    public string $search = '';

    public array $motifs = [
        'perime' => 'Périmé',
        'casse'  => 'Casse / destruction accidentelle',
        'vol'    => 'Vol',
        'autre'  => 'Autre',
    ];

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
        Gate::authorize('Supprimer Perte');

        $perte = Perte::find($id);
        if ($perte) {
            $perte->delete(); // cascade sur les lignes stock_movements
            $this->dispatch('perte-deleted');
        }
    }

    #[On('perte-created')]
    #[On('perte-updated')]
    #[On('perte-deleted')]
    public function render()
    {
        Gate::authorize('Voir Pertes');

        $pertes = Perte::with(['user', 'lignes.product'])
            ->when($this->search, function ($query) {
                $query->whereHas('lignes.product', fn ($q) => $q->where('name', 'like', "%{$this->search}%"));
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $pageHeader = [
            'title'       => 'Pertes',
            'subtitle'    => 'Produits périmés, cassés ou détruits',
            'breadcrumbs' => [
                ['label' => 'Accueil', 'url' => route('dashboard')],
                ['label' => 'Pertes'],
            ],
        ];

        return view('livewire.pertes.pertes', [
            'pertes'     => $pertes,
            'pageHeader' => $pageHeader,
        ])->layout('components.layouts.app', ['title' => 'Pertes']);
    }
}
