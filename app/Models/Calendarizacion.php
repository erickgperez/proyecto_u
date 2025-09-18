<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Calendarizacion extends Model
{
    protected $table = 'public.calendarizacion';

    public function eventos(): HasMany
    {
        return $this->hasMany(Evento::class, 'calendarizacion_id');
    }
}
