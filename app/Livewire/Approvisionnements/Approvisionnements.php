<?php

namespace App\Livewire\Approvisionnements;

use App\Models\StockMovement;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;

class Approvisionnements extends Component
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
        $appro = StockMovement::find($id);
        if ($appro) {
            $appro->delete();
            $this->dispatch('approvisionnement-deleted');
        }
    }

    #[On('approvisionnement-created')]
    #[On('approvisionnement-updated')]
    #[On('approvisionnement-deleted')]
    public function render()
    {
        $approvisionnements = StockMovement::with(['product', 'fournisseur'])
            ->when($this->search, function ($query) {
                $query->whereHas('product', fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
                      ->orWhereHas('fournisseur', fn ($q) => $q->where('name', 'like', "%{$this->search}%"));
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $pageHeader = [
            'title'       => 'Approvisionnements',
            'subtitle'    => 'Historique des entrées de stock',
            'breadcrumbs' => [
                ['label' => 'Accueil', 'url' => route('dashboard')],
                ['label' => 'Approvisionnements'],
            ],
        ];

        return view('livewire.approvisionnements.approvisionnements', [
            'approvisionnements' => $approvisionnements,
            'pageHeader'         => $pageHeader,
        ])->layout('components.layouts.app', ['title' => 'Approvisionnements']);
    }
}
