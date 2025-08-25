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
}
