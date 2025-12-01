<?php

namespace App\Models\Academico;

use App\Models\Calendarizacion;
use App\Models\User;
use App\Traits\HasCreateMany;
use App\Traits\HasUuid;
use App\Traits\UserStamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Semestre extends Model
{
    use UserStamps, HasUuid, HasCreateMany;

    protected $table = "academico.semestre";

    protected $fillable = [
        'codigo',
        'anio',
        'descripcion',
        'fecha_inicio',
        'fecha_fin',
        'calendarizacion_id'
    ];

    protected $appends = ['nombre'];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function calendario(): BelongsTo
    {
        return $this->belongsTo(Calendarizacion::class, 'calendarizacion_id');
    }

    public function getNombreAttribute()
    {
        return $this->codigo . ' - ' . $this->anio;
    }
}
