<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Calendarizacion;
use App\Models\Evento;
use Illuminate\Http\Request;

class EventoController extends Controller
{
    /**
     *
     */
    public function index(int $id)
    {
        $calendario = Calendarizacion::find($id);

        $eventos = $calendario->eventos()->orderBy('fecha_inicio', 'ASC')->get();

        $_eventos = [];
        $step = 1;
        foreach ($eventos as $row) {
            $evento = $row;
            $evento['step'] = $step;
            $evento['icon'] = $step <= 9 ? 'mdi-numeric-' . $step : 'mdi-numeric-9-plus';
            $_eventos[] = $evento;
        }

        return response()->json(['status' => 'ok', 'message' => '_datos_guardados_', 'items' => $_eventos]);
    }

    public function save(int $id, Request $request)
    {
        // Aunque se ha validado del lado del cliente, validar aquí también
        $request->validate([
            'nombre' => 'required|string|max:50',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date',
            'indicaciones' => 'string',
        ]);

        $calendario = Calendarizacion::find($id);

        if ($request->get('id') === null) {
            $evento = new Evento();
        } else {
            $evento = Evento::find($request->get('id'));
        }

        $evento->nombre = $request->get('nombre');
        $evento->fecha_inicio = $request->get('fecha_inicio');
        $evento->fecha_fin = $request->get('fecha_fin');
        $evento->indicaciones = $request->get('indicaciones');
        $evento->calendario()->associate($calendario);

        $evento->save();

        //Obtener todos los items del calendario para volver a dibujar la interfaz
        $eventos = $calendario->eventos()->orderBy('fecha_inicio', 'ASC')->get();

        return response()->json(['status' => 'ok', 'message' => '_datos_guardados_', 'items' => $eventos]);
    }

    public function delete(int $id)
    {
        $evento = Evento::find($id);
        $calendario = $evento->calendario()->first();

        $delete = Evento::destroy($id);

        $eventos = $calendario->eventos()->orderBy('fecha_inicio', 'ASC')->get();

        if ($delete == 0) {
            return response()->json(['status' => 'error', 'message' => '_no_se_encontro_registro_']);
        } else {
            return response()->json(['status' => 'ok', 'message' => '_evento_borrado_',  'items' => $eventos]);
        }
    }
}
