<?php

namespace App\Models;

use App\Traits\UserStamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TipoEvento extends Model
{
    use UserStamps;

    protected $table = 'public.tipo_evento';

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function tipoCalendarizacion(): BelongsTo
    {
        return $this->belongsTo(TipoCalendarizacion::class, 'tipo_calendarizacion_id');
    }
}
