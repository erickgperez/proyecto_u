<?php

namespace App\Http\Controllers\Ingreso;

use App\Http\Controllers\Controller;
use App\Mail\CandidatoInvitado;
use App\Models\Academica\CarreraSede;
use App\Models\Academica\Sede;
use App\Models\Calendarizacion;
use App\Models\Ingreso\Aspirante;
use App\Models\Ingreso\Convocatoria;
use App\Models\Secundaria\DataBachillerato;
use App\Models\Secundaria\Institucion;
use App\Models\Workflow\Solicitud;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class ConvocatoriaController extends Controller
{
    /**
     *
     */
    public function index(Request $request): Response
    {

        $convocatorias = Convocatoria::with('carrerasSedes', 'creator', 'updater')->get();

        $items = $this->getSedesCarreras();

        return Inertia::render('ingreso/Convocatoria', ['items' => $convocatorias, 'sedesCarreras' => $items]);
    }

    public function save(Request $request)
    {
        // Aunque se ha validado del lado del cliente, validar aquí también
        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:255',
            'fecha' => 'required|date',
            'cuerpo_mensaje' => 'nullable|string',
            'afiche_file' => 'nullable|file|mimes:pdf',
            'afiche_file' => 'nullable|file|mimes:pdf',
            'carrerasSedes' => 'nullable',
        ]);

        if ($request->get('id') === null) {
            $convocatoria = new Convocatoria();
            //Crear el calendario de actividades
            $calendarizacion = new Calendarizacion();
            $calendarizacion->nombre = $request->get('nombre'); //llevará el mismo nombre que la convocatoria
            $calendarizacion->save();

            //Asociar el calendario a la convocatoria
            $convocatoria->calendario()->associate($calendarizacion);
        } else {
            $convocatoria = Convocatoria::find($request->get('id'));
        }

        $convocatoria->nombre = $request->get('nombre');
        $convocatoria->descripcion = $request->get('descripcion');
        $convocatoria->fecha = $request->get('fecha');
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

        $convocatoriaData = Convocatoria::with('carrerasSedes', 'creator', 'updater')->find($convocatoria->id);

        return response()->json(['status' => 'ok', 'message' => '_datos_guardados_', 'convocatoria' => $convocatoriaData]);
    }

    public function ofertaSave($id, Request $request)
    {

        $convocatoria = Convocatoria::find($id);

        $convocatoria->carrerasSedes()->sync($request->get('carreras_sedes') ?? []);

        $convocatoria->save();

        $convocatoriaData = Convocatoria::with('carrerasSedes', 'creator', 'updater')->find($convocatoria->id);

        return response()->json(['status' => 'ok', 'message' => '_datos_guardados_', 'convocatoria' => $convocatoriaData]);
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

    private function getSedesCarreras(?Convocatoria $convocatoria = null)
    {
        $query = DB::table('academico.carrera_sede as cs')
            ->select(
                's.id as sede_id',
                's.nombre as sede_nombre',
                'tc.id as tipo_carrera_id',
                'tc.descripcion as tipo_carrera',
                'cs.id as id',
                'c.nombre as nombre_carrera',
                'c.codigo as codigo_carrera'
            )
            ->join('plan_estudio.carrera as c', 'cs.carrera_id', '=', 'c.id')
            ->join('academico.sede as s', 'cs.sede_id', '=', 's.id')
            ->join('plan_estudio.tipo_carrera as tc', 'c.tipo_carrera_id', '=', 'tc.id')
            ->orderBy('s.nombre')
            ->orderBy('tc.descripcion')
            ->orderBy('c.nombre');

        if ($convocatoria != null) {
            $query->join('ingreso.convocatoria_carrera_sede as ccs', 'cs.id', '=', 'ccs.carrera_sede_id')
                ->where('ccs.convocatoria_id', $convocatoria->id);
        }
        $carrerasSedes = $query->get();

        $sedesCarreras = [];

        foreach ($carrerasSedes as $cs) {
            $sedesCarreras[$cs->sede_id]['id'] = 's-' . $cs->sede_id;
            $sedesCarreras[$cs->sede_id]['title'] = $cs->sede_nombre;
            $sedesCarreras[$cs->sede_id]['childrenn'][$cs->tipo_carrera_id]['id'] = $cs->sede_id . '-' . $cs->tipo_carrera_id;
            $sedesCarreras[$cs->sede_id]['childrenn'][$cs->tipo_carrera_id]['title'] = $cs->tipo_carrera;
            $sedesCarreras[$cs->sede_id]['childrenn'][$cs->tipo_carrera_id]['children'][] = ['id' => $cs->id, 'title' => '(' . $cs->codigo_carrera . ') ' . $cs->nombre_carrera];
        }

        $items = [];
        foreach ($sedesCarreras as $sc) {
            $sc_ = $sc;
            foreach ($sc['childrenn'] as $c) {
                $sc_['children'][] = $c;
            }
            unset($sc_['childrenn']);
            $items[] = $sc_;
        }

        return $items;
    }

    public function solicitudes(int $id, int $idSede)
    {
        $convocatoria = Convocatoria::find($id);
        $sede = Sede::find($idSede);

        $solicitudes_ = $convocatoria->solicitudes()->with([
            'solicitante',
            'etapa',
            'estado',
            'solicitudCarrerasSede'
        ])
            ->select(
                'solicitud.*',
                'sector.descripcion as sector',
                'convocatoria_aspirante.seleccionado',
                'convocatoria_aspirante.solicitud_carrera_sede_id',
                'scs.carrera_sede_id',
            )
            ->join('ingreso.aspirante as aspirante', function ($join) {
                $join->on('solicitud.solicitante_id', '=', 'aspirante.id')
                    ->where('solicitud.solicitante_type', '=', Aspirante::class);
            })
            ->join('ingreso.convocatoria_aspirante as convocatoria_aspirante', function ($join) use ($convocatoria) {
                $join->on('aspirante.id', '=', 'convocatoria_aspirante.aspirante_id')
                    ->where('convocatoria_aspirante.convocatoria_id', $convocatoria->id);
            })
            ->leftJoin('workflow.solicitud_carrera_sede as scs', 'scs.id', '=', 'convocatoria_aspirante.solicitud_carrera_sede_id')
            ->join('public.persona as persona', 'aspirante.persona_id', '=', 'persona.id')
            ->join('public.estudio as estudio', 'estudio.persona_id', '=', 'persona.id')
            ->join('secundaria.institucion as institucion', function ($join) {
                $join->on('estudio.institucion_id', '=', 'institucion.id')
                    ->where('estudio.institucion_type', '=', Institucion::class);
            })
            ->join('secundaria.sector as sector', 'institucion.sector_id', '=', 'sector.id')
            ->whereBelongsTo($sede)
            ->orderBy('aspirante.calificacion_bachillerato', 'DESC')
            ->get();

        $solitudes = [];
        foreach ($solicitudes_ as $sol) {
            $arreglo = [
                'id' => $sol->id,
                'nie' => $sol->solicitante->nie,
                'nombre' => $sol->solicitante->persona->nombreCompleto,
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

        $ofertaSede_ = CarreraSede::with([
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
            ])
            ->where('sede_id', $idSede)
            ->get();
        $ofertaSede = [];
        foreach ($ofertaSede_ as $cs) {
            $ofertaSede[] = [
                'carrera_sede_id'   => $cs->id,
                'cupo'              => $cs->cupo,
                'carrera_id'        => $cs->carrera_id,
                'carrera'           => $cs->carrera->nombreCompleto,
                'carrera_nombre'    => $cs->carrera->nombre,
                'carrera_tipo'      => $cs->carrera->tipo->descripcion,
                'seleccionados'     => $cs->seleccionados,
                'seleccionados_publico' => $cs->seleccionados_publico,
                'seleccionados_privado' => $cs->seleccionados - $cs->seleccionados_publico
            ];
        }
        array_multisort(
            array_column($ofertaSede, 'carrera_tipo'),
            SORT_ASC,
            array_column($ofertaSede, 'carrera_nombre'),
            SORT_ASC,
            $ofertaSede
        );

        return response()->json(['status' => 'ok', 'ofertaSede' => $ofertaSede, 'solicitudes' => $solitudes]);
    }
}
