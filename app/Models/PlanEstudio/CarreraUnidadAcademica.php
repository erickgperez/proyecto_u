<?php

namespace App\Models\PlanEstudio;

use App\Models\User;
use App\Traits\UserStamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarreraUnidadAcademica extends Model
{

    use UserStamps;

    protected $table = 'plan_estudio.carrera_unidad_academica';


    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    public function carrera(): BelongsTo
    {
        return $this->belongsTo(Carrera::class, 'carrera_id');
    }

    public function unidadAcademica(): BelongsTo
    {
        return $this->belongsTo(UnidadAcademica::class, 'unidad_academica_id');
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
