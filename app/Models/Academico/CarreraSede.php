<?php

namespace App\Models\Academico;

use App\Models\Ingreso\Convocatoria;
use App\Models\PlanEstudio\Carrera;
use App\Models\User;
use App\Models\Workflow\SolicitudCarreraSede;
use App\Traits\HasCreateMany;
use App\Traits\UserStamps;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CarreraSede extends Pivot
{
    use UserStamps, HasCreateMany;

    protected $table = "academico.carrera_sede";

    protected $appends = ['titulo', 'tituloAbr'];

    protected $fillable = [
        'carrera_id',
        'sede_id'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function carrera(): BelongsTo
    {
        return $this->belongsTo(Carrera::class);
    }

    public function sede(): BelongsTo
    {
        return $this->belongsTo(Sede::class);
    }

    public function convocatorias(): BelongsToMany
    {
        return $this->belongsToMany(Convocatoria::class, 'academico.convocatoria_carrera_sede', 'carrera_sede_id', 'convocatoria_id');
    }

    public function solicitudes(): HasMany
    {
        return $this->hasMany(SolicitudCarreraSede::class, 'carrera_sede_id');
    }

    public function getTituloAttribute(): string
    {
        return (string) "{$this->sede->nombre} -- {$this->carrera->nombreCompleto}";
    }

    public function getTituloAbrAttribute(): string
    {
        return (string) "{$this->sede->codigo} -- {$this->carrera->nombreCompleto}";
    }

    public function docentes(): BelongsToMany
    {
        return $this->belongsToMany(Docente::class, 'academico.asignado', 'carrera_sede_id', 'docente_id');
    }
}
