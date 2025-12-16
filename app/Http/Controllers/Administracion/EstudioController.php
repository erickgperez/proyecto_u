<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Models\Estudio;
use App\Models\Persona;
use Illuminate\Http\Request;

class EstudioController extends Controller
{
    /**
     *
     */
    public function index($uuid, Request $request)
    {

        $persona = Persona::where('uuid', $uuid)->first();
        $estudios = $persona->estudios()->with('creator', 'updater', 'persona')->orderBy('fecha_finalizacion', 'desc')->get();

        return response()->json(['status' => 'ok', 'message' => '', 'items' => $estudios]);
    }

    public function save($uuid, Request $request)
    {
        // Aunque se ha validado del lado del cliente, validar aquí también
        $request->validate([
            'nombre' => 'required|string|max:255',
            'institucion' => 'required|string|max:255',
            'fecha_finalizacion' => 'required|date',
        ]);

        $persona = Persona::where('uuid', $uuid)->first();

        if ($request->get('id') === null) {
            $estudio = new Estudio();
            $estudio->persona_id = $persona->id;
        } else {
            $estudio = Estudio::find($request->get('id'));
        }

        $estudio->nombre_titulo = $request->get('nombre');
        $estudio->nombre_institucion = $request->get('institucion');
        $estudio->fecha_finalizacion = $request->get('fecha_finalizacion');

        $estudio->save();


        $item = Estudio::with('creator', 'updater', 'persona')->find($estudio->id);

        return response()->json(['status' => 'ok', 'message' => '_datos_guardados_', 'item' => $item]);
    }

    public function delete($id)
    {

        $delete = Estudio::where('id', $id)->first()->delete();

        if ($delete == 0) {
            return response()->json(['status' => 'error', 'message' => '_no_se_encontro_registro_']);
        } else {
            return response()->json(['status' => 'ok', 'message' => $id]);
        }
    }
}
