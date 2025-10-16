<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Ingreso\AspiranteController;
use App\Models\Rol;
use App\Models\Secundaria\DataBachillerato;
use App\Models\User;
use App\Models\Workflow\Solicitud;
use App\Services\AspiranteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SimulacionController extends Controller
{
    protected $aspiranteService;

    public function __construct(AspiranteService $aspiranteService)
    {
        $this->aspiranteService = $aspiranteService;
    }

    /**
     *
     */
    public function index()
    {

        if (Auth::user()->hasRole('super-admin')) {

            $rolAspirante = Rol::where('name', 'aspirante')->first();
            echo 'Simulacion de datos pre-seleccion';

            //Verificar que se haya cargado la base de bachilleres
            $bachilleres = DataBachillerato::whereNotNull('correo')->limit(10)->get();

            if ($bachilleres->isEmpty()) {
                echo '<BR><BR>No se ha cargado la base de bachilleres, primero suba el archivo en Ingreso->convocatoria->cargar archivo';
            } else {
                foreach ($bachilleres as $row) {
                    //Crear la cuenta de usuario
                    $user = User::firstOrCreate(
                        ['name' => $row['nie']],
                        [
                            'email' => $row['correo'],
                            'password' => Hash::make('password')
                        ],

                    );

                    //Crear los datos de aspirante
                    if (!$user->hasVerifiedEmail()) {
                        $user->markEmailAsVerified();
                        $this->aspiranteService->createFromUser($user);
                    }

                    // Recargar los datos de usuario, por los cambios que se hayan podido hacer
                    // al crear las cuentas
                    $user->fresh();

                    $persona = $user->personas()->first();

                    //Crear la solicitud de ingreso
                    //Verificar si ya tiene una
                    $solicitud = $persona->solicitudes()->with('estado', 'etapa', 'persona')
                        ->where('rol_id', $rolAspirante->id)
                        ->orderBy('created_at', 'desc')
                        ->first();
                    if (!$solicitud) {
                        $aspiranteC = new AspiranteController();
                        $aspiranteC->solicitudCrear($persona->id);
                    }
                    //Volver a recuperar para actualizar datos
                    $solicitud = $persona->solicitudes()->with('estado', 'etapa', 'persona')
                        ->where('rol_id', $rolAspirante->id)
                        ->orderBy('created_at', 'desc')
                        ->first();

                    //Crear la seleccion
                    $data = [
                        'convocatoria_id' => 'value1',
                        'carrera_sede' => 'value2',
                    ];

                    //$request = Request::create(route('ingreso-solicitud-seleccion-carrera', ['id' => $solicitud->id]), 'POST', $data);
                }
            }
        } else {
            abort(403, 'Unauthorized action.');
        }
    }
}
