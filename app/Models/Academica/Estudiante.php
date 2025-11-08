<?php

namespace App\Models\Ingreso;

use App\Models\Persona;
use App\Models\Workflow\Solicitud;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Estudiante extends Model
{

    protected $table = "ingreso.estudiante";


    public function persona(): BelongsTo
    {
        return $this->belongsTo(Persona::class);
    }

    public function solicitudes(): MorphMany
    {
        return $this->morphMany(Solicitud::class, 'solicitante');
    }
}
