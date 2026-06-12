<?php

namespace App\Livewire\Caisses\Modals;

use App\Models\Depense;
use Illuminate\Support\Facades\Gate;
use Livewire\WithFileUploads;
use LivewireUI\Modal\ModalComponent;

class EditDepense extends ModalComponent
{
    use WithFileUploads;

    public int    $depenseId    = 0;
    public string $motif        = '';
    public string $montant      = '';
    public string $beneficiaire = '';
    public string $note         = '';
    public $fichier             = null;

    public function mount(int $depenseId): void
    {
        $depense = Depense::find($depenseId);

        if (! $depense || ! $depense->estEdite()) {
            $this->dispatch('notify', message: 'Ce bon ne peut pas être modifié dans son état actuel.', type: 'error');
            $this->closeModal();
            return;
        }

        $this->depenseId    = $depenseId;
        $this->motif        = $depense->motif;
        $this->montant      = (string) $depense->montant;
        $this->beneficiaire = $depense->beneficiaire ?? '';
        $this->note         = $depense->note ?? '';
    }

    public function removeFile(): void
    {
        $this->fichier = null;
    }

    public function update(): void
    {
        Gate::authorize('Modifier Dépense');

        $depense = Depense::findOrFail($this->depenseId);

        if (! $depense->estEdite()) {
            $this->dispatch('notify', message: 'Ce bon ne peut plus être modifié.', type: 'error');
            $this->closeModal();
            return;
        }

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

        $depense->update([
            'motif'        => $this->motif,
            'montant'      => $this->montant,
            'beneficiaire' => $this->beneficiaire ?: null,
            'note'         => $this->note ?: null,
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

        $this->dispatch('depense-modifiee');
        $this->closeModal();
    }

    public function render()
    {
        $depense = Depense::with('files')->find($this->depenseId);
        return view('livewire.caisses.modals.edit-depense', compact('depense'));
    }
}
