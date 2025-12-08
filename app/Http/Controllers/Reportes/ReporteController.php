<?php

namespace App\Http\Controllers\Reportes;

use App\Http\Controllers\Controller;
use App\Models\Academico\Sede;
use App\Models\Academico\Semestre;
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

    public function parametrosSemestre(Request $request)
    {

        return response()->json([
            'semestres' => Semestre::orderBy('anio', 'desc')->orderBy('codigo', 'desc')->get(),
            'sedes' => Sede::orderBy('nombre', 'asc')->get(),
            'carreras' => Carrera::with([
                'unidadesAcademicas' => function ($query) {
                    $query
                        ->select('plan_estudio.carrera_unidad_academica.*')
                        ->join('plan_estudio.unidad_academica', 'plan_estudio.carrera_unidad_academica.unidad_academica_id', '=', 'plan_estudio.unidad_academica.id')
                        ->orderBy('semestre', 'asc')
                        ->orderBy('plan_estudio.unidad_academica.nombre', 'asc')
                        ->with('unidadAcademica');
                }
            ])
                ->orderBy('tipo_carrera_id', 'asc')->orderBy('codigo', 'asc')->orderBy('nombre', 'asc')->get(),

        ]);
    }
}
