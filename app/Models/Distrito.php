<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Distrito extends Model
{
    //
    protected $table = 'distrito';

    public function municipio(): BelongsTo
    {
        return $this->belongsTo(Municipio::class);
    }
}
