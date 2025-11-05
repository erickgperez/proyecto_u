<?php

namespace App\Models\Secundaria;

use App\Models\Estudio;
use App\Models\PlanEstudio\Carrera as PlanEstudioCarrera;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Carrera extends Model
{
    //
    protected $table = "secundaria.carrera";

    public function estudios()
    {
        return $this->morphMany(Estudio::class, 'carrera');
    }

    public function carrerasUniversitarias(): BelongsToMany
    {
        return $this->belongsToMany(PlanEstudioCarrera::class, 'ingreso.relacion_carreras', 'carrera_secundaria_id', 'carrera_universitaria_id');
    }
}
