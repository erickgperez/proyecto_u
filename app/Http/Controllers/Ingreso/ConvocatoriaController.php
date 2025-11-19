<?php

namespace App\Http\Controllers\Ingreso;

use App\Http\Controllers\Controller;
use App\Mail\CandidatoInvitado;
use App\Mail\NotificacionSeleccion;
use App\Models\Academico\CarreraSede;
use App\Models\Academico\Sede;
use App\Models\Calendarizacion;
use App\Models\Ingreso\Convocatoria;
use App\Models\Ingreso\ConvocatoriaAspirante;
use App\Models\Ingreso\ConvocatoriaConfiguracion;
use App\Models\Secundaria\DataBachillerato;
use App\Models\Secundaria\PruebaBachillerato;
use App\Models\TipoCalendarizacion;
use App\Models\Workflow\Estado;
use App\Models\Workflow\Flujo;
use App\Models\Workflow\Solicitud;
use App\Models\Workflow\TipoFlujo;
use App\Services\CarreraSedeService;
use App\Services\SolicitudService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class ConvocatoriaController extends Controller
{
    protected $solicitudService;
    protected $carreraSedeService;

    public function __construct(SolicitudService $solicitudService, CarreraSedeService $carreraSedeService)
    {
        $this->solicitudService = $solicitudService;
        $this->carreraSedeService = $carreraSedeService;
    }

    /**
     *
     */
    public function index(Request $request): Response
    {

        $convocatorias = $this->convocatoriaBase()->orderBy('created_at', 'DESC')->get();

        $sedesCarreras = $this->carreraSedeService->getSedesCarreras();
        $tipoFlujo = TipoFlujo::where('codigo', 'INGRESO')->first();
        $flujos = Flujo::where('activo', true)->where('tipo_flujo_id', $tipoFlujo->id)->get();
        $pruebasBachillerato = PruebaBachillerato::orderBy('codigo', 'ASC')->get();

        //Revisar las convocatorias que ya se cumplió la fecha de fin de recepción de solicitudes
        // para pasarla a etapa de SELECCION_ASPIRANTES (si está en etapa de INVITACIONES)
        $convocatorias_ = [];
        $today = new \DateTime();

        foreach ($convocatorias as $c) {
            if ($c->activa && $c->configuracion != null) {
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
            }
            $convocatorias_[] = $c;
        }

        return Inertia::render('ingreso/Convocatoria', [
            'items'         => $convocatorias_,
            'sedesCarreras' => $sedesCarreras,
            'flujos'        => $flujos,
            'pruebasBachillerato' => $pruebasBachillerato,
        ]);
    }

    public function notificarSeleccionParametros(): Response
    {
        $convocatorias = Convocatoria::select('id', 'nombre', 'descripcion')
            ->where('activa', true)
            ->orderBy('nombre', 'asc')
            ->withCount([
                'aspirantes as aspirantes_seleccionados_notificados' => function (Builder $query) {
                    $query->where('seleccionado', true)
                        ->whereNotNull('fecha_notificacion_seleccion');
                },
                'aspirantes as aspirantes_seleccionados_pendientes_notificacion' => function (Builder $query) {
                    $query->where('seleccionado', true)
                        ->whereNull('fecha_notificacion_seleccion');
                },

            ])
            ->get();
        return Inertia::render('ingreso/NotificarSeleccion', [
            'convocatorias'     => $convocatorias,
        ]);
    }


    public function notificarSeleccion(Request $request)
    {
        // Aunque se ha validado del lado del cliente, validar aquí también
        $request->validate([
            'tipoNotificacion'  => 'required|string',
            'convocatoria' => ['required', 'integer', Rule::exists('pgsql.ingreso.convocatoria', 'id')],
        ]);

        $convocatoria  = Convocatoria::find($request->get('convocatoria'));
        $tipoNotificacion = $request->get('tipoNotificacion');

        if ($tipoNotificacion === 'reenviar') {
            $aspirantes = $convocatoria->aspirantes()->wherePivot('seleccionado', true)->get();
        } else {
            $aspirantes = $convocatoria->aspirantes()->wherePivot('seleccionado', true)->wherePivotNull('fecha_notificacion_seleccion')->get();
        }

        foreach ($aspirantes as $a) {
            $cc = [];
            $persona = $a->persona;
            $correoPrincipal = $persona->datosContacto->email_principal;
            $correo2 = $persona->datosContacto->email_alternativo;
            if ($correo2) {
                $cc[] = $correo2;
            }
            $usuario = $persona->usuarios()->role(['aspirante'])->first();
            if ($usuario->email != $correoPrincipal && $usuario->email != $correo2) {
                $cc[] = $usuario->email;
            }

            $convocatoriaAspirante = ConvocatoriaAspirante::where('aspirante_id', $a->id)
                ->where('convocatoria_id', $convocatoria->id)->first();

            Mail::to($correoPrincipal)
                ->cc($cc)
                ->queue(
                    new NotificacionSeleccion($convocatoriaAspirante)
                );
            $convocatoriaAspirante->fecha_notificacion_seleccion = new \DateTime();
            $convocatoriaAspirante->save();
        }

        return response()->json(['status' => 'ok', 'message' => '', 'enviados' => count($aspirantes)]);
    }

    public function save(Request $request)
    {
        // Aunque se ha validado del lado del cliente, validar aquí también
        $request->validate([
            'nombre'  => 'required|string|max:100',
            'activa' => 'required',
            'descripcion' => 'nullable|string|max:255',
            'cuerpo_mensaje' => 'nullable|string',
            'afiche_file' => 'nullable|file|mimes:pdf',
            'carrerasSedes' => 'nullable',
            'flujo_id' => ['required', 'integer', Rule::exists('pgsql.workflow.flujo', 'id')],
        ]);

        if ($request->get('id') === null) {
            $convocatoria = new Convocatoria();
            //Crear el calendario de actividades
            $tipoCalendario = TipoCalendarizacion::where('codigo', 'CONVOCATORIA')->first();
            $calendarizacion = new Calendarizacion();
            $calendarizacion->codigo = substr($request->get('nombre'), 0, 50); //llevará el mismo nombre que la convocatoria
            $calendarizacion->tipo()->associate($tipoCalendario);
            $calendarizacion->save();

            //Asociar el calendario a la convocatoria
            $convocatoria->calendario()->associate($calendarizacion);
        } else {
            $convocatoria = Convocatoria::find($request->get('id'));
        }

        $convocatoria->nombre = $request->get('nombre');
        $convocatoria->descripcion = $request->get('descripcion');
        $convocatoria->flujo_id = $request->get('flujo_id');
        $convocatoria->activa = ($request->get('activa') === 'true');
        $convocatoria->cuerpo_mensaje = $request->get('cuerpo_mensaje');

        if ($request->hasFile('afiche_file')) {
            $file = $request->file('afiche_file');

            if ($request->get('id') != null) {
                //Verificar si ya tenía un afiche cargado, en ese caso borrarlo para subir el nuevo
                $filePath = $convocatoria->afiche;
                if (Storage::exists($filePath)) {
                    Storage::delete($filePath);
                }
            }

            $path = $file->store('documents/convocatorias');

            $convocatoria->afiche = $path;
        }

        $convocatoria->save();

        if ($request->get('id') === null) {
            // Crear una solicitud para al llevar el flujo de ejecución de la convocatoria
            $flujo = Flujo::where('codigo', 'CONFIGURACION_CONVOCATORIA')->first();
            $estado = Estado::where('codigo', 'INICIO')->first();
            $etapa = $flujo->primeraEtapa();


            $solicitud = new Solicitud();
            $solicitud->flujo()->associate($flujo);
            $solicitud->estado()->associate($estado);
            $solicitud->etapa()->associate($etapa);
            $solicitud->solicitante()->associate($convocatoria);
            $solicitud->save();
            //Guardarla en el historial de la solicitud
            $solicitud->guardarHistorial();
        }

        $convocatoriaData = $this->convocatoriaBase()->find($convocatoria->id);
        $convocatoriaData->etapa = $convocatoria->solicitud->etapa->codigo;

        return response()->json(['status' => 'ok', 'message' => '_datos_guardados_', 'convocatoria' => $convocatoriaData]);
    }

    public function ofertaSave($id, Request $request)
    {

        $request->validate([
            'carreras_sedes'  => 'required'
        ]);
        $convocatoria = Convocatoria::find($id);

        $convocatoria->carrerasSedes()->sync($request->get('carreras_sedes') ?? []);

        $convocatoria->save();

        //Verificar la solicitud
        $solicitud = $convocatoria->solicitud;
        if ($solicitud && $solicitud->etapa->codigo === 'OFERTA') {
            $solicitud->pasarSiguienteEtapa();
            $solicitud->save();
            $solicitud->guardarHistorial();
        }

        $convocatoriaData = $this->convocatoriaBase()->find($convocatoria->id);
        $convocatoriaData->etapa = $solicitud->etapa->codigo;

        return response()->json(['status' => 'ok', 'message' => '_datos_guardados_', 'convocatoria' => $convocatoriaData]);
    }

    public function configuracionSave($id, Request $request)
    {

        $convocatoria = Convocatoria::find($id);
        $configuracion = $convocatoria->configuracion;

        if (!$configuracion) {
            //No existe el registro de configuración, crearlo
            $configuracion = new ConvocatoriaConfiguracion();
            $configuracion->convocatoria()->associate($convocatoria);
        }

        $fechasHoras = ['publicacion_resultados', 'inicio_recepcion_solicitudes', 'fin_recepcion_solicitudes'];
        foreach ($fechasHoras as $campo) {
            $fecha_ = $request->get('fecha_' . $campo);
            $fechaHora = null;
            if ($fecha_) {
                $fecha = new \DateTime($fecha_);
                $hora = $request->get('hora_' . $campo) ?? '00:00:00';

                $fechaHora = $fecha->format("Y-m-d") . ' ' . $hora;
            }
            $configuracion->{'fecha_' . $campo} = $fechaHora;
        }


        $configuracion->cuota_sector_publico = $request->get('cuota_sector_publico');
        $configuracion->prueba_bachillerato_id = $request->get('prueba_bachillerato_id');
        $configuracion->save();

        //Verificar la solicitud
        $solicitud = $convocatoria->solicitud;
        if ($solicitud && $solicitud->etapa->codigo === 'CONFIGURACION') {
            $solicitud->pasarSiguienteEtapa();
            $solicitud->save();
            $solicitud->guardarHistorial();
        }

        $convocatoriaData = $this->convocatoriaBase()->find($convocatoria->id);
        $convocatoriaData->etapa = $solicitud->etapa->codigo;

        return response()->json(['status' => 'ok', 'message' => '_datos_guardados_', 'convocatoria' => $convocatoriaData]);
    }

    protected function convocatoriaBase()
    {
        return Convocatoria::with([
            'carrerasSedes',
            'creator',
            'updater',
            'configuracion',
            'flujo',
            'solicitud' => ['estado', 'etapa', 'historial' => ['etapa', 'estado', 'creator']]
        ]);
    }

    public function delete(int $id)
    {
        $convocatoria = Convocatoria::find($id);

        //Verificar si tiene un afiche cargado, borrarlo en ese caso
        $filePath = $convocatoria->afiche;
        if ($filePath != null && Storage::exists($filePath)) {
            Storage::delete($filePath);
        }
        $delete = Convocatoria::destroy($id);

        if ($delete == 0) {
            return response()->json(['status' => 'error', 'message' => '_no_se_encontro_registro_']);
        } else {
            return response()->json(['status' => 'ok', 'message' => $id]);
        }
    }

    public function enviarInvitacionesPendientes(int $id)
    {
        $convocatoria = Convocatoria::find($id);

        //invitaciones pendientes de envío
        $invitaciones = $convocatoria->invitaciones()->whereNull('fecha_envio_correo')->get();

        foreach ($invitaciones as $inv) {
            $bachiller = DataBachillerato::where('nie', $inv->nie)->first();
            Mail::to($bachiller->correo)->queue(
                new CandidatoInvitado($bachiller, $convocatoria)
            );
            $inv->fecha_envio_correo = new \DateTime();

            $inv->save();
        }

        // Mandar los datos actualizados
        $convocatoriaAct = Convocatoria::select('id', 'nombre', 'descripcion')
            ->where('id', $convocatoria->id)
            ->orderBy('nombre', 'asc')
            ->withCount([
                'invitaciones as invitaciones',
                'invitaciones as invitaciones_pendientes_envio' => function (Builder $query) {
                    $query->whereNull('fecha_envio_correo');
                },
                'invitaciones as invitaciones_aceptadas' => function (Builder $query) {
                    $query->whereNotNull('fecha_aceptacion');
                },
            ])
            ->first();

        return response()->json(['status' => 'ok', 'message' => '', 'convocatoria' => $convocatoriaAct]);
    }


    public function aficheDownload(int $id)
    {
        $convocatoria = Convocatoria::find($id);
        $filePath = $convocatoria->afiche;

        if (Storage::exists($filePath)) {
            return Storage::download($filePath, 'archivo.pdf');
        } else {
            abort(404);
        }
    }

    public function aficheDelete(int $id)
    {
        $convocatoria = Convocatoria::find($id);
        $filePath = $convocatoria->afiche;
        $convocatoria->afiche = null;
        $convocatoria->save();
        if (Storage::exists($filePath)) {
            Storage::delete($filePath);
        }
        return response()->json(['status' => 'ok', 'message' => $id]);
    }

    public function ofertaCarreras(int $id)
    {
        $convocatoria = Convocatoria::find($id);
        $oferta = $this->getSedesCarreras($convocatoria);
        return response()->json(['status' => 'ok', 'oferta' => $oferta]);
    }



    public function solicitudes(int $id, int $idSede)
    {
        $convocatoria = Convocatoria::find($id);
        $sede = Sede::find($idSede);

        $querySol = $this->solicitudService->getQB()
            ->orderBy('aspirante.calificacion_bachillerato', 'DESC')
            //->where('aspirante.calificacion_bachillerato',  '>=', 0)
            ->where('solicitud.modelo_id', $convocatoria->id)
            ->where('solicitud.modelo_type', Convocatoria::class);

        if ($idSede > 0) {
            $querySol->whereBelongsTo($sede);
        }
        $solicitudes_ = $querySol->get();

        $solitudes = [];
        foreach ($solicitudes_ as $sol) {
            $arreglo = [
                'id' => $sol->id,
                'nie' => $sol->solicitante->nie,
                'nombre' => $sol->solicitante->persona->nombreCompleto,
                'carrera_nombre' => $sol->nombre_carrera,
                'carrera_codigo' => $sol->codigo_carrera,
                'sede' => $sol->sede->nombre,
                'opcion' => $sol->opcion,
                'sexo' => $sol->solicitante->persona->sexo->descripcion,
                'nota' => $sol->solicitante->calificacion_bachillerato,
                'seleccionado' => $sol->seleccionado,
                'solicitud_carrera_sede_id' => $sol->solicitud_carrera_sede_id,
                'carrera_sede_id' => $sol->carrera_sede_id,
                'sector' => $sol->sector,
            ];
            foreach ($sol->solicitudCarrerasSede as $scs) {
                $arreglo[$scs->tipoCarreraSedeSolicitud->codigo] = [
                    'solicitud_carrera_sede_id' => $scs->id,
                    'carrera_sede_id' => $scs->carreraSede->id,
                    'carrera' => $scs->carreraSede->carrera->nombreCompleto,
                    'opcion' => $scs->tipoCarreraSedeSolicitud->codigo
                ];
            }
            $solitudes[] = $arreglo;
        }

        $infoSede = [];
        $ofertaSede = [];

        if (!empty($solitudes)) {
            $queryOferta = CarreraSede::with([
                'carrera' => ['tipo'],
                'sede'
            ])
                ->withCount([
                    'solicitudes as seleccionados' => function (Builder $query) use ($convocatoria) {
                        $query
                            ->join('workflow.solicitud as s', 'workflow.solicitud_carrera_sede.solicitud_id', '=', 's.id')
                            ->join('ingreso.convocatoria_aspirante as ca', 'workflow.solicitud_carrera_sede.id', '=', 'ca.solicitud_carrera_sede_id')
                            ->where('s.solicitante_type', 'App\Models\Ingreso\Aspirante')
                            ->where('s.modelo_type', 'App\Models\Ingreso\Convocatoria')
                            ->where('s.modelo_id',  $convocatoria->id)
                            ->where('ca.seleccionado', true);
                    },
                    'solicitudes as seleccionados_publico' => function (Builder $query) use ($convocatoria) {
                        $query
                            ->join('workflow.solicitud as s', 'workflow.solicitud_carrera_sede.solicitud_id', '=', 's.id')
                            ->join('ingreso.convocatoria_aspirante as ca', 'workflow.solicitud_carrera_sede.id', '=', 'ca.solicitud_carrera_sede_id')
                            ->join('ingreso.aspirante as a', 'ca.aspirante_id', '=', 'a.id')
                            ->join('public.persona as p', 'a.persona_id', '=', 'p.id')
                            ->join('public.estudio as e', 'p.id', 'e.persona_id')
                            ->join('secundaria.institucion as i', 'e.institucion_id', 'i.id')
                            ->join('secundaria.sector as sec', 'i.sector_id', 'sec.id')
                            ->where('ca.seleccionado', true)
                            ->where('e.institucion_type', 'App\Models\Secundaria\Institucion')
                            ->where('s.solicitante_type', 'App\Models\Ingreso\Aspirante')
                            ->where('s.modelo_type', 'App\Models\Ingreso\Convocatoria')
                            ->where('s.modelo_id',  $convocatoria->id)
                            ->where('sec.codigo', '01');
                    }
                ]);
            if ($idSede > 0) {
                $queryOferta->where('sede_id', $idSede);
            }

            $ofertaSede_ = $queryOferta->get();

            foreach ($ofertaSede_ as $cs) {
                $ofertaSede[] = [
                    'carrera_sede_id'   => $cs->id,
                    'sede'              => $cs->sede->nombre,
                    'cupo'              => $cs->cupo,
                    'carrera_id'        => $cs->carrera_id,
                    'carrera'           => $cs->carrera->nombreCompleto,
                    'carrera_nombre'    => $cs->carrera->nombre,
                    'carrera_tipo'      => $cs->carrera->tipo->descripcion,
                    'seleccionados'     => $cs->seleccionados,
                    'seleccionados_publico' => $cs->seleccionados_publico,
                    'seleccionados_privado' => $cs->seleccionados - $cs->seleccionados_publico
                ];
                if (!array_key_exists($cs->sede->nombre, $infoSede)) {
                    $infoSede[$cs->sede->nombre] =  ['nombre' => '', 'cupo' => 0, 'seleccionados' => 0, 'seleccionados_publico' => 0, 'seleccionados_privado' => 0];
                }
                $infoSede[$cs->sede->nombre]['nombre'] = $cs->sede->nombre;
                $infoSede[$cs->sede->nombre]['cupo'] += $cs->cupo;
                $infoSede[$cs->sede->nombre]['seleccionados'] +=  $cs->seleccionados;
                $infoSede[$cs->sede->nombre]['seleccionados_publico'] += $cs->seleccionados_publico;
                $infoSede[$cs->sede->nombre]['seleccionados_privado'] += $cs->seleccionados - $cs->seleccionados_publico;
            }
        }


        /*foreach($infoSede as $i) {

        }*/
        /*array_multisort(
            array_column($ofertaSede, 'carrera_tipo'),
            SORT_ASC,
            array_column($ofertaSede, 'carrera_nombre'),
            SORT_ASC,
            $ofertaSede
        );*/

        return response()->json(['status' => 'ok', 'ofertaSede' => $ofertaSede, 'solicitudes' => $solitudes, 'infoSede' => $infoSede]);
    }
}
