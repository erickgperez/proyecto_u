<?php

namespace App\Http\Controllers\Academica;

use App\Http\Controllers\Controller;
use App\Models\PlanEstudio\Area;
use App\Models\PlanEstudio\Carrera;
use App\Models\PlanEstudio\CarreraUnidadAcademica;
use App\Models\PlanEstudio\Requisitos;
use App\Models\PlanEstudio\TipoRequisito;
use App\Models\PlanEstudio\UnidadAcademica;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class MallaCurricularController extends Controller
{
    /**
     *
     */
    public function index(): Response
    {

        $items = CarreraUnidadAcademica::with('creator', 'updater', 'area', 'unidadAcademica', 'carrera')
            ->orderBy('carrera_id')
            ->orderBy('semestre')
            ->get();
        $areas = Area::orderBy('codigo')->get();
        $unidadesAcademicas = UnidadAcademica::orderBy('nombre')->get();
        $carreras = Carrera::orderBy('nombre')->get();

        return Inertia::render('academica/CarreraUnidadAcademica', [
            'items' => $items,
            'areas' => $areas,
            'unidadesAcademicas' => $unidadesAcademicas,
            'carreras' => $carreras
        ]);
    }

    public function save(Request $request)
    {
        // Aunque se ha validado del lado del cliente, validar aquí también
        $request->validate([
            'semestre' => 'required|numeric|min:0',
            'obligatoria' => 'required',
            'requisito_creditos' => 'required|numeric|min:0',
            'carrera_id' => ['required', 'integer', Rule::exists('pgsql.plan_estudio.carrera', 'id')],
            'area_id' => ['nullable', 'integer', Rule::exists('pgsql.plan_estudio.area', 'id')],
            'unidad_academica_id' => ['required', 'integer', Rule::exists('pgsql.plan_estudio.unidad_academica', 'id')],
        ]);

        if ($request->get('id') === null) {
            // Está agregando uno nuevo, verificar que no exista el código
            $carreraUnidadAcademicaCheck = CarreraUnidadAcademica::where('carrera_id', $request->get('carrera_id'))
                ->where('unidad_academica_id', $request->get('unidad_academica_id'))
                ->first();
            if ($carreraUnidadAcademicaCheck === null) {
                $carreraUnidadAcademica = new CarreraUnidadAcademica();
            } else {
                return response()->json(['status' => 'error', 'message' => 'mallaCurricular._codigo_ya existe_']);
            }
        } else {
            // Verificar que el nuevo código que ponga no esté utilizado por otro registro
            $carreraUnidadAcademicaCheck = CarreraUnidadAcademica::where('carrera_id', $request->get('carrera_id'))
                ->where('unidad_academica_id', $request->get('unidad_academica_id'))
                ->where('id', '!=', $request->get('id'))
                ->first();

            if ($carreraUnidadAcademicaCheck === null) {
                $carreraUnidadAcademica = CarreraUnidadAcademica::find($request->get('id'));
            } else {
                return response()->json(['status' => 'error', 'message' => 'mallaCurricular._codigo_ya existe_']);
            }
        }

        $carreraUnidadAcademica->semestre = $request->get('semestre');
        $carreraUnidadAcademica->obligatoria = ($request->get('obligatoria') == 'true');
        $carreraUnidadAcademica->requisito_creditos = $request->get('requisito_creditos');
        $carreraUnidadAcademica->carrera_id = $request->get('carrera_id');
        $carreraUnidadAcademica->area_id = $request->get('area_id');
        $carreraUnidadAcademica->unidad_academica_id = $request->get('unidad_academica_id');
        $carreraUnidadAcademica->save();

        $requisitos = [];
        $tipoPrerrequisito = TipoRequisito::where('codigo', 'PRERREQUISITO')->first();
        $tipoCorrequisito = TipoRequisito::where('codigo', 'CORREQUISITO')->first();
        foreach ($request->get('prerrequisitos') ?? [] as $pr) {
            $requisitos[$pr] = ['tipo_requisito_id' => $tipoPrerrequisito->id];
        }
        foreach ($request->get('correquisitos') ?? [] as $pr) {
            $requisitos[$pr] = ['tipo_requisito_id' => $tipoCorrequisito->id];
        }
        $carreraUnidadAcademica->requisitos()->sync($requisitos);

        //Obtener la información de las relaciones del item recién creado/actualizado
        $item = CarreraUnidadAcademica::with('creator', 'updater', 'area', 'unidadAcademica', 'carrera')->find($carreraUnidadAcademica->id);

        return response()->json(['status' => 'ok', 'message' => '_datos_guardados_', 'item' => $item]);
    }

    public function delete(int $id)
    {
        //Verificar si es requisito de obligatoria
        $requisito = Requisitos::where('carrera_unidad_academica_requisito_id', $id)->first();
        if ($requisito) {
            return response()->json(['status' => 'error', 'message' => 'mallaCurricular._no_borrar_es_requisito_']);
        }

        $delete = CarreraUnidadAcademica::destroy($id);

        if ($delete == 0) {
            return response()->json(['status' => 'error', 'message' => '_no_se_encontro_registro_']);
        } else {
            return response()->json(['status' => 'ok', 'message' => $id]);
        }
    }
}
