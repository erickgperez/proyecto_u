<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Calendarizacion;
use App\Models\Evento;
use App\Models\TipoEvento;
use Illuminate\Http\Request;

class EventoController extends Controller
{
    /**
     *
     */
    public function index(int $id)
    {
        $calendario = Calendarizacion::find($id);

        $tipoEvento = TipoEvento::where('tipo_calendarizacion_id', $calendario->tipo_calendarizacion_id)
            ->orWhereNull('tipo_calendarizacion_id')->get();
        $eventos = $calendario->eventos()->with('tipo')->orderBy('fecha_inicio', 'ASC')->get();

        $_eventos = $this->eventosExtraInfo($eventos);


        return response()->json(['status' => 'ok', 'message' => '_datos_guardados_', 'items' => $_eventos, 'calendario' => $calendario, 'tipoEvento' => $tipoEvento]);
    }

    public function save(int $id, Request $request)
    {
        // Aunque se ha validado del lado del cliente, validar aquí también
        $request->validate([
            'nombre' => 'nullable|string|max:50',
            'tipo_evento_id' => 'required|numeric',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date',
            'indicaciones' => 'nullable|string',
        ]);

        $calendario = Calendarizacion::find($id);

        if ($request->get('id') === null) {
            $evento = new Evento();
        } else {
            $evento = Evento::find($request->get('id'));
        }

        $evento->nombre = $request->get('nombre');
        $evento->tipo_evento_id = $request->get('tipo_evento_id');
        $evento->fecha_inicio = $request->get('fecha_inicio');
        $evento->fecha_fin = $request->get('fecha_fin');
        $evento->indicaciones = $request->get('indicaciones');
        $evento->calendario()->associate($calendario);

        $evento->save();

        //Obtener todos los items del calendario para volver a dibujar la interfaz
        $eventos = $calendario->eventos()->with('tipo')->orderBy('fecha_inicio', 'ASC')->get();
        $_eventos = $this->eventosExtraInfo($eventos);

        return response()->json(['status' => 'ok', 'message' => '_datos_guardados_', 'items' => $_eventos]);
    }

    public function delete($id)
    {
        $evento = Evento::where('uuid', $id)->first();
        $calendario = $evento->calendario()->first();

        $delete = $evento->delete();

        $eventos = $calendario->eventos()->with('tipo')->orderBy('fecha_inicio', 'ASC')->get();

        if ($delete == 0) {
            return response()->json(['status' => 'error', 'message' => '_no_se_encontro_registro_']);
        } else {
            return response()->json(['status' => 'ok', 'message' => '_evento_borrado_',  'items' => $eventos]);
        }
    }

    protected function eventosExtraInfo($eventos): array
    {
        $_eventos = [];
        $step = 1;
        foreach ($eventos as $row) {
            $evento = $row;
            $evento['step'] = $step;
            $evento['name'] = $row->nombre ?? $row->tipo->descripcion;
            $evento['allDay'] = false;
            $evento['icon'] = $step <= 9 ? 'mdi-numeric-' . $step : 'mdi-numeric-9-plus';
            $_eventos[] = $evento;
            $step++;
        }

        return $_eventos;
    }
}
