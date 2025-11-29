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
        // Preparar los estados
        $usoEstado = UsoEstado::where('codigo', 'ESTUDIANTE_CARRERA_SEDE')->first();
        $estadoActivo = $usoEstado->estados()->where('codigo', 'ESTUDIANTE')->first();

        $usoEstadoExpediente = UsoEstado::where('codigo', 'EXPEDIENTE')->first();
        $estadoAP = $usoEstadoExpediente->estados()->where('codigo', 'AP')->first();
        $estadoEC = $usoEstadoExpediente->estados()->where('codigo', 'EC')->first();
        $estadoRP = $usoEstadoExpediente->estados()->where('codigo', 'RP')->first();

        // Recuperar el estudiante con su carrera activa y su expediente
        $estudiante = Estudiante::with([
            'carreraSede' => function ($query) use ($estadoActivo) {
                $query->wherePivot('estado_id', $estadoActivo->id);
            },
            'expediente',
        ])
            ->where('uuid', $uuid)->first();

        //Recuperar el semestre que tenga evento de inscripción activo a la fecha actual
        $semestre = Semestre::whereHas('calendario', function ($query) {
            $tipoEvento = TipoEvento::where('codigo', 'INSCRIPCION')->first();
            $query->whereHas('eventos', function ($query) use ($tipoEvento) {
                $query->where('tipo_evento_id', $tipoEvento->id)->where('fecha_inicio', '<=', now())->where('fecha_fin', '>=', now());
            });
        })->first();

        // Recuperar la oferta académica para la carrera del estudiante
        $carreraSede = $estudiante->carreraSede->first();
        $ofertaAcademica = Oferta::with([
            'imparte',
            'carreraUnidadAcademica' => ['requisitos', 'unidadAcademica', 'carrera']
        ])
            ->where('semestre_id', $semestre->id)
            ->whereHas('imparte', function ($query) use ($carreraSede) {
                $query->where('carrera_sede_id', $carreraSede->id)->where('cupo', '>', 0)->where('ofertada', true);
            })
            ->join('plan_estudio.carrera_unidad_academica as cua', 'oferta.carrera_unidad_academica_id', '=', 'cua.id')
            ->join('plan_estudio.unidad_academica as ua', 'cua.unidad_academica_id', '=', 'ua.id')
            ->orderBy('cua.semestre', 'asc')
            ->orderBy('ua.nombre', 'asc')
            ->select('oferta.*')
            ->get();

        // Clasificar el expediente del estudiante por estados
        $expedienteAP = [];
        $expedienteRP = [];
        $expedienteEC = [];
        foreach ($estudiante->expediente as $expediente) {
            if ($expediente->estado_id == $estadoAP->id) {
                $expedienteAP[] = $expediente->carreraUnidadAcademica->id;
            }
            if ($expediente->estado_id == $estadoRP->id) {
                $expedienteRP[] = $expediente->carreraUnidadAcademica->id;
            }
            if ($expediente->estado_id == $estadoEC->id) {
                $expedienteEC[] = $expediente->carreraUnidadAcademica->id;
            }
        }

        // Filtrar la oferta académica ...
        // ... quitar las que ya aprobó o están en curso
        $cargaAcademica = $ofertaAcademica->filter(function ($oferta) use ($expedienteAP, $expedienteEC) {
            return !in_array($oferta->carreraUnidadAcademica->id, $expedienteAP)
                && !in_array($oferta->carreraUnidadAcademica->id, $expedienteEC);
        });

        // ... quitar las que no tiene ganados los requisitos
        $cargaAcademica = $cargaAcademica->filter(function ($oferta) use ($expedienteAP) {
            return $oferta->carreraUnidadAcademica->requisitos->filter(function ($requisito) use ($expedienteAP) {
                return in_array($requisito->unidad_academica_requisito_id, $expedienteAP);
            })->count() == $oferta->carreraUnidadAcademica->requisitos->count();
        });

        // ... contar las veces que aparecen reprobadas, para calcular la matricula
        $frecuenciasRP = array_count_values($expedienteRP);
        $cargaAcademica = $cargaAcademica->map(function ($oferta) use ($frecuenciasRP) {
            $oferta->matricula = ($frecuenciasRP[$oferta->carreraUnidadAcademica->id] ?? 0) + 1;
            return $oferta;
        });


        return Inertia::render('academico/Estudiante/Inscripcion', [
            'semestre' => $semestre,
            'cargaAcademica' => $cargaAcademica,
            'estudiante' => $estudiante,
            'carreraSede' => $carreraSede,
        ]);
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
