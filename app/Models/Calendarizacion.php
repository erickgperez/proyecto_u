<?php

namespace App\Models;

use App\Traits\HasCreateMany;
use App\Traits\HasUuid;
use App\Traits\UserStamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Calendarizacion extends Model
{
    use UserStamps, HasUuid, HasCreateMany;

    protected $table = 'public.calendarizacion';

    public function eventos(): HasMany
    {
        return $this->hasMany(Evento::class, 'calendarizacion_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function tipo(): BelongsTo
    {
        return $this->belongsTo(TipoCalendarizacion::class, 'tipo_calendarizacion_id');
    }
}
