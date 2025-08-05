<?php

namespace App\Http\Controllers\Ingreso;

use App\Http\Controllers\Controller;
use App\Mail\CandidatoInvitado;
use App\Models\Secundaria\DataBachillerato;
use App\Models\Secundaria\Invitacion;
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

        return Inertia::render('ingreso/Candidatos', [
            'status' => $request->session()->get('status'),
            //'componente' => 'seleccionCandidatos',            'titulo' => 'Seleccionar candidatos',
            'departamentos' => $departamentos,
            'opcionesBachillerato' => $opcionesBachillerato
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
        return response()->json($query->get());
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



        $bachiller = DataBachillerato::where('nie', $nie)->first();

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
            }
            if ($bachiller->correo) {
                // Enviar correo
                Mail::to($bachiller->correo)->queue(
                    new CandidatoInvitado($bachiller)
                );
                $invitacion->fecha_envio_correo = new \DateTime();
            }
        } elseif ($campo == 'correo') {
            $correo = $request->get('correo');
            $bachiller->correo = $correo;

            $bachiller->save();
        }

        return response()->json(['message' => 'Cambio realizado']);
    }
}
