<?php

namespace App\Models\Workflow;

use App\Models\User;
use App\Traits\UserStamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transicion extends Model
{
    use UserStamps;

    protected $table = 'workflow.flujo';

    protected $fillable = [
        'codigo',
        'nombre',
        'flujo_id',
        'etapa_origen_id',
        'etapa_destino_id',
        'estado_id'
    ];

    public function flujo(): BelongsTo
    {
        return $this->belongsTo(Flujo::class, 'flujo_id');
    }

    public function etapaOrigen(): BelongsTo
    {
        return $this->belongsTo(Etapa::class, 'etapa_origen_id');
    }

    public function etapaDestino(): BelongsTo
    {
        return $this->belongsTo(Etapa::class, 'etapa_destino_id');
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
