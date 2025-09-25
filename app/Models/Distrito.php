<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Distrito extends Model
{
    //
    protected $table = 'distrito';

    protected $appends = ['nombreCompleto'];

    public function municipio(): BelongsTo
    {
        return $this->belongsTo(Municipio::class);
    }

    public function getNombreCompletoAttribute(): string
    {
        return (string) "{$this->descripcion} ({$this->municipio->descripcion}/{$this->municipio->departamento->descripcion})";
    }
}
