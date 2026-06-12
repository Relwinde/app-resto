<?php

namespace App\Livewire\Caisses\Modals;

use App\Models\Depense;
use Illuminate\Support\Facades\Gate;
use Livewire\WithFileUploads;
use LivewireUI\Modal\ModalComponent;

class CreateDepense extends ModalComponent
{
    use WithFileUploads;

    public string $motif        = '';
    public string $montant      = '';
    public string $beneficiaire = '';
    public string $note         = '';
    public $fichier             = null;

    public function removeFile(): void
    {
        $this->fichier = null;
    }

    public function create(): void
    {
        Gate::authorize('Créer Dépense');

        $this->validate([
            'motif'        => ['required', 'string', 'max:255'],
            'montant'      => ['required', 'numeric', 'min:1'],
            'beneficiaire' => ['nullable', 'string', 'max:255'],
            'note'         => ['nullable', 'string', 'max:1000'],
            'fichier'      => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ], [
            'motif.required'   => 'Le motif est obligatoire.',
            'montant.required' => 'Le montant est obligatoire.',
            'montant.min'      => 'Le montant doit être supérieur à 0.',
            'fichier.mimes'    => 'Le fichier doit être un PDF ou une image (jpg, jpeg, png).',
            'fichier.max'      => 'Le fichier ne doit pas dépasser 10 Mo.',
        ]);

        $depense = Depense::create([
            'user_id'      => auth()->id(),
            'montant'      => $this->montant,
            'motif'        => $this->motif,
            'beneficiaire' => $this->beneficiaire ?: null,
            'note'         => $this->note ?: null,
            'statut'       => 'edite',
        ]);

        if ($this->fichier) {
            $path = $this->fichier->storeAs(
                "files/depenses/{$depense->id}",
                $this->fichier->getClientOriginalName(),
                'local'
            );
            $depense->files()->create([
                'original_name' => $this->fichier->getClientOriginalName(),
                'path'          => $path,
                'mime_type'     => $this->fichier->getMimeType(),
                'size'          => $this->fichier->getSize(),
            ]);
        }

        $this->dispatch('depense-created');
        $this->reset();
        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.caisses.modals.create-depense');
    }
}
