<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Models\Academico\Asignado;
use App\Models\Academico\Docente;
use App\Models\Persona;
use Illuminate\Http\Request;


class DocenteController extends Controller
{

    public function data($id, Request $request)
    {

        $persona = Persona::where('uuid', $id)->first();
        $docente = Docente::with('carrerasSedes')
            ->where('persona_id', $persona->id)->first();

        return response()->json(['status' => 'ok', 'message' => '', 'docente' => $docente]);
    }

    public function asignacionSave($id, Request $request)
    {

        $persona = Persona::find($id);
        $docente = $persona->docente;

        if (!$docente) {
            //No existe el registro de docente, hacerlo
            $docente = new Docente();
            $docente->persona()->associate($persona);

            $docente->save();
        }

        $docente->carrerasSedes()->sync($request->get('carreras_sedes') ?? []);

        //Si tiene asignación principal
        if ($request->get('carrera_sede_principal_id')) {
            $asignado = Asignado::where('docente_id', $docente->id)
                ->where('carrera_sede_id', $request->get('carrera_sede_principal_id'))
                ->first();
            $asignado->principal = true;
            $asignado->save();
        }


        return response()->json(['status' => 'ok', 'message' => '_datos_guardados_']);
    }
}
