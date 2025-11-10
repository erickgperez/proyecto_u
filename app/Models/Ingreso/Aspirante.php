<?php

namespace App\Models\Ingreso;

use App\Models\Persona;
use App\Models\Workflow\Solicitud;
use App\Traits\UserStamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Aspirante extends Model
{

    use UserStamps;

    protected $table = "ingreso.aspirante";

    protected $fillable = [
        'nie',
        'persona_id'
    ];

    public function persona(): BelongsTo
    {
        return $this->belongsTo(Persona::class);
    }

    public function convocatorias(): BelongsToMany
    {
        return $this->belongsToMany(Convocatoria::class, 'ingreso.convocatoria_aspirante', 'aspirante_id', 'convocatoria_id');
    }

    public function solicitudes(): MorphMany
    {
        return $this->morphMany(Solicitud::class, 'solicitante');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
