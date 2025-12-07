<?php

namespace App\Http\Controllers\Reportes;

use App\Http\Controllers\Controller;
use App\Models\Academico\Sede;
use App\Models\Ingreso\Convocatoria;
use App\Models\PlanEstudio\Carrera;
use Illuminate\Http\Request;

class ReporteController extends Controller
{

    public function parametros1(Request $request)
    {

        return response()->json([
            'convocatorias' => Convocatoria::orderBy('anio_ingreso', 'desc')->orderBy('nombre', 'desc')->get(),
            'sedes' => Sede::orderBy('nombre', 'asc')->get(),
            'carreras' => Carrera::orderBy('tipo_carrera_id', 'asc')->orderBy('codigo', 'asc')->orderBy('nombre', 'asc')->get(),
        ]);
    }
}
