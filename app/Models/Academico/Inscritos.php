<?php

namespace App\Models\Academico;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inscritos extends Model
{

    protected $table = "academico.inscritos";

    public function imparte(): BelongsTo
    {
        return $this->belongsTo(Imparte::class, 'imparte_id');
    }

    public function expediente(): BelongsTo
    {
        return $this->belongsTo(Expediente::class, 'expediente_id');
    }

    public function calificacion(): HasMany
    {
        return $this->hasMany(Calificacion::class, 'inscrito_id');
    }
}
