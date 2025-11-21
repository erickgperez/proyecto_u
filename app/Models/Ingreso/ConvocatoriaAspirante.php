<?php

namespace App\Models\Ingreso;

use App\Models\Academico\CarreraSede;
use App\Models\User;
use App\Models\Workflow\SolicitudCarreraSede;
use App\Traits\HasCreateMany;
use App\Traits\HasUuid;
use App\Traits\UserStamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConvocatoriaAspirante extends Model
{
    use UserStamps, HasUuid, HasCreateMany;

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

    public function solicitudCarreraSede(): BelongsTo
    {
        return $this->belongsTo(SolicitudCarreraSede::class);
    }

    public function aspirante(): BelongsTo
    {
        return $this->belongsTo(Aspirante::class);
    }
}
