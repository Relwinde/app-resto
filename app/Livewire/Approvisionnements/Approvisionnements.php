<?php

namespace App\Livewire\Approvisionnements;

use App\Models\Caisse;
use App\Models\StockMovement;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;

class Approvisionnements extends Component
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
        Gate::authorize('Supprimer Approvisionnement');
        $appro = StockMovement::forRestaurant($this->restaurantId)->find($id);
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
        Gate::authorize('Voir Approvisionnements');

        $approvisionnements = StockMovement::with(['product', 'fournisseur'])
            ->forRestaurant($this->restaurantId)
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
                ['label' => 'Accueil', 'url' => route('app.dashboard', $this->restaurantId)],
                ['label' => 'Approvisionnements'],
            ],
        ];

        return view('livewire.approvisionnements.approvisionnements', [
            'approvisionnements' => $approvisionnements,
            'pageHeader'         => $pageHeader,
            'restaurantId'       => $this->restaurantId,
            'sessionOuverte'     => Caisse::sessionOuverte($this->restaurantId),
        ])->layout('components.layouts.app', ['title' => 'Approvisionnements']);
    }
}
