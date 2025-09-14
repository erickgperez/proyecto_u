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
}
