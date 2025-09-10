<?php

namespace App\Http\Controllers\Ingreso;

use App\Http\Controllers\Controller;
use App\Models\Ingreso\Convocatoria;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ConvocatoriaController extends Controller
{
    /**
     *
     */
    public function index(Request $request): Response
    {

        $convocatorias = Convocatoria::all();
        return Inertia::render('ingreso/Convocatoria', ['items' => $convocatorias]);
    }

    public function save(Request $request)
    {
        // Aunque se ha validado del lado del cliente, validar aquí también
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:255',
            'fecha' => 'required|date',
            'cuerpo_mensaje' => 'nullable|string',
            'afiche' => 'nullable|file|mimes:pdf',
        ]);

        if ($request->get('id') === null) {
            $convocatoria = new Convocatoria();
        } else {
            $convocatoria = Convocatoria::find($request->get('id'));
        }

        $convocatoria->nombre = $validatedData['nombre'];
        $convocatoria->descripcion = $validatedData['descripcion'];
        $convocatoria->fecha = $validatedData['fecha'];
        $convocatoria->cuerpo_mensaje = $validatedData['cuerpo_mensaje'];

        if ($request->hasFile('afiche')) {
            $file = $request->file('afiche');

            $path = $file->store('documents/convocatorias');

            $convocatoria->afiche = $path;
        }

        $convocatoria->save();

        return response()->json(['status' => 'ok', 'message' => 'Datos guardados']);
    }

    public function delete(int $id)
    {
        $delete = Convocatoria::destroy($id);

        if ($delete == 0) {
            return response()->json(['status' => 'error', 'message' => '_no_se_encontro_registro_']);
        } else {
            return response()->json(['status' => 'ok', 'message' => $id]);
        }
    }
}
