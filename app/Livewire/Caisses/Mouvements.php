<?php

namespace App\Livewire\Caisses;

use App\Models\Caisse;
use App\Models\MouvementCaisse;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithPagination;

class Mouvements extends Component
{
    use WithPagination;

    public string $caisse_id = '';
    public string $type      = '';
    public string $dateMin   = '';
    public string $dateMax   = '';

    public function updatingCaisseId(): void { $this->resetPage(); }
    public function updatingType(): void { $this->resetPage(); }

    public function clear_filtres(): void
    {
        $this->caisse_id = '';
        $this->type      = '';
        $this->dateMin   = '';
        $this->dateMax   = '';
        $this->resetPage();
    }

    public function render()
    {
        Gate::authorize('Voir Journal Caisse');

        $caisse = $this->caisse_id
            ? Caisse::find($this->caisse_id)
            : Caisse::where('statut', 'active')->first();

        $mouvements = MouvementCaisse::with(['caisse', 'commande', 'user'])
            ->when($this->caisse_id, fn ($q) => $q->where('caisse_id', $this->caisse_id))
            ->when($this->type, fn ($q) => $q->where('type', $this->type))
            ->when($this->dateMin, fn ($q) => $q->whereDate('created_at', '>=', $this->dateMin))
            ->when($this->dateMax, fn ($q) => $q->whereDate('created_at', '<=', $this->dateMax))
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $caisses = Caisse::orderBy('nom')->get();

        $pageHeader = [
            'title'       => 'Journal de caisse',
            'subtitle'    => 'Mouvements de caisse',
            'breadcrumbs' => [
                ['label' => 'Accueil', 'url' => route('dashboard')],
                ['label' => 'Caisse', 'url' => route('caisse')],
                ['label' => 'Journal'],
            ],
        ];

        return view('livewire.caisses.mouvements', compact('mouvements', 'caisses', 'caisse', 'pageHeader'))
            ->layout('components.layouts.app', ['title' => 'Journal de caisse']);
    }
}
