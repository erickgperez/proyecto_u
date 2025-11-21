<?php

namespace App\Models;

use App\Traits\HasCreateMany;
use App\Traits\HasUuid;
use App\Traits\UserStamps;
use Illuminate\Database\Eloquent\Model;

class TipoCalendarizacion extends Model
{
    use UserStamps, HasUuid, HasCreateMany;

    protected $table = 'public.tipo_calendarizacion';

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
