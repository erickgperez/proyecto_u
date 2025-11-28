<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Models\Academico\Estudiante;
use App\Models\Academico\Oferta;
use App\Models\Academico\Semestre;
use App\Models\Academico\UsoEstado;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Persona;
use App\Models\TipoEvento;


class EstudianteController extends Controller
{

    /**
     *
     */
    public function inscripcion($uuid, $id): Response
    {
        $usoEstado = UsoEstado::where('codigo', 'ESTUDIANTE_CARRERA_SEDE')->first();
        $estadoActivo = $usoEstado->estados()->where('codigo', 'ESTUDIANTE')->first();
        $estudiante = Estudiante::with(['carreraSede' => function ($query) use ($estadoActivo) {
            $query->wherePivot('estado_id', $estadoActivo->id);
        }])
            ->where('uuid', $uuid)->first();
        //Recuperar el semestre que tenga evento de inscripción activo a la fecha actual
        $semestre = Semestre::whereHas('calendario', function ($query) {
            $tipoEvento = TipoEvento::where('codigo', 'INSCRIPCION')->first();
            $query->whereHas('eventos', function ($query) use ($tipoEvento) {
                $query->where('tipo_evento_id', $tipoEvento->id)->where('fecha_inicio', '<=', now())->where('fecha_fin', '>=', now());
            });
        })->first();

        $carreraSede = $estudiante->carreraSede->first();
        $ofertaAcademica = Oferta::with('imparte')
            ->where('semestre_id', $semestre->id)
            ->whereHas('imparte', function ($query) use ($carreraSede) {
                $query->where('carrera_sede_id', $carreraSede->id);
            })
            ->get();

        dd($ofertaAcademica);
        return Inertia::render('academico/Estudiante/Inscripcion', ['semestre' => $semestre, 'oferta' => $oferta]);
    }



    public function getEstudianteData($uuid)
    {
        $persona = Persona::where('uuid', $uuid)->first();
        $usoEstado = UsoEstado::where('codigo', 'ESTUDIANTE_CARRERA_SEDE')->first();
        $estadoActivo = $usoEstado->estados()->where('codigo', 'ESTUDIANTE')->first();
        $estudiante = $persona->estudiante()
            ->with(['carreraSede' => function ($query) use ($estadoActivo) {
                $query->wherePivot('estado_id', $estadoActivo->id);
            }])
            ->first();
        return response()->json(['status' => 'ok', 'estudiante' => $estudiante]);
    }
}
