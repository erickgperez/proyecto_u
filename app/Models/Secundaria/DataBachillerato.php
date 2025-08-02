<?php

namespace App\Models\Secundaria;

use Illuminate\Database\Eloquent\Model;

class DataBachillerato extends Model
{
    //
    protected $table = "secundaria.data_bachillerato";
    public $timestamps = false;
    protected $primaryKey = 'nie';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nie',
        'correo',
        'primer_nombre',
        'segundo_nombre',
        'tercer_nombre',
        'primer_apellido',
        'segundo_apellido',
        'tercer_apellido',
        'sexo',
        'edad',
        'sector',
        'grado',
        'codigo_ce',
        'nombre_centro_educativo',
        'direccion',
        'codigo_depto',
        'departamento',
        'codigo_distrito',
        'distrito',
        'opcion_bachillerato',
        'nota_promocion',
    ];
}
