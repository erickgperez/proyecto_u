<?php

namespace App\Models\Academico;

use App\Models\Persona;
use App\Models\User;
use App\Traits\UserStamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Docente extends Model
{
    use UserStamps;

    protected $table = "academico.docente";

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function persona(): BelongsTo
    {
        return $this->belongsTo(Persona::class);
    }

    public function carrerasSedes(): BelongsToMany
    {
        return $this->belongsToMany(CarreraSede::class, 'academico.asignado', 'docente_id', 'carrera_sede_id')->withPivot('principal');
    }
}
