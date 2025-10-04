<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Models\Calendarizacion;
use App\Models\Ingreso\Convocatoria;
use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class PersonaController extends Controller
{
    /**
     *
     */
    public function index(Request $request): Response
    {

        $personas = Persona::with('sexo')->get();


        return Inertia::render('administracion/Persona', ['items' => $personas]);
    }

    public function save(Request $request)
    {
        // Aunque se ha validado del lado del cliente, validar aquí también
        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:255',
            'fecha' => 'required|date',
            'cuerpo_mensaje' => 'nullable|string',
            'afiche_file' => 'nullable|file|mimes:pdf',
            'afiche_file' => 'nullable|file|mimes:pdf',
            'carrerasSedes' => 'nullable',
        ]);

        if ($request->get('id') === null) {
            $convocatoria = new Convocatoria();
            //Crear el calendario de actividades
            $calendarizacion = new Calendarizacion();
            $calendarizacion->nombre = $request->get('nombre'); //llevará el mismo nombre que la convocatoria
            $calendarizacion->save();

            //Asociar el calendario a la convocatoria
            $convocatoria->calendario()->associate($calendarizacion);
        } else {
            $convocatoria = Convocatoria::find($request->get('id'));
        }

        $convocatoria->nombre = $request->get('nombre');
        $convocatoria->descripcion = $request->get('descripcion');
        $convocatoria->fecha = $request->get('fecha');
        $convocatoria->cuerpo_mensaje = $request->get('cuerpo_mensaje');
        $convocatoria->carrerasSedes()->sync($request->get('carreras_sedes') ?? []);

        if ($request->hasFile('afiche_file')) {
            $file = $request->file('afiche_file');

            if ($request->get('id') != null) {
                //Verificar si ya tenía un afiche cargado, en ese caso borrarlo para subir el nuevo
                $filePath = $convocatoria->afiche;
                if (Storage::exists($filePath)) {
                    Storage::delete($filePath);
                }
            }

            $path = $file->store('documents/convocatorias');

            $convocatoria->afiche = $path;
        }

        $convocatoria->save();

        $convocatoriaData = Convocatoria::with('carrerasSedes', 'creator', 'updater')->find($convocatoria->id);

        return response()->json(['status' => 'ok', 'message' => '_datos_guardados_', 'convocatoria' => $convocatoriaData]);
    }

    public function delete(int $id)
    {
        $convocatoria = Convocatoria::find($id);

        //Verificar si tiene un afiche cargado, borrarlo en ese caso
        $filePath = $convocatoria->afiche;
        if ($filePath != null && Storage::exists($filePath)) {
            Storage::delete($filePath);
        }
        $delete = Convocatoria::destroy($id);

        if ($delete == 0) {
            return response()->json(['status' => 'error', 'message' => '_no_se_encontro_registro_']);
        } else {
            return response()->json(['status' => 'ok', 'message' => $id]);
        }
    }
}
