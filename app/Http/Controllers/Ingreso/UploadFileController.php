<?php

namespace App\Http\Controllers\Ingreso;

use App\Http\Controllers\Controller;
use App\Imports\BachilleratoImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;
use Maatwebsite\Excel\Facades\Excel;

class UploadFileController extends Controller
{
    /**
     *
     */
    public function index(Request $request): Response
    {
        return Inertia::render('ingreso/CargarDatosBachillerato', [
            'status' => $request->session()->get('status'),
        ]);
    }

    /**
     *
     */
    public function import(Request $request)
    {

        ini_set('max_execution_time', 300);

        //phpinfo();
        //exit;
        $request->validate([
            'archivo' => 'required|file|mimes:csv,txt',
        ]);

        $path = $request->file('archivo')->store('imports');

        if ($request->get('tipoCarga') === 'nueva') {
            DB::delete('delete from secundaria.data_bachillerato');
        }

        Excel::import(new BachilleratoImport, $path);

        //Llenar la tabla de instituciones de secundaria
        $institucionesQuery = DB::table('secundaria.data_bachillerato', 'A')
            ->select('codigo_ce', 'nombre_centro_educativo', 'direccion', 'B.id as id_sector', 'C.id as id_distrito')
            ->join('secundaria.sector as B', function ($join) {
                $join->on(DB::raw('LOWER("A".sector)'), '=', DB::raw('LOWER("B".descripcion)'));
            })
            ->join('public.distrito as C', 'A.codigo_distrito', '=', 'C.codigo')
            ->groupBy('codigo_ce', 'nombre_centro_educativo', 'direccion', 'B.id', 'C.id')
            ->whereNotIn('codigo_ce', function ($qb) {
                $qb->select('codigo')->from('secundaria.institucion');
            });

        DB::table('secundaria.institucion')
            ->insertUsing(['codigo', 'nombre', 'direccion', 'sector_id', 'distrito_id'], $institucionesQuery);

        //Llenar la tabla de carreras de secundaria
        $carrerasQuery = DB::table('secundaria.data_bachillerato', 'A')
            ->select('opcion_bachillerato')
            ->groupBy('opcion_bachillerato')
            ->whereNotIn('opcion_bachillerato', function ($qb) {
                $qb->select('descripcion')->from('secundaria.carrera');
            });

        DB::table('secundaria.carrera')
            ->insertUsing(['descripcion'], $carrerasQuery);

        return back()->with('success', 'Excel file imported successfully!');
    }
}
