<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Ingreso\AspiranteController;
use App\Models\Academica\CarreraSede;
use App\Models\Academica\Sede;
use App\Models\Ingreso\Convocatoria;
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
            //Elegir una sede al azar
            $sede = Sede::inRandomOrder()->first();

            //Elegir al azar 3 carreras de esa sede
            $carreras = CarreraSede::inRandomOrder()->where('sede_id', $sede->id)->take(3)->get();

            echo 'Simulacion de datos pre-seleccion';

            //Verificar que se haya cargado la base de bachilleres
            $bachilleres = DataBachillerato::whereNotNull('correo')->limit(1000)->get();

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
                    $aspiranteC = new AspiranteController();
                    //Verificar si ya tiene una
                    $solicitud = $persona->solicitudes()->with('estado', 'etapa', 'persona')
                        ->where('rol_id', $rolAspirante->id)
                        ->orderBy('created_at', 'desc')
                        ->first();
                    if (!$solicitud) {
                        $aspiranteC->solicitudCrear($persona->id);
                    }
                    //Volver a recuperar para actualizar datos
                    $solicitud->fresh();

                    //Recuperar la convocatoria de prueba
                    $convocatoria = Convocatoria::where('nombre', '01-2026')->first();
                    $carrera_sede = [];
                    foreach ($carreras as $c) {
                        $carrera_sede[] = $c->id;
                    }

                    //Crear la seleccion
                    $data = [
                        'convocatoria_id' => $convocatoria->id,
                        'carrera_sede' => $carrera_sede,
                    ];

                    $request = Request::create(route('ingreso-solicitud-seleccion-carrera', ['id' => $solicitud->id]), 'POST', $data);
                    $aspiranteC->seleccionCarrera($solicitud->id, $request);

                    echo '<BR><BR> Se han cargado todos los datos de la simulación. Ahora ya se puede realizar la selección de aspirantes';
                }
            }
        } else {
            abort(403, 'Unauthorized action.');
        }
    }
}
