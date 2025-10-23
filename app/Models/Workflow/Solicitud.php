<?php

namespace App\Models\Workflow;

use App\Models\Academica\Sede;
use App\Models\Persona;
use App\Models\Rol;
use App\Models\User;
use App\Traits\UserStamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Solicitud extends Model
{
    use UserStamps;

    protected $table = 'workflow.solicitud';

    protected $fillable = [
        'comentario',
        'flujo_id',
        'etapa_id',
        'persona_id',
        'rol_id',
        'estado_id',
    ];


    public function flujo(): BelongsTo
    {
        return $this->belongsTo(Flujo::class, 'flujo_id');
    }

    public function etapa(): BelongsTo
    {
        return $this->belongsTo(Etapa::class, 'etapa_id');
    }


    public function estado(): BelongsTo
    {
        return $this->belongsTo(Estado::class, 'estado_id');
    }

    public function sede(): BelongsTo
    {
        return $this->belongsTo(Sede::class, 'sede_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function solicitudCarrerasSede(): HasMany
    {
        return $this->hasMany(SolicitudCarreraSede::class);
    }

    public function historial(): HasMany
    {
        return $this->hasMany(Historial::class);
    }

    public function modelo(): MorphTo
    {
        return $this->morphTo();
    }

    public function solicitante(): MorphTo
    {
        return $this->morphTo();
    }

    public function guardarHistorial()
    {

        $this->historial()->create([
            'comentario' => $this->comentario,
            'estado_id' => $this->estado_id,
            'etapa_id' => $this->etapa_id
        ]);
    }

    public function pasarSiguienteEtapa(): void
    {
        $transicion = $this->flujo->getTransicion($this->etapa);

        if ($transicion) {
            $this->etapa()->associate($transicion->etapaDestino);
            $this->estado()->associate($transicion->estadoDestino);
        }
    }
}
