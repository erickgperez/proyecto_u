<?php

namespace App\Models\PlanEstudio;

use Illuminate\Database\Eloquent\Model;

class Grado extends Model
{

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
