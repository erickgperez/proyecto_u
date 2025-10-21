<?php

namespace App\Models\Ingreso;

use App\Models\Academica\CarreraSede;
use App\Models\Calendarizacion;
use App\Models\Secundaria\Invitacion;
use App\Models\User;
use App\Models\Workflow\Solicitud;
use App\Traits\UserStamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class ConvocatoriaAspirante extends Model
{
    use UserStamps;

    protected $table = "ingreso.convocatoria_aspirante";

    protected $fillable = [
        'convocatoria_id',
        'aspirante_id',
        'carrera_sede_id',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function convocatoria(): BelongsTo
    {
        return $this->belongsTo(Convocatoria::class);
    }

    public function carreraSede(): BelongsTo
    {
        return $this->belongsTo(CarreraSede::class);
    }

    public function aspirante(): BelongsTo
    {
        return $this->belongsTo(Aspirante::class);
    }
}
