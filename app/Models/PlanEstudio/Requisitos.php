<?php

namespace App\Models\PlanEstudio;

use App\Models\User;
use App\Traits\HasCreateMany;
use App\Traits\HasUuid;
use App\Traits\UserStamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Requisitos extends Model
{

    use UserStamps, HasUuid, HasCreateMany;

    protected $table = 'plan_estudio.requisitos';



    public function unidadAcademica(): BelongsTo
    {
        return $this->belongsTo(CarreraUnidadAcademica::class, 'carrera_unidad_academica_id');
    }

    public function unidadAcademicaRequisito(): BelongsTo
    {
        return $this->belongsTo(CarreraUnidadAcademica::class, 'carrera_unidad_academica_requisito_id');
    }

    public function tipo(): BelongsTo
    {
        return $this->belongsTo(TipoRequisito::class, 'tipo_requisito_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
