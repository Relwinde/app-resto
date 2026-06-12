<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Depense extends Model
{
    protected $fillable = [
        'caisse_id', 'session_caisse_id', 'user_id', 'valide_par', 'paye_par',
        'montant', 'motif', 'beneficiaire', 'note', 'statut', 'valide_le', 'paye_le',
    ];

    protected $casts = [
        'montant'    => 'decimal:2',
        'valide_le'  => 'datetime',
        'paye_le'    => 'datetime',
    ];

    public function caisse(): BelongsTo
    {
        return $this->belongsTo(Caisse::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function validePar(): BelongsTo
    {
        return $this->belongsTo(User::class, 'valide_par');
    }

    public function payePar(): BelongsTo
    {
        return $this->belongsTo(User::class, 'paye_par');
    }

    public function mouvement(): HasOne
    {
        return $this->hasOne(MouvementCaisse::class);
    }

    public function estEdite(): bool     { return $this->statut === 'edite'; }
    public function estEnAttente(): bool { return $this->statut === 'en_attente'; }
    public function estValide(): bool    { return $this->statut === 'valide'; }
    public function estPaye(): bool      { return $this->statut === 'paye'; }
}
