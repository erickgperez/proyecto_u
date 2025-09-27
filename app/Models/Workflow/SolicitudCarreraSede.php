<?php

namespace App\Models\Workflow;

use App\Models\Academica\CarreraSede;
use App\Models\User;
use App\Traits\UserStamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SolicitudCarreraSede extends Model
{
    use UserStamps;

    protected $table = 'workflow.solicitud_carrera_sede';

    protected $fillable = [
        'solicitud_id',
        'tipo_carrera_sede_solicitud_id',
        'carrera_sede_id',
    ];

    public function solicitud(): BelongsTo
    {
        return $this->belongsTo(Solicitud::class, 'solicitud_id');
    }

    public function tipoCarreraSedeSolicitud(): BelongsTo
    {
        return $this->belongsTo(TipoCarreraSedeSolicitud::class, 'tipo_carrera_sede_solicitud_id');
    }

    public function  carreraSede(): BelongsTo
    {
        return $this->belongsTo(CarreraSede::class, 'carrera_sede_id');
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
