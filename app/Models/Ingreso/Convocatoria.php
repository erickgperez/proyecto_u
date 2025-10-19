<?php

namespace App\Models\Ingreso;

use App\Models\Academica\CarreraSede;
use App\Models\Calendarizacion;
use App\Models\Secundaria\Invitacion;
use App\Models\User;
use App\Models\Workflow\Solicitud;
use App\Traits\UserStamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Convocatoria extends Model
{
    use UserStamps;

    protected $table = "ingreso.convocatoria";

    protected $fillable = [
        'nombre',
        'descripcion',
        'fecha',
        'cuerpo_mensaje',
        'afiche',
        'calendarizacion_id'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function calendario()
    {
        return $this->belongsTo(Calendarizacion::class, 'calendarizacion_id');
    }

    public function invitaciones(): HasMany
    {

        return $this->hasMany(Invitacion::class);
    }

    public function aspirantes(): BelongsToMany
    {
        return $this->belongsToMany(Aspirante::class, 'ingreso.convocatoria_aspirante', 'convocatoria_id', 'aspirante_id');
    }

    public function solicitudes(): MorphMany
    {
        return $this->morphMany(Solicitud::class, 'modelo');
    }

    public function carrerasSedes(): BelongsToMany
    {
        return $this->belongsToMany(CarreraSede::class, 'ingreso.convocatoria_carrera_sede', 'convocatoria_id', 'carrera_sede_id');
    }
}
