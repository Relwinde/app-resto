<?php

namespace App\Livewire\Caisses\Modals;

use App\Models\Depense;
use Illuminate\Support\Facades\Gate;
use Livewire\WithFileUploads;
use LivewireUI\Modal\ModalComponent;

class AttachFileDepense extends ModalComponent
{
    use WithFileUploads;

    public int $depenseId = 0;
    public $fichier       = null;

    public function mount(int $depenseId): void
    {
        $depense = Depense::find($depenseId);

        if (! $depense) {
            $this->dispatch('notify', message: 'Bon de caisse introuvable.', type: 'error');
            $this->closeModal();
            return;
        }

        $this->depenseId = $depenseId;
    }

    public function removeFile(): void
    {
        $this->fichier = null;
    }

    public function attach(): void
    {
        Gate::authorize('Modifier Dépense');

        $this->validate([
            'fichier' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ], [
            'fichier.required' => 'Veuillez sélectionner un fichier.',
            'fichier.mimes'    => 'Le fichier doit être un PDF ou une image (jpg, jpeg, png).',
            'fichier.max'      => 'Le fichier ne doit pas dépasser 10 Mo.',
        ]);

        $depense = Depense::findOrFail($this->depenseId);

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

        $this->dispatch('depense-modifiee');
        $this->closeModal();
    }

    public function render()
    {
        $depense = Depense::with('files')->find($this->depenseId);
        return view('livewire.caisses.modals.attach-file-depense', compact('depense'));
    }
}
