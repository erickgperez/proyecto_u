<?php

namespace App\Models\PlanEstudio;

use App\Models\User;
use App\Traits\HasCreateMany;
use App\Traits\HasUuid;
use App\Traits\UserStamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UnidadAcademica extends Model
{

    use UserStamps, HasUuid, HasCreateMany;

    protected $table = 'plan_estudio.unidad_academica';

    protected $appends = ['nombreCompleto'];


    public function getNombreCompletoAttribute(): string
    {
        return (string) "({$this->codigo}) {$this->nombre}";
    }

    public function tipo(): BelongsTo
    {
        return $this->belongsTo(TipoUnidadAcademica::class, 'tipo_unidad_academica_id');
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
