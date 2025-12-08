<?php

namespace App\Http\Controllers\Reportes;

use App\Http\Controllers\Controller;
use App\Models\Academico\Expediente;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ReporteEstudiantesController extends Controller
{


    public function inscritos(Request $request): Response
    {

        return Inertia::render('reportes/academico/Inscritos');
    }

    public function getDataInscritos(Request $request)
    {
        $semestreId = $request->get('semestre');
        $sedeSeleccionIds = $request->get('sedeSeleccion');
        $carreraSeleccionIds = $request->get('carreraSeleccion');
        $unidadAcademicaSeleccionIds = $request->get('unidadAcademicaSeleccion');

        $inscritos = Expediente::with([
            'estudiante' => [
                'persona' => [
                    'sexo'
                ],
                'carreraSede' => [
                    'carrera',
                    'sede',
                ],
            ],
            'estado',
            'carreraUnidadAcademica' => [
                'carrera',
                'unidadAcademica',
            ],
        ])
            ->whereHas('carreraUnidadAcademica', function ($query) use ($sedeSeleccionIds, $carreraSeleccionIds, $unidadAcademicaSeleccionIds) {
                if (!empty($sedeSeleccionIds)) {
                    $query->whereHas('carrera', function ($query) use ($sedeSeleccionIds) {
                        $query->join('academico.carrera_sede as carrera_sede', 'carrera_sede.carrera_id', 'carrera.id')
                            ->whereIn('carrera_sede.sede_id', $sedeSeleccionIds);
                    });
                }
                if (!empty($carreraSeleccionIds)) {
                    $query->whereIn('carrera_id', $carreraSeleccionIds);
                }
                if (!empty($unidadAcademicaSeleccionIds)) {
                    $query->whereIn('unidad_academica_id', $unidadAcademicaSeleccionIds);
                }
            })
            ->where('semestre_id', $semestreId)
            ->get();


        return response()->json([
            'items' => $inscritos,
        ]);
    }
}
