<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fournisseur extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'phone'];

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }
}
