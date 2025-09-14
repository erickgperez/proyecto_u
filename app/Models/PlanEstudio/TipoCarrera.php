<?php

namespace App\Models\PlanEstudio;

use App\Models\PlanEstudio\Grado;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TipoCarrera extends Model
{
    protected $table = 'plan_estudio.tipo_carrera';

    protected $fillable = [
        'codigo',
        'descripcion',
        'grado_id'
    ];

    public function grado(): BelongsTo
    {
        return $this->belongsTo(Grado::class);
    }
}
