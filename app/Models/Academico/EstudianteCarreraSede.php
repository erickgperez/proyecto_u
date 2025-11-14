<?php

namespace App\Models\Academico;

use App\Models\User;
use App\Traits\UserStamps;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class EstudianteCarreraSede extends Pivot
{
    use UserStamps;

    protected $table = "academico.estudiante_carrera_sede";

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function carreraSede(): BelongsTo
    {
        return $this->belongsTo(CarreraSede::class);
    }

    public function estudiante(): BelongsTo
    {
        return $this->belongsTo(Estudiante::class);
    }

    public function estado(): BelongsTo
    {
        return $this->belongsTo(Estado::class);
    }
}
