<?php

namespace App\Models\PlanEstudio;

use App\Models\PlanEstudio\Grado;
use App\Traits\HasCreateMany;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TipoCarrera extends Model
{
    use HasUuid, HasCreateMany;

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
