<?php

namespace App\Http\Controllers\Reportes;

use App\Http\Controllers\Controller;
use App\Models\Academico\Imparte;
use App\Models\Academico\Oferta;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ReporteAsignaturaController extends Controller
{


    public function inscritosTitular(string $uuid): Response
    {

        $oferta = Oferta::where('uuid', $uuid)->first();
        $semestre = $oferta->semestre;
        $carrera[] = $oferta->carreraUnidadAcademica->carrera;
        $unidadAcademica[] = $oferta->carreraUnidadAcademica->unidadAcademica;

        return Inertia::render('reportes/academico/Asignatura', [
            'semestre' => $semestre,
            'carrera' => $carrera,
            'unidadAcademica' => $unidadAcademica,
            'sede' => [],
        ]);
    }

    public function inscritosAsociado(string $uuid): Response
    {

        $imparte = Imparte::where('uuid', $uuid)->first();
        $semestre = $imparte->oferta->semestre;
        $carrera[] = $imparte->oferta->carreraUnidadAcademica->carrera;
        $unidadAcademica[] = $imparte->oferta->carreraUnidadAcademica->unidadAcademica;
        $sede[] = $imparte->carreraSede->sede;

        return Inertia::render('reportes/academico/Asignatura', [
            'semestre' => $semestre,
            'carrera' => $carrera,
            'unidadAcademica' => $unidadAcademica,
            'sede' => $sede,
        ]);
    }
}
