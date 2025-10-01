<?php

namespace App\Models\Workflow;

use App\Models\Persona;
use App\Models\Rol;
use App\Models\User;
use App\Traits\UserStamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Solicitud extends Model
{
    use UserStamps;

    protected $table = 'workflow.solicitud';

    protected $fillable = [
        'comentario',
        'flujo_modelo_id',
        'flujo_id',
        'etapa_id',
        'persona_id',
        'rol_id',
        'estado_id',
    ];

    public function flujoModelo(): BelongsTo
    {
        return $this->belongsTo(FlujoModelo::class, 'flujo_modelo_id');
    }

    public function flujo(): BelongsTo
    {
        return $this->belongsTo(Flujo::class, 'flujo_id');
    }

    public function etapa(): BelongsTo
    {
        return $this->belongsTo(Etapa::class, 'etapa_id');
    }

    public function persona(): BelongsTo
    {
        return $this->belongsTo(Persona::class, 'persona_id');
    }

    public function rol(): BelongsTo
    {
        return $this->belongsTo(Rol::class, 'rol_id');
    }

    public function estado(): BelongsTo
    {
        return $this->belongsTo(Estado::class, 'estado_id');
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
