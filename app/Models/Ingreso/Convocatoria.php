<?php

namespace App\Models\Ingreso;

use Illuminate\Database\Eloquent\Model;

class Convocatoria extends Model
{
    //
    protected $table = "ingreso.convocatoria";

    protected $fillable = [
        'nombre',
        'descripcion',
        'fecha',
        'cuerpo_mensaje',
        'afiche'
    ];
}
