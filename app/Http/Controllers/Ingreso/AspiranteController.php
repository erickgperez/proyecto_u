<?php

namespace App\Http\Controllers\Ingreso;

use App\Http\Controllers\Controller;
use App\Models\Academico\Estado;
use App\Models\Academico\Estudiante;
use App\Models\Academico\EstudianteCarreraSede;
use App\Models\Ingreso\Convocatoria;
use App\Models\Ingreso\ConvocatoriaAspirante;
use App\Models\Workflow\Solicitud;
use App\Services\SolicitudService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class AspiranteController extends Controller
{

    protected $solicitudService;

    public function __construct(SolicitudService $solicitudService)
    {
        $this->solicitudService = $solicitudService;
    }

    public function save(Request $request) {}

    public function delete($id) {}

    public function datosPersonalesRestringido()
    {
        $user = Auth::user();
        $persona = $user->personas()->first();

        return response()->json(['status' => 'ok', 'message' => '', 'persona' => $persona]);
    }

    public function seleccion(Request $request): Response
    {

        $convocatorias = Convocatoria::with([
            'carrerasSedes' => [
                'carrera',
                'sede',
            ],
            'configuracion',
            'solicitud' => ['estado', 'etapa', 'historial' => ['etapa', 'estado', 'creator']]
        ])->where('activa', true)
            ->orderBy('nombre', 'asc')
            ->get();

        //Revisar las convocatorias que ya se cumplió la fecha de fin de recepción de solicitudes
        // para pasarla a etapa de SELECCION_ASPIRANTES (si está en etapa de INVITACIONES)
        $convocatorias_ = [];
        $today = new \DateTime();
        foreach ($convocatorias as $c) {
            if ($c->configuracion != null) {
                $solicitud = $c->solicitud;
                $fecha_fin_sol = new \DateTime($c->configuracion->fecha_fin_recepcion_solicitudes);
                if ($today > $fecha_fin_sol && $solicitud->etapa->codigo === 'INVITACIONES') {
                    $solicitud->pasarSiguienteEtapa();
                    $solicitud->save();
                    $solicitud->guardarHistorial();
                }

                //Verificar si ya pasó la fecha de publicacion de resultados
                $fecha_pub = new \DateTime($c->configuracion->fecha_publicacion_resultados);
                if ($today >  $fecha_pub && $solicitud->etapa->codigo === 'SELECCION_ASPIRANTES') {
                    $solicitud->pasarSiguienteEtapa();
                    $solicitud->save();
                    $solicitud->guardarHistorial();
                }
                $convocatorias_[] = $c;
            }
        }

        return Inertia::render('ingreso/Seleccion', ['convocatorias' => $convocatorias_]);
    }

    public function aplicarSeleccion(int $id, $seleccionado = true, $idSolicitudCarreraSede = null)
    {
        $solicitud = Solicitud::find($id);
        $aspirante = $solicitud->solicitante;
        $convocatoria = $solicitud->modelo;

        $convocatoriaAspirante = ConvocatoriaAspirante::whereBelongsTo($aspirante)
            ->whereBelongsTo($convocatoria)
            ->first();
        $convocatoriaAspirante->seleccionado = $seleccionado;
        $convocatoriaAspirante->solicitud_carrera_sede_id = $idSolicitudCarreraSede;

        $convocatoriaAspirante->save();

        return response()->json(['status' => 'ok', 'message' => '']);
    }

    public function aceptarSeleccion(int $id)
    {
        $solicitud = Solicitud::with([
            'solicitante',
            'etapa',
            'estado',
            'modelo'
        ])->where('id', $id)->first();


        $convocatoriaAspirante = ConvocatoriaAspirante::with('solicitudCarreraSede')
            ->whereBelongsTo($solicitud->solicitante)
            ->whereBelongsTo($solicitud->modelo)
            ->first();

        //Crear el registro de estudiante
        $estudiante = new Estudiante();
        $estudiante->persona_id = $convocatoriaAspirante->aspirante->persona_id;
        $estudiante->save();

        //Agregar la carrera
        $estado = Estado::where('codigo', 'ESTUDIANTE')->first();
        $estudianteCarreraSede = new EstudianteCarreraSede();
        $estudianteCarreraSede->estudiante()->associate($estudiante);
        $estudianteCarreraSede->carreraSede()->associate($convocatoriaAspirante->solicitudCarreraSede->carreraSede);
        $estudianteCarreraSede->fecha_inicio = new \DateTime();
        $estudianteCarreraSede->estado()->associate($estado);
        $estudianteCarreraSede->save();

        //  Cambiarle rol al usuario
        $usuario = $convocatoriaAspirante->aspirante->persona->usuarios()->role(['aspirante'])->first();
        $usuario->assignRole('estudiante');
        $usuario->removeRole('aspirante');
        $usuario->save();

        $solicitud->pasarSiguienteEtapa();
        //$estadoSolicitud = WorkflowEstado::where('codigo', 'APROBADA');
        $solicitud->save();

        //Guardar en el historial de solicitud
        $solicitud->guardarHistorial();

        return redirect()->intended(route('home', absolute: false));
    }
}
