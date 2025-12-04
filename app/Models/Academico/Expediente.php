<?php

namespace App\Models\Academico;

use App\Models\Academico\Estado;
use App\Models\Academico\Estudiante;
use App\Models\Academico\Semestre;
use App\Models\PlanEstudio\CarreraUnidadAcademica;
use App\Traits\HasCreateMany;
use App\Traits\HasUuid;
use App\Traits\UserStamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Expediente extends Model
{

    use UserStamps, HasUuid, HasCreateMany;

    protected $table = "academico.expediente";


    public function estudiante(): BelongsTo
    {
        return $this->belongsTo(Estudiante::class, 'estudiante_id');
    }

    public function carreraUnidadAcademica(): BelongsTo
    {
        return $this->belongsTo(CarreraUnidadAcademica::class, 'carrera_unidad_academica_id');
    }

    public function estado(): BelongsTo
    {
        return $this->belongsTo(Estado::class, 'estado_id');
    }
    public function semestre(): BelongsTo
    {
        return $this->belongsTo(Semestre::class, 'semestre_id');
    }
    public function tipoCurso(): BelongsTo
    {
        return $this->belongsTo(TipoCurso::class, 'tipo_curso_id');
    }

    public function inscritos(): BelongsToMany
    {
        return $this->belongsToMany(Imparte::class, 'academico.inscritos', 'expediente_id', 'imparte_id');
    }

    public function inscritosPivot(): HasMany
    {
        return $this->hasMany(Inscritos::class, 'expediente_id');
    }
}
