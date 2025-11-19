<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Models\Academico\CarreraSede;
use App\Models\Academico\Imparte;
use App\Models\Academico\Oferta;
use App\Models\Academico\Semestre;
use App\Models\PlanEstudio\Carrera;
use App\Models\PlanEstudio\CarreraUnidadAcademica;


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

            //Agregar la oferta por sede
            $carreraUnidadAcademica = CarreraUnidadAcademica::find($idCarreraUnidadAcademica);
            $carrerasSedes = CarreraSede::where('carrera_id', $carreraUnidadAcademica->carrera_id)->get();
            foreach ($carrerasSedes as $cs) {
                $imparte = new Imparte();
                $imparte->carreraSede()->associate($cs);
                $imparte->oferta()->associate($oferta);
                $imparte->ofertada = true;
                $imparte->cupo = $cs->cupo;

                $imparte->save();
            }
        }

        return response()->json(['status' => 'ok', 'message' => '']);
    }

    public function getOfertaDetalle($id, $idCarreraUnidadAcademica)
    {
        $oferta = Oferta::where('semestre_id', $id)->where('carrera_unidad_academica_id', $idCarreraUnidadAcademica)->first();
        $imparte = Imparte::where('oferta_id', $oferta->id)->get();

        $detalleOferta = [];
        foreach ($imparte as $i) {

            $detalleOferta[] = [
                'id' => $i->id,
                'sede' => $i->carreraSede->sede->nombre,
                'ofertada' => $i->ofertada,
                'cupo' => $i->cupo,
                'docente_id' => $i->docente_id,
                'docentes' => $i->carreraSede->docentes()->with('persona')->get()
            ];
        }

        return response()->json(['status' => 'ok', 'message' => '', 'detalleOferta' => $detalleOferta]);
    }
}
