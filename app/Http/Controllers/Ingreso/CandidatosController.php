<?php

namespace App\Http\Controllers\Ingreso;

use App\Http\Controllers\Controller;
use App\Mail\CandidatoInvitado;
use App\Models\Ingreso\Convocatoria;
use App\Models\Secundaria\DataBachillerato;
use App\Models\Secundaria\Invitacion;
use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Inertia\Response;

class CandidatosController extends Controller
{
    /**
     *
     */
    public function index(Request $request): Response
    {
        $departamentos = DB::table('secundaria.data_bachillerato')
            ->select('codigo_depto', 'departamento')
            ->orderBy('codigo_depto', 'asc')
            ->distinct()
            ->get();
        $opcionesBachillerato = DB::table('secundaria.data_bachillerato')
            ->select('opcion_bachillerato as opcion')
            ->orderBy('opcion_bachillerato', 'asc')
            ->groupBy('opcion_bachillerato')
            ->get();
        $convocatorias = Convocatoria::select('id', 'nombre', 'descripcion')
            ->with([
                'solicitud' => ['etapa']
            ])
            ->where('activa', true)
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
            ->get();
        //Revisar las convocatorias que ya se cumplió la fecha de fin de recepción de solicitudes
        // para pasarla a etapa de SELECCION_ASPIRANTES (si está en etapa de INVITACIONES)
        $convocatorias_ = [];
        $today = new \DateTime();
        foreach ($convocatorias as $c) {
            $solicitud = $c->solicitud;
            if ($c->configuracion != null) {
                $fecha_fin_sol = new \DateTime($c->configuracion->fecha_fin_recepcion_solicitudes);
                if ($today > $fecha_fin_sol && $solicitud->etapa->codigo === 'INVITACIONES') {
                    $solicitud->pasarSiguienteEtapa();
                    $solicitud->save();
                    $solicitud->guardarHistorial();
                }
            }
            $convocatorias_[] = $c;
        }

        return Inertia::render('ingreso/Candidatos', [
            'status' => $request->session()->get('status'),
            //'componente' => 'seleccionCandidatos',            'titulo' => 'Seleccionar candidatos',
            'departamentos' => $departamentos,
            'opcionesBachillerato' => $opcionesBachillerato,
            'convocatorias' => $convocatorias_
        ]);
    }

    /**
     *
     */
    public function listado(Request $request)
    {
        $query = DB::table('secundaria.data_bachillerato', 'A')
            ->select('A.*', 'B.invitado', 'B.fecha_envio_correo', 'B.fecha_aceptacion', 'B.created_at as fecha_invitacion')
            ->leftJoin('secundaria.invitacion as B', 'A.nie', '=', 'B.nie');

        $departamentos = $request->get('departamentos');
        if ($departamentos !== null) {
            $codigos = array_map(fn($depto) => $depto['codigo_depto'], $departamentos);
            $query->whereIn('codigo_depto', $codigos);
        }

        $opcionesBach = $request->get('opciones');
        if ($opcionesBach !== null) {
            $opciones = array_map(fn($opcion) => $opcion['opcion'], $opcionesBach);
            $query->whereIn('opcion_bachillerato', $opciones);
        }
        $listado_ = $query->get();
        $listado = [];
        foreach ($listado_ as $a) {
            //$array = (array) $a;
            $a->nombre_completo = preg_replace('/\\s+/', ' ', $a->primer_nombre . ' ' . $a->segundo_nombre . ' ' . $a->tercer_nombre . ' ' . $a->primer_apellido . ' ' . $a->segundo_apellido . ' ' . $a->tercer_apellido);
            $listado[] = $a;
        }
        return response()->json($listado);
    }


    /**
     *
     */
    public function resumen(Request $request)
    {
        $query = DB::table('secundaria.data_bachillerato', 'A')
            ->selectRaw('"A".departamento, "A".sexo, "A".sector, "A".opcion_bachillerato,
                        count("A".nie) as candidatos, count("B".fecha_envio_correo) as invitados')
            ->leftJoin('secundaria.invitacion as B', 'A.nie', '=', 'B.nie')
            ->groupBy(
                'A.departamento',
                'A.sexo',
                'A.sector',
                'A.opcion_bachillerato',
            );

        return response()->json($query->get());
    }

    public function saveField(Request $request)
    {
        $nie = $request->get('nie');
        $campo = $request->get('campo');
        $idConvocatoria = $request->get('idConvocatoria');

        $bachiller = DataBachillerato::where('nie', $nie)->first();
        $convocatoria = Convocatoria::find($idConvocatoria);
        $convocatoriaAct = null;

        if ($campo == 'invitado') {
            $invitado = $request->get('invitado');
            // Buscar la invitacion
            $invitacion = Invitacion::where('nie', $nie)->first();
            if (!$invitacion && $invitado) {
                $invitacion = Invitacion::create([
                    'nie' => $nie,
                    'codigo' => chr(rand(65, 90)) . chr(rand(65, 90)) . random_int(1000, 9999),
                    'invitado' => true
                ]);
                $invitacion->convocatoria()->associate($convocatoria);
                $invitacion->save();
            } else {
                $invitacion->delete();
            }
            /*if ($bachiller->correo) {
                // Enviar correo
                Mail::to($bachiller->correo)->queue(
                    new CandidatoInvitado($bachiller, $convocatoria)
                );
                $invitacion->fecha_envio_correo = new \DateTime();
            }*/
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
        } elseif ($campo == 'correo') {
            $correo = $request->get('correo');
            $bachiller->correo = $correo;

            $bachiller->save();
        }

        return response()->json(['message' => 'Cambio realizado', 'convocatoria' => $convocatoriaAct]);
    }

    public function invitaciones(Request $request)
    {
        $tipoEnvio = $request->get('tipoEnvio');

        $tipoInvitacion = $request->get('tipoInvitacion');
        $idConvocatoria = $request->get('idConvocatoria');

        $convocatoria = Convocatoria::find($idConvocatoria);

        //Recuperar los datos de los bachilleres que tengan correo registrado
        $query = DataBachillerato::from('secundaria.data_bachillerato as A')
            ->select('A.*', 'B.invitado', 'B.fecha_envio_correo', 'B.fecha_aceptacion', 'B.created_at as fecha_invitacion')
            ->leftJoin('secundaria.invitacion as B', 'A.nie', '=', 'B.nie')
            ->whereNotNull('A.correo');


        if ($tipoInvitacion === 'nuevo') {
            //Solo los que no tengan fecha de envío de correo
            $query->whereNull('fecha_envio_correo');
        }
        if ($tipoEnvio === 'opciones') {
            $query->whereIn('A.opcion_bachillerato', function ($query) {
                $query->select('cs.descripcion')
                    ->from('secundaria.carrera as cs')
                    ->join('ingreso.relacion_carreras as rc', 'cs.id', '=', 'rc.carrera_secundaria_id')
                ;
            });
        }

        $bachilleres = $query->get();

        foreach ($bachilleres as $bachiller) {
            $nie = $bachiller->nie;
            $invitacion = Invitacion::firstOrCreate([
                'nie' => $nie
            ]);

            $invitacion->codigo = (rand(65, 90)) . chr(rand(65, 90)) . random_int(1000, 9999);
            $invitacion->invitado = true;
            $invitacion->convocatoria()->associate($convocatoria);

            Mail::to($bachiller->correo)->queue(
                new CandidatoInvitado($bachiller, $convocatoria)
            );
            $invitacion->fecha_envio_correo = new \DateTime();

            $invitacion->save();
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


        return response()->json(['message' => 'Envios realizados', 'convocatoria' => $convocatoriaAct]);
    }
}
