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
            })->orderBy('tipo_carrera_id')->orderBy('codigo')->orderBy('nombre')->get();

        $oferta = Oferta::with('semestre', 'carreraUnidadAcademica')
            ->where('semestre_id', $semestre->id)->get();
        $ofertadas = [];
        foreach ($oferta as $o) {
            $ofertadas[] = $o->carreraUnidadAcademica->id;
        }
        $items = [];
        foreach ($carreras as $c) {

            $unidades = $c->unidadesAcademicas()->orderBy('semestre')->get();

            $children = [];
            foreach ($unidades as $u) {
                $children[] = [
                    'id' => $u->id,
                    'tipo' => 'unidad',
                    'nombreCompleto' => '(' . $u->semestre . ') ' . $u->unidadAcademica->nombreCompleto,
                    'semestre' => $u->semestre,
                    'ofertada' => in_array($u->id, $ofertadas)
                ];
            }
            $items[] = ['id' => 'carr' . $c->id, 'tipo' => 'carrera', 'nombreCompleto' => $c->nombreCompleto . '(' . count($children) . ')', 'children' => $children];
        }
        return response()->json(['status' => 'ok', 'message' => '_datos_guardados_', 'items' => $items, 'oferta' => $oferta]);
    }

    public function ofertar($id, $idCarreraUnidadAcademica)
    {


        $oferta = Oferta::where('semestre_id', $id)->where('carrera_unidad_academica_id', $idCarreraUnidadAcademica)->first();

        if ($oferta) {
            $oferta->delete();
        } else {
            $oferta = new Oferta();
            $oferta->semestre_id = $id;
            $oferta->carrera_unidad_academica_id = $idCarreraUnidadAcademica;

            $oferta->save();
        }

        return response()->json(['status' => 'ok', 'message' => '_datos_guardados_']);
    }
}
