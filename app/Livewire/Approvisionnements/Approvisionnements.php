<?php

namespace App\Livewire\Approvisionnements;

use App\Models\Approvisionnement;
use App\Models\Caisse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
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
        Gate::authorize('Supprimer Approvisionnement');

        $appro = Approvisionnement::find($id);
        if (! $appro) {
            return;
        }

        DB::transaction(function () use ($appro) {
            if ($appro->caisse_id && (float) $appro->montant_total > 0) {
                $caisse = Caisse::find($appro->caisse_id);
                if ($caisse) {
                    $caisse->rembourser(
                        (float) $appro->montant_total,
                        "Remboursement suite à suppression de l'approvisionnement {$appro->numero}",
                        $appro->id
                    );
                }
            }

            $appro->delete(); // cascade sur les lignes stock_movements
        });

        $this->dispatch('approvisionnement-deleted');
    }

    #[On('approvisionnement-created')]
    #[On('approvisionnement-updated')]
    #[On('approvisionnement-deleted')]
    public function render()
    {
        Gate::authorize('Voir Approvisionnements');

        $approvisionnements = Approvisionnement::with(['fournisseur', 'caisse', 'lignes.product'])
            ->when($this->search, function ($query) {
                $query->whereHas('fournisseur', fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
                      ->orWhereHas('lignes.product', fn ($q) => $q->where('name', 'like', "%{$this->search}%"));
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
            'sessionOuverte'     => Caisse::sessionOuverte(),
        ])->layout('components.layouts.app', ['title' => 'Approvisionnements']);
    }
}
