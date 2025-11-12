<?php

namespace App\Models\PlanEstudio;

use App\Models\User;
use App\Traits\UserStamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Requisitos extends Model
{

    use UserStamps;

    protected $table = 'plan_estudio.requisitos';



    public function unidadAcademica(): BelongsTo
    {
        return $this->belongsTo(UnidadAcademica::class, 'unidad_academica_id');
    }

    public function unidadAcademicaRequisito(): BelongsTo
    {
        return $this->belongsTo(UnidadAcademica::class, 'unidad_academica_requisito_id');
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
