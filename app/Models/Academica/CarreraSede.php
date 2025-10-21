<?php

namespace App\Models\Academica;

use App\Models\Ingreso\Aspirante;
use App\Models\Ingreso\Convocatoria;
use App\Models\PlanEstudio\Carrera;
use App\Models\User;
use App\Traits\UserStamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CarreraSede extends Model
{
    use UserStamps;

    protected $table = "academico.carrera_sede";

    protected $appends = ['titulo'];

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

    public function seleccionados(): BelongsToMany
    {
        return $this->belongsToMany(Aspirante::class, 'ingreso.convocatoria_aspirante', 'carrera_sede_id', 'aspirante_id');
    }

    public function getTituloAttribute(): string
    {
        return (string) "{$this->sede->nombre} -- {$this->carrera->nombreCompleto}";
    }
}
