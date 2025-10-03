<?php

namespace App\Models\Ingreso;

use App\Models\Persona;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Aspirante extends Model
{

    protected $table = "ingreso.aspirante";

    protected $fillable = [
        'persona_id'
    ];

    public function persona(): BelongsTo
    {
        return $this->belongsTo(Persona::class);
    }

    public function convocatorias(): BelongsToMany
    {
        return $this->belongsToMany(Convocatoria::class, 'ingreso.convocatoria_aspirante', 'aspirante_id', 'convocatoria_id');
    }
}
