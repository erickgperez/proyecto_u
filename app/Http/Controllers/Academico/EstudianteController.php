<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Models\Academico\Estudiante;
use App\Models\Academico\CarreraSede;
use App\Models\Academico\Estado;
use App\Models\Academico\Sede;
use App\Models\Academico\UsoEstado;
use App\Models\PlanEstudio\Carrera;
use App\Models\PlanEstudio\TipoCarrera;
use App\Models\Secundaria\Carrera as SecundariaCarrera;
use App\Services\CarreraSedeService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Persona;

class EstudianteController extends Controller
{

    /**
     *
     */
    public function index(): Response
    {



        return Inertia::render('academico/Carrera', ['items' => $items, 'tiposCarrera' => $tiposCarrera, 'sedes' => $sedes, 'carrerasSecundaria' => $carrerasSecundaria]);
    }



    public function getEstudianteData($uuid)
    {
        $persona = Persona::where('uuid', $uuid)->first();
        $usoEstado = UsoEstado::where('codigo', 'ESTUDIANTE_CARRERA_SEDE')->first();
        $estadoActivo = $usoEstado->estados()->where('codigo', 'ESTUDIANTE')->first();
        $estudiante = $persona->estudiante()
            ->with(['carreraSede' => function ($query) use ($estadoActivo) {
                $query->wherePivot('estado_id', $estadoActivo->id);
            }])
            ->first();
        return response()->json(['status' => 'ok', 'estudiante' => $estudiante]);
    }
}
