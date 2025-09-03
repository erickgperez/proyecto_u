<?php

namespace App\Http\Controllers\Ingreso;

use App\Http\Controllers\Controller;
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

        return Inertia::render('ingreso/Convocatoria', []);
    }

    public function save(Request $request)
    {
        // Aunque se ha validado del lado del cliente, validar aquí también
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'string|max:255',
            'fecha' => 'required|date',
            'cuerpo_mensaje' => 'string',
            'afiche' => 'file|mimes:pdf',
        ]);


        return response()->json(['message' => 'Cambio realizado']);
    }
}
