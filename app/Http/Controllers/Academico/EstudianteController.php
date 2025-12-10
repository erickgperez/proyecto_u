<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Models\Academico\Estudiante;
use App\Models\Academico\Semestre;
use App\Models\Academico\UsoEstado;
use App\Models\Documento\TipoDocumento;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Persona;
use App\Models\Sexo;
use App\Models\TipoEvento;
use App\Services\EstudianteService;
use App\Services\DistritoService;

class EstudianteController extends Controller
{

    public function __construct(private EstudianteService $estudianteService, private DistritoService $distritoService) {}

    /**
     *
     */
    public function inscripcion($uuid, $id): Response
    {
        // Preparar los estados
        $usoEstado = UsoEstado::where('codigo', 'ESTUDIANTE_CARRERA_SEDE')->first();
        $estadoActivo = $usoEstado->estados()->where('codigo', 'ESTUDIANTE')->first();

        // Recuperar el estudiante con su carrera activa y su expediente
        $estudiante = Estudiante::with([
            'carreraSede' => function ($query) use ($estadoActivo) {
                $query->wherePivot('estado_id', $estadoActivo->id);
            },
            'expediente' => ['carreraUnidadAcademica' => ['unidadAcademica'], 'estado'],
        ])
            ->where('uuid', $uuid)->first();

        $carreraSede = $estudiante->carreraSede->first();

        //Recuperar el semestre que tenga evento de inscripción activo a la fecha actual
        $semestre = Semestre::whereHas('calendario', function ($query) {
            $tipoEvento = TipoEvento::where('codigo', 'INSCRIPCION')->first();
            $query->whereHas('eventos', function ($query) use ($tipoEvento) {
                $query->where('tipo_evento_id', $tipoEvento->id)->where('fecha_inicio', '<=', now())->where('fecha_fin', '>=', now());
            });
        })->first();

        $cargaAcademica = $this->estudianteService->getCargaAcademica($estudiante, $semestre, $carreraSede);

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
            ->with([
                'carreraSede' => function ($query) use ($estadoActivo) {
                    $query->wherePivot('estado_id', $estadoActivo->id);
                },
                'expediente' => function ($query) {
                    $query
                        ->select('expediente.*', 'unidad_academica.nombre', 'unidad_academica.codigo', 'carrera_unidad_academica.semestre')
                        ->join('plan_estudio.carrera_unidad_academica as carrera_unidad_academica', 'expediente.carrera_unidad_academica_id', '=', 'carrera_unidad_academica.id')
                        ->join('plan_estudio.unidad_academica as unidad_academica', 'carrera_unidad_academica.unidad_academica_id', '=', 'unidad_academica.id')
                        ->orderBy('unidad_academica.nombre', 'asc')
                        ->with([
                            'estado',
                            'inscritosPivot' => ['calificacion' => function ($query) {
                                $query
                                    ->select('calificacion.*', 'evaluacion.codigo', 'evaluacion.porcentaje', 'evaluacion.fecha')
                                    ->join('academico.evaluacion as evaluacion', 'calificacion.evaluacion_id', '=', 'evaluacion.id')
                                    ->orderBy('evaluacion.fecha', 'desc');
                            }]
                        ]);
                }
            ])
            ->first();
        return response()->json(['status' => 'ok', 'estudiante' => $estudiante]);
    }

    public function carreras($uuid)
    {
        $estudiante = Estudiante::where('uuid', $uuid)->first();
        $carreras = $estudiante->carreraSede()->get();
        return response()->json(['status' => 'ok', 'carreras' => $carreras]);
    }

    public function perfil($uuid)
    {
        $estudiante = Estudiante::where('uuid', $uuid)->first();

        $sexos = Sexo::all();
        $tiposDocumento = TipoDocumento::with('roles')->orderBy('codigo')->get();
        $tipos = [];
        foreach ($tiposDocumento as $td) {
            foreach ($td->roles as $rol) {
                if ($rol->guard_name == 'web' && $rol->name == 'estudiante') {
                    $tipos[] = $td;
                    break;
                }
            }
        }
        $distritosTree = $this->distritoService->distritosLikeTree();
        $persona = $estudiante->persona()
            ->with([
                'datosContacto',
                'sexo',
                'usuarios' => function ($query) {
                    $query->join('model_has_roles as roles', 'users.id', '=', 'roles.model_id')
                        ->join('roles as rol', 'roles.role_id', '=', 'rol.id')
                        ->where('roles.model_type', 'App\Models\User')
                        ->where('rol.name', 'estudiante');
                }
            ])
            ->first();

        return Inertia::render('academico/Estudiante/Perfil', [
            'estudiante' => $estudiante,
            'persona' => $persona,
            'sexos' => $sexos,
            'tiposDocumento' => $tipos,
            'distritosTree' => $distritosTree,
        ]);
    }
}
