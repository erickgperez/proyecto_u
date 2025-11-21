<?php

namespace App\Models;

use App\Traits\HasCreateMany;
use App\Traits\HasUuid;
use App\Traits\UserStamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Evento extends Model
{
    use UserStamps, HasUuid, HasCreateMany;

    protected $table = 'public.evento';

    protected $fillable = [
        'nombre',
        'fecha_inicio',
        'fecha_fin',
        'indicaciones',
        'completado',
        'calendarizacion_id'
    ];


    public function calendario(): BelongsTo
    {
        return $this->belongsTo(Calendarizacion::class, 'calendarizacion_id');
    }

    public function tipo(): BelongsTo
    {
        return $this->belongsTo(TipoEvento::class, 'tipo_evento_id');
    }
}
