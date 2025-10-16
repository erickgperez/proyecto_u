<?php

namespace App\Models\Academica;

use App\Models\PlanEstudio\Carrera;
use App\Models\User;
use App\Traits\UserStamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Sede extends Model
{
    use UserStamps;

    protected $table = "academico.sede";

    protected $fillable = [
        'codigo',
        'nombre',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function carreras(): BelongsToMany
    {
        return $this->belongsToMany(Carrera::class, 'academico.carrera_sede', 'sede_id', 'carrera_id')->withPivot('cupo');
    }
}
