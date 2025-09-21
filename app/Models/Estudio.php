<?php

namespace App\Models;

use App\Models\Documento\Documento;
use App\Models\PlanEstudio\Grado;
use App\Models\User;
use App\Traits\UserStamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Estudio extends Model
{
    use UserStamps;

    protected $table = "estudio";

    protected $fillable = [
        'nombre_titulo',
        'nombre_institucion',
        'fecha_finalizacion',
        'persona_id',
        'grado_id',
        'pais_id',
        'documento_id'
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function persona(): BelongsTo
    {
        return $this->belongsTo(Persona::class);
    }

    public function grado(): BelongsTo
    {
        return $this->belongsTo(Grado::class);
    }

    public function pais(): BelongsTo
    {
        return $this->belongsTo(Pais::class);
    }

    public function documento(): BelongsTo
    {
        return $this->belongsTo(Documento::class);
    }
}
