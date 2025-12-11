<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Models\Academico\CarreraSede;
use App\Models\Academico\Estudiante;
use App\Models\Academico\Expediente;
use App\Models\Academico\Oferta;
use App\Models\Academico\Semestre;
use App\Models\Academico\UsoEstado;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Models\Academico\TipoCurso;
use App\Models\PlanEstudio\CarreraUnidadAcademica;
use App\Models\TipoEvento;
use App\Services\EstudianteService;

class ExpedienteController extends Controller
{

    public function __construct(private EstudianteService $estudianteService) {}

    public function inscripcionSave($uuid, $uuidSemestre, $id, Request $request)
    {
        $estudiante = Estudiante::where('uuid', $uuid)->first();

        $carreraSede = CarreraSede::find($id);

        $semestre = Semestre::where('uuid', $uuidSemestre)->first();

        $usoEstadoExpediente = UsoEstado::where('codigo', 'EXPEDIENTE')->first();
        $estadoEC = $usoEstadoExpediente->estados()->where('codigo', 'EC')->first();
        $tipoCurso = TipoCurso::where('codigo', 'NM')->first();


        // Recuperar la carga academica seleccionada
        $cargaSeleccionada = $request->carga_inscrita;

        //Guardar en el expediente
        foreach ($cargaSeleccionada as $carga) {
            $expediente = new Expediente();
            $expediente->estudiante_id = $estudiante->id;
            $expediente->carrera_unidad_academica_id = $carga['carrera_unidad_academica_id'];
            $expediente->matricula = $carga['matricula'];
            $expediente->semestre_id = $semestre->id;
            $expediente->tipo_curso_id = $tipoCurso->id;
            $expediente->estado_id = $estadoEC->id;

            $expediente->save();

            $oferta = Oferta::where('carrera_unidad_academica_id', $carga['carrera_unidad_academica_id'])
                ->where('semestre_id', $semestre->id)
                ->first();
            $imparte = $oferta->imparte()
                ->where('carrera_sede_id', $carreraSede->id)
                ->where('oferta_id', $oferta->id)
                ->first();
            $expediente->inscritos()->attach($imparte->id);
        }

        //Calcular la nueva carga académica
        $cargaAcademica = $this->estudianteService->getCargaAcademica($estudiante, $semestre, $carreraSede);
        $estudiante = Estudiante::with(['expediente' => ['carreraUnidadAcademica' => ['unidadAcademica'], 'estado']])
            ->find($estudiante->id);

        return response()->json(['status' => 'ok', 'cargaAcademica' => $cargaAcademica, 'estudiante' => $estudiante]);
    }

    public function expediente($uuid, $id): Response
    {
        $carreraSede = CarreraSede::find($id);
        $estudiante = Estudiante::where('uuid', $uuid)->first();

        $data = $this->expedienteData($estudiante, $carreraSede);


        return Inertia::render('academico/Estudiante/Expediente', [
            'expediente' => $data['expediente'],
            'estudiante' => $estudiante,
            'carreraSede' => $carreraSede,
            'mallaCurricular' => $data['mallaCurricular'],
        ]);
    }

    protected function expedienteData($estudiante, $carreraSede)
    {
        //Recuperar el expediente del estudiante en la carrera seleccionada
        $expediente = $estudiante->expediente()
            ->select('expediente.*')
            ->with([
                'semestre',
                'carreraUnidadAcademica' => ['unidadAcademica'],
                'estado'
            ])
            ->join('plan_estudio.carrera_unidad_academica as cua', 'expediente.carrera_unidad_academica_id', '=', 'cua.id')
            ->join('plan_estudio.unidad_academica as ua', 'cua.unidad_academica_id', '=', 'ua.id')
            ->join('academico.semestre as s', 'expediente.semestre_id', '=', 's.id')
            ->where('cua.carrera_id', $carreraSede->carrera_id)
            ->orderBy('s.anio', 'desc')
            ->orderBy('s.codigo', 'desc')
            ->orderBy('ua.nombre', 'asc')
            ->get();

        $mallaCurricular = CarreraUnidadAcademica::with([
            'unidadAcademica',
            'requisitos' => ['unidadAcademica']
        ])
            ->select('carrera_unidad_academica.*')
            ->join('plan_estudio.unidad_academica as ua', 'carrera_unidad_academica.unidad_academica_id', '=', 'ua.id')
            ->where('carrera_id', $carreraSede->carrera_id)
            ->orderBy('carrera_unidad_academica.semestre', 'asc')
            ->orderBy('ua.nombre', 'asc')
            ->get();

        return [
            'expediente' => $expediente,
            'mallaCurricular' => $mallaCurricular,
        ];
    }

    public function expedienteJson($uuid, $id)
    {
        $carreraSede = CarreraSede::find($id);
        $estudiante = Estudiante::where('uuid', $uuid)->first();

        $data = $this->expedienteData($estudiante, $carreraSede);

        return response()->json($data);
    }

    public function retiro($uuid)
    {
        $expediente = Expediente::where('uuid', $uuid)->first();

        $usoEstadoExpediente = UsoEstado::where('codigo', 'EXPEDIENTE')->first();
        $estadoRT = $usoEstadoExpediente->estados()->where('codigo', 'RT')->first();

        $expediente->estado_id = $estadoRT->id;
        $expediente->save();
        return response()->json(['status' => 'ok', 'expediente' => $expediente]);
    }

    public function semestreRetiro($id)
    {
        //Recuperar el semestre y verificar que tenga evento de RETIRO_ASIGNATURAS activo a la fecha actual
        $semestre = Semestre::where('id', $id)->whereHas('calendario', function ($query) {
            $tipoEvento = TipoEvento::where('codigo', 'RETIRO_ASIGNATURAS')->first();
            $query->whereHas('eventos', function ($query) use ($tipoEvento) {
                $query->where('tipo_evento_id', $tipoEvento->id)->where('fecha_inicio', '<=', now())->where('fecha_fin', '>=', now());
            });
        })->first();

        return response()->json(['status' => 'ok', 'semestre' => $semestre]);
    }
}
