<?php

namespace App\Models\Academico;

use App\Models\Persona;
use App\Traits\UserStamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Estudiante extends Model
{

    use UserStamps;

    protected $table = "academico.estudiante";


    public function persona(): BelongsTo
    {
        return $this->belongsTo(Persona::class);
    }
}
