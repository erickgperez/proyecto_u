<?php

namespace App\Models\PlanEstudio;

use App\Models\User;
use App\Traits\UserStamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Carrera extends Model
{

    use UserStamps;

    protected $table = 'plan_estudio.carrera';

    protected $appends = ['nombreCompleto'];

    protected $fillable = [
        'codigo',
        'nombre',
        'certificacion_de',
        'tipo_carrera_id',
    ];

    public function padre(): BelongsTo
    {
        return $this->belongsTo(Carrera::class, 'certificacion_de');
    }

    public function tipo(): BelongsTo
    {
        return $this->belongsTo(TipoCarrera::class, 'tipo_carrera_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function getNombreCompletoAttribute(): string
    {
        return (string) "({$this->codigo}) {$this->nombre}";
    }
}
