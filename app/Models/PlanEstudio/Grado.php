<?php

namespace App\Models\PlanEstudio;

use App\Traits\HasCreateMany;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class Grado extends Model
{

    use HasUuid, HasCreateMany;

    protected $table = 'plan_estudio.grado';

    protected $fillable = [
        'codigo',
        'descripcion_masculino',
        'descripcion_femenino'
    ];

    protected $appends = ['descripciones'];

    public function getDescripcionesAttribute()
    {
        return "{$this->descripcion_masculino}, {$this->descripcion_femenino}";
    }
}
