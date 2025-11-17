<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Models\Academico\Oferta;
use App\Models\Academico\Semestre;
use App\Models\Calendarizacion;
use App\Models\PlanEstudio\Carrera;
use App\Models\TipoCalendarizacion;
use Illuminate\Http\Request;


class OfertaController extends Controller
{

    public function index($id)
    {
        $semestre = Semestre::find($id);
        $carreras = Carrera::with(['unidadesAcademicas' => ['unidadAcademica']])
            ->whereHas('estado', function ($query) {
                $query->where('codigo', 'VIGENTE');
            })->orderBy('tipo_carrera_id')->orderBy('nombre')->get();

        $oferta = Oferta::with('semestre', 'carreraUnidadAcademica')
            ->where('semestre_id', $semestre->id)->get();
        $ofertadas = [];
        foreach ($oferta as $o) {
            $ofertadas[] = $o->carreraUnidadAcademica->id;
        }
        $items = [];
        foreach ($carreras as $c) {

            $unidades = $c->unidadesAcademicas;

            $children = [];
            foreach ($unidades as $u) {
                $children[] = [
                    'id' => $u->id,
                    'tipo' => 'unidad',
                    'nombreCompleto' => $u->unidadAcademica->nombreCompleto,
                    'semestre' => $u->semestre,
                    'ofertada' => in_array($u->id, $ofertadas)
                ];
            }
            $items[] = ['id' => 'carr' . $c->id, 'tipo' => 'carrera', 'nombreCompleto' => $c->nombreCompleto . '(' . count($children) . ')', 'children' => $children];
        }
        return response()->json(['status' => 'ok', 'message' => '_datos_guardados_', 'items' => $items, 'oferta' => $oferta]);
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
            $semestreCheck = Oferta::where('codigo', $request->get('codigo'))->first();
            if ($semestreCheck === null) {
                $semestre = new Oferta();

                //Crear el calendario de actividades
                $tipoCalendario = TipoCalendarizacion::where('codigo', 'SEMESTRE')->first();
                $calendarizacion = new Calendarizacion();
                $calendarizacion->codigo = substr($request->get('codigo'), 0, 50); //llevará el mismo nombre que la convocatoria
                $calendarizacion->tipo()->associate($tipoCalendario);
                $calendarizacion->save();

                //Asociar el calendario a la convocatoria
                $semestre->calendario()->associate($calendarizacion);
            } else {
                return response()->json(['status' => 'error', 'message' => 'semestre._codigo_ya existe_']);
            }
        } else {
            // Verificar que el nuevo código que ponga no esté utilizado por otro registro
            $semestreCheck = Oferta::where('codigo', $request->get('codigo'))
                ->where('id', '!=', $request->get('id'))
                ->first();

            if ($semestreCheck === null) {
                $semestre = Oferta::find($request->get('id'));
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
        $item = Oferta::with('creator', 'updater')->find($semestre->id);

        return response()->json(['status' => 'ok', 'message' => '_datos_guardados_', 'item' => $item]);
    }

    public function delete(int $id)
    {
        $delete = Oferta::destroy($id);

        if ($delete == 0) {
            return response()->json(['status' => 'error', 'message' => '_no_se_encontro_registro_']);
        } else {
            return response()->json(['status' => 'ok', 'message' => $id]);
        }
    }
}
