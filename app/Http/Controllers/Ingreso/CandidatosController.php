<?php

namespace App\Http\Controllers\Ingreso;

use App\Http\Controllers\Controller;
use App\Models\Secundaria\SecundariaDataBachillerato;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        return Inertia::render('ingreso/SeleccionCandidatos', [
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
            ->select('A.*', 'B.fecha_envio_correo', 'B.fecha_aceptacion', 'B.created_at as fecha_invitacion')
            ->leftJoin('secundaria.invitacion as B', 'A.nie', '=', 'B.nie')
            ->orderBy('nota_promocion', 'desc');

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
}
