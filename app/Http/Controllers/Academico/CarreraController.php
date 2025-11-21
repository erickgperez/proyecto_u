<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Models\Academico\CarreraSede;
use App\Models\Academico\Sede;
use App\Models\PlanEstudio\Carrera;
use App\Models\PlanEstudio\TipoCarrera;
use App\Models\Secundaria\Carrera as SecundariaCarrera;
use App\Services\CarreraSedeService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class CarreraController extends Controller
{
    protected $carreraSedeService;

    public function __construct(CarreraSedeService $carreraSedeService)
    {
        $this->carreraSedeService = $carreraSedeService;
    }

    /**
     *
     */
    public function index(): Response
    {

        $carreras = Carrera::with('padre', 'tipo', 'creator', 'updater', 'sedes', 'carrerasSecundaria')->orderBy('nombre')->get();
        $tiposCarrera = TipoCarrera::orderBy('descripcion')->get();
        $sedes = Sede::orderBy('nombre')->get();
        $carrerasSecundaria = SecundariaCarrera::orderBy('descripcion')->get();

        $items = [];
        foreach ($carreras as $row) {
            $item = $row->toArray();
            $item['padre_'] = $row->padre?->nombreCompleto;
            $item['tipo_'] = $row->tipo?->descripcion;

            $items[] = $item;
        }

        return Inertia::render('academico/Carrera', ['items' => $items, 'tiposCarrera' => $tiposCarrera, 'sedes' => $sedes, 'carrerasSecundaria' => $carrerasSecundaria]);
    }

    public function save(Request $request)
    {
        // Aunque se ha validado del lado del cliente, validar aquí también
        $request->validate([
            'codigo' => 'required|string|max:30',
            'nombre' => 'required|string',
            'tipo_carrera_id' => ['required', 'integer', Rule::exists('pgsql.plan_estudio.tipo_carrera', 'id')],
            'certificacion_de' => ['nullable', 'integer', Rule::exists('pgsql.plan_estudio.carrera', 'id')],
        ]);

        if ($request->get('id') === null) {
            // Está agregando uno nuevo, verificar que no exista el código
            $carreraCheck = Carrera::where('codigo', $request->get('codigo'))->first();
            if ($carreraCheck === null) {
                $carrera = new Carrera();
            } else {
                return response()->json(['status' => 'error', 'message' => 'carrera._codigo_ya existe_']);
            }
        } else {
            // Verificar que el nuevo código que ponga no esté utilizado por otro registro
            $carreraCheck = Carrera::where('codigo', $request->get('codigo'))
                ->where('id', '!=', $request->get('id'))
                ->first();

            if ($carreraCheck === null) {
                $carrera = Carrera::find($request->get('id'));
            } else {
                return response()->json(['status' => 'error', 'message' => 'carrera._codigo_ya existe_']);
            }
        }

        $padre = Carrera::find($request->get('certificacion_de'));
        $tipoCarrera = TipoCarrera::find($request->get('tipo_carrera_id'));

        $carrera->codigo = $request->get('codigo');
        $carrera->nombre = $request->get('nombre');
        $carrera->padre()->associate($padre);
        $carrera->tipo()->associate($tipoCarrera);

        $carrera->sedes()->sync($request->get('sedes') ?? []);

        $carrera->carrerasSecundaria()->sync($request->get('carreras_secundaria') ?? []);

        $carrera->save();

        //Obtener la información de las relaciones del item recién creado/actualizado
        $item = Carrera::with('padre', 'tipo', 'creator', 'updater', 'sedes', 'carrerasSecundaria')->find($carrera->id);
        $item['padre_'] = $carrera->padre?->nombreCompleto;
        $item['tipo_'] = $carrera->tipo?->descripcion;

        return response()->json(['status' => 'ok', 'message' => '_datos_guardados_', 'item' => $item]);
    }

    public function delete($id)
    {
        $delete = Carrera::where('uuid', $id)->first()->delete();

        if ($delete == 0) {
            return response()->json(['status' => 'error', 'message' => '_no_se_encontro_registro_']);
        } else {
            return response()->json(['status' => 'ok', 'message' => $id]);
        }
    }

    public function getCarreraSede()
    {
        //Obtener las carreras/sedes agrupadas para formar el control de árbol
        $carrerasSedes = $this->carreraSedeService->getSedesCarreras();

        //Obtener las carreras sedes sin agrupar
        $carrerasSedesPlain = CarreraSede::with('carrera', 'sede')->get();
        return response()->json([
            'status' => 'ok',
            'message' => '',
            'carrerasSedes' => $carrerasSedes,
            'carrerasSedesPlain' => $carrerasSedesPlain,
        ]);
    }
}
