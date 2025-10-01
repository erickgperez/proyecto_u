<?php

namespace App\Models\Academica;

use App\Models\PlanEstudio\Carrera;
use App\Models\User;
use App\Traits\UserStamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarreraSede extends Model
{
    use UserStamps;

    protected $table = "academico.carrera_sede";

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
}
