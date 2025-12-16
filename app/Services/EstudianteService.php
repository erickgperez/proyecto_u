<?php

namespace App\Services;

use App\Models\Academico\Administrativo;
use App\Models\Academico\Docente;
use App\Models\Academico\Estudiante;
use App\Models\Academico\Oferta;
use App\Models\Academico\UsoEstado;
use App\Models\Persona;
use Illuminate\Support\Str;

class EstudianteService
{
    public function generateCarnet(Persona $persona, $anio_ingreso, $codigo_sede, $tipo = 'estudiante')
    {
        $carnet = Str::upper(Str::substr($persona->primer_apellido, 0, 1)) .
            Str::upper(Str::substr($persona->segundo_apellido ?? $persona->primer_apellido, 0, 1)) .
            $codigo_sede .
            Str::substr($anio_ingreso, -2);

        //buscar el último correlativo, últimos 3 caracteres
        if ($tipo == 'estudiante') {
            $correlativo = Estudiante::where('carnet', 'like', $carnet . '%')->max('carnet');
        } elseif ($tipo == 'docente') {
            $correlativo = Docente::where('codigo', 'like', $carnet . '%')->max('codigo');
        } elseif ($tipo == 'administrativo') {
            $correlativo = Administrativo::where('codigo', 'like', $carnet . '%')->max('codigo');
        }
        $nextCorrelativo = 1;
        if ($correlativo) {
            $lastThreeDigits = (int) Str::substr($correlativo, -3);
            $nextCorrelativo = $lastThreeDigits + 1;
        }
        $carnet .= str_pad($nextCorrelativo, 3, '0', STR_PAD_LEFT);

        return $carnet;
    }

    public function getCargaAcademica($estudiante, $semestre, $carreraSede)
    {
        $usoEstadoExpediente = UsoEstado::where('codigo', 'EXPEDIENTE')->first();
        $estadoAP = $usoEstadoExpediente->estados()->where('codigo', 'AP')->first();
        $estadoEC = $usoEstadoExpediente->estados()->where('codigo', 'EC')->first();
        $estadoRP = $usoEstadoExpediente->estados()->where('codigo', 'RP')->first();
        $estadoRT = $usoEstadoExpediente->estados()->where('codigo', 'RT')->first();

        $cargaAcademica = [];
        if ($semestre) {
            // Recuperar la oferta académica para la carrera del estudiante
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
                //Retiradas en el mismo semestre de inscripción
                if ($expediente->estado_id == $estadoRT->id && $expediente->semestre_id == $semestre->id) {
                    $expedienteRT[] = $expediente->carreraUnidadAcademica->id;
                }
            }

            // Filtrar la oferta académica ...
            // ... quitar las que ya aprobó o están en curso, o retiradas en el mismo semestre de inscripción
            $cargaAcademica = $ofertaAcademica->filter(function ($oferta) use ($expedienteAP, $expedienteEC, $expedienteRT) {
                return !in_array($oferta->carreraUnidadAcademica->id, $expedienteAP)
                    && !in_array($oferta->carreraUnidadAcademica->id, $expedienteEC)
                    && !in_array($oferta->carreraUnidadAcademica->id, $expedienteRT);
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
        }
        // Devolver la carga académica, se utilizar --values()-- para que vuelva a generar las llaves del arreglo
        return empty($cargaAcademica) ? [] : $cargaAcademica->values();
    }
}
