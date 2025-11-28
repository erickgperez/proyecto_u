<?php

namespace App\Models\Academico;

use App\Models\PlanEstudio\CarreraUnidadAcademica;
use App\Models\User;
use App\Traits\HasCreateMany;
use App\Traits\HasUuid;
use App\Traits\UserStamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\hasMany;

class Oferta extends Model
{
    use UserStamps, HasUuid, HasCreateMany;

    protected $table = "academico.oferta";

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function carreraUnidadAcademica(): BelongsTo
    {
        return $this->belongsTo(CarreraUnidadAcademica::class);
    }

    public function semestre(): BelongsTo
    {
        return $this->belongsTo(Semestre::class);
    }

    public function imparte(): hasMany
    {
        return $this->hasMany(Imparte::class, 'oferta_id');
    }
}
