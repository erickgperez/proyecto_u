<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Models\Academico\Semestre;
use App\Models\Calendarizacion;
use App\Models\TipoCalendarizacion;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SemestreController extends Controller
{
    /**
     *
     */
    public function index(): Response
    {

        $semestres = Semestre::with('creator', 'updater', 'calendario')->orderBy('codigo')->get();

        return Inertia::render('academico/Semestre', ['items' => $semestres]);
    }

    public function save(Request $request)
    {
        // Aunque se ha validado del lado del cliente, validar aquí también
        $request->validate([
            'codigo' => 'required|string|max:50',
            'descripcion' => 'nullable|string',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date',
        ]);

        if ($request->get('id') === null) {
            // Está agregando uno nuevo, verificar que no exista el código
            $semestreCheck = Semestre::where('codigo', $request->get('codigo'))->first();
            if ($semestreCheck === null) {
                $semestre = new Semestre();

                $tipoCalendario = TipoCalendarizacion::where('codigo', 'SEMESTRE')->first();
                $calendarizacion = new Calendarizacion();
                $calendarizacion->codigo = substr('Semestre-' . $request->get('codigo'), 0, 100); //llevará el mismo código que el semestre
                $calendarizacion->descripcion = substr('Actividades del semestre: ' . $request->get('descripcion'), 0, 255);
                $calendarizacion->tipo()->associate($tipoCalendario);
                $calendarizacion->save();

                //Asociar el calendario a la convocatoria
                $semestre->calendario()->associate($calendarizacion);
            } else {
                return response()->json(['status' => 'error', 'message' => 'semestre._codigo_ya existe_']);
            }
        } else {
            // Verificar que el nuevo código que ponga no esté utilizado por otro registro
            $semestreCheck = Semestre::where('codigo', $request->get('codigo'))
                ->where('id', '!=', $request->get('id'))
                ->first();

            if ($semestreCheck === null) {
                $semestre = Semestre::find($request->get('id'));
            } else {
                return response()->json(['status' => 'error', 'message' => 'semestre._codigo_ya existe_']);
            }
        }

        $semestre->codigo = $request->get('codigo');
        $semestre->descripcion = $request->get('descripcion');
        $semestre->fecha_inicio = $request->get('fecha_inicio');
        $semestre->fecha_fin = $request->get('fecha_fin');

        $semestre->save();

        //Obtener la información de las relaciones del item recién creado/actualizado
        $item = Semestre::with('creator', 'updater', 'calendario')->find($semestre->id);

        return response()->json(['status' => 'ok', 'message' => '_datos_guardados_', 'item' => $item]);
    }

    public function delete($id)
    {
        $delete = Semestre::where('uuid', $id)->first()->delete();

        if ($delete == 0) {
            return response()->json(['status' => 'error', 'message' => '_no_se_encontro_registro_']);
        } else {
            return response()->json(['status' => 'ok', 'message' => $id]);
        }
    }
}
