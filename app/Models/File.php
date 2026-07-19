<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'fileable_id',
        'fileable_type',
        'original_name',
        'path',
        'mime_type',
        'size',
    ];

    public function fileable(): MorphTo
    {
        return $this->morphTo();
    }
}
