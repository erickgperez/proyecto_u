<?php

namespace App\Models\Academico;

use App\Models\Persona;
use App\Traits\HasCreateMany;
use App\Traits\HasUuid;
use App\Traits\UserStamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Estudiante extends Model
{

    use UserStamps, HasUuid, HasCreateMany;

    protected $table = "academico.estudiante";


    public function persona(): BelongsTo
    {
        return $this->belongsTo(Persona::class);
    }

    public function expediente(): HasMany
    {
        return $this->hasMany(Expediente::class);
    }

    public function carreraSede(): BelongsToMany
    {
        return $this->belongsToMany(CarreraSede::class, 'academico.estudiante_carrera_sede', 'estudiante_id', 'carrera_sede_id')
            ->withPivot('estado_id');
    }
}
