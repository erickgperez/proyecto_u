<?php

namespace App\Services;

use App\Models\Academico\Estudiante;
use App\Models\Persona;
use Illuminate\Support\Str;

class EstudianteService
{
    public function generateCarnet(Persona $persona, $anio_ingreso, $codigo_sede)
    {
        $carnet = Str::upper(Str::substr($persona->primer_apellido, 0, 1)) .
            Str::upper(Str::substr($persona->segundo_apellido ?? $persona->primer_apellido, 0, 1)) .
            $codigo_sede .
            Str::substr($anio_ingreso, -2);

        //buscar el último correlativo, últimos 3 caracteres
        $correlativo = Estudiante::where('carnet', 'like', $carnet . '%')->max('carnet');
        $nextCorrelativo = 1;
        if ($correlativo) {
            $lastThreeDigits = (int) Str::substr($correlativo, -3);
            $nextCorrelativo = $lastThreeDigits + 1;
        }
        $carnet .= str_pad($nextCorrelativo, 3, '0', STR_PAD_LEFT);

        return $carnet;
    }
}
