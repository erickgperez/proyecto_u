<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Ingreso\AspiranteController;
use App\Http\Controllers\Workflow\SolicitudIngresoController;
use App\Models\Academico\CarreraSede;
use App\Models\Academico\Estado;
use App\Models\Academico\Estudiante;
use App\Models\Academico\Expediente;
use App\Models\Academico\Imparte;
use App\Models\Academico\Oferta;
use App\Models\Academico\Sede;
use App\Models\Academico\Semestre;
use App\Models\Academico\TipoCurso;
use App\Models\Ingreso\Convocatoria;
use App\Models\Ingreso\ConvocatoriaAspirante;
use App\Models\Rol;
use App\Models\Secundaria\DataBachillerato;
use App\Models\User;
use App\Models\Workflow\Solicitud;
use App\Services\AspiranteService;
use App\Services\SolicitudService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PhpParser\Node\Stmt\TryCatch;

class SimulacionController extends Controller
{
    protected $aspiranteService;
    protected $solicitudService;

    public function __construct(AspiranteService $aspiranteService, SolicitudService $solicitudService)
    {
        $this->aspiranteService = $aspiranteService;
        $this->solicitudService = $solicitudService;
    }

    /**
     *
     */
    public function index($cantidad)
    {

        ini_set('max_execution_time', 3000);

        if (Auth::user()->hasRole('super-admin')) {

            $rolAspirante = Rol::where('name', 'aspirante')->first();

            echo 'Simulacion de datos pre-seleccion';

            //Verificar que se haya cargado la base de bachilleres
            $bachilleres = DataBachillerato::inRandomOrder()->whereNotNull('correo')->limit($cantidad)->get();

            if ($bachilleres->isEmpty()) {
                echo '<BR><BR>No se ha cargado la base de bachilleres, primero suba el archivo en Ingreso->convocatoria->cargar archivo';
            } else {
                foreach ($bachilleres as $row) {
                    $carrera_sede = [];
                    //Elegir una sede al azar
                    $sede = Sede::inRandomOrder()->first();

                    //Elegir al azar 3 carreras de esa sede
                    // La primera que sea una carrera técnica
                    $carreraSedeT = CarreraSede::inRandomOrder()
                        ->select('carrera_sede.*')
                        ->join('plan_estudio.carrera as carrera', 'carrera_sede.carrera_id', '=', 'carrera.id')
                        ->whereBelongsTo($sede)
                        ->where('carrera.tipo_carrera_id', 1)
                        ->first();

                    // Las otras 2 carreras que sean técnicas o certificaciones, pero diferentes a la primera
                    $carreras = CarreraSede::inRandomOrder()
                        ->whereBelongsTo($sede)
                        ->where('carrera_sede.id', '!=', $carreraSedeT->id)
                        ->take(2)->get();

                    array_push($carrera_sede, $carreraSedeT->id);
                    foreach ($carreras as $c) {
                        array_push($carrera_sede, $c->id);
                    }

                    try {

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
                            $aspirante = $this->aspiranteService->createFromUser($user);
                        } else {
                            $persona = $user->personas()->first();
                            $aspirante = $persona->aspirante()->first();
                        }

                        // Recargar los datos de usuario, por los cambios que se hayan podido hacer
                        // al crear las cuentas
                        $user->fresh();

                        //Crear la solicitud de ingreso
                        $solicitudC = new SolicitudIngresoController($this->solicitudService);
                        //Verificar si ya tiene una
                        $solicitud = $aspirante->solicitudes()->with('estado', 'etapa')
                            ->orderBy('created_at', 'desc')
                            ->first();
                        if (!$solicitud) {
                            // No existe la solicitud, crearla
                            //Recuperar la convocatoria de prueba
                            $convocatoria = Convocatoria::where('nombre', '01')->where('anio_ingreso', '2026')->first();

                            $resp = $solicitudC->solicitudCrear($aspirante->id, $convocatoria->id);
                            $data = json_decode($resp->getContent(), true);

                            $solicitud = Solicitud::with('estado', 'etapa')->find($data['solicitud']['id']);
                        }



                        //Crear la seleccion
                        $data = [
                            'sede_id' => $sede->id,
                            'carrera_sede' => $carrera_sede,
                        ];

                        $request = Request::create(route('workflow-ingreso-solicitud-seleccion-carrera', ['id' => $solicitud->id]), 'POST', $data);
                        $solicitudC->seleccionCarrera($solicitud->id, $request);
                    } catch (\Throwable $e) {
                        dump($e);
                    }
                }
                echo '<BR><BR> Se han cargado todos los datos de la simulación. Ahora ya se puede realizar la selección de aspirantes';
            }
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function aceptarSeleccion()
    {
        //obtener convocatoria aspirantes que están seleccionados
        $convocatoriaAspirantes = ConvocatoriaAspirante::where('seleccionado', true)->get();

        foreach ($convocatoriaAspirantes as $convocatoriaAspirante) {
            $persona = $convocatoriaAspirante->aspirante->persona;
            //Verificar si la persona tiene un registro de estudiante creado
            $estudiante = Estudiante::where('persona_id', $persona->id)->first();
            if (!$estudiante) {
                //Aplicar la aceptación de la seleccion
                $aspiranteService = new AspiranteService();
                $aspiranteService->aplicarAceptarSeleccion($convocatoriaAspirante->solicitudCarreraSede->solicitud->id);
            }

            //Inscribirlo en las asignaturas de primer semestre
            $carreraSede = $convocatoriaAspirante->solicitudCarreraSede->carreraSede;
            $carrera = $carreraSede->carrera;

            $carreraUnidadesAcademicas = $carrera->unidadesAcademicas()->where('semestre', 1)->get();
            $tipoCurso = TipoCurso::where('codigo', 'NM')->first();
            $estado = Estado::where('codigo', 'EC')->first();

            $carreraUnidadesAcademicas->each(function ($carreraUnidadAcademica) use ($tipoCurso, $estado, $estudiante, $carreraSede) {
                $semestre = Semestre::firstOrCreate([
                    'codigo' => '01',
                    'anio' => '2026',
                ]);
                $oferta = Oferta::firstOrCreate([
                    'carrera_unidad_academica_id' => $carreraUnidadAcademica->id,
                    'semestre_id' => $semestre->id,
                ]);

                $expediente = Expediente::firstOrCreate([
                    'estudiante_id' => $estudiante->id,
                    'carrera_unidad_academica_id' => $carreraUnidadAcademica->id,
                    'semestre_id' => $semestre->id,
                ], [
                    'tipo_curso_id' => $tipoCurso->id,
                    'estado_id' => $estado->id,
                    'matricula' => 1,
                ]);

                $imparte = Imparte::firstOrCreate([
                    'oferta_id' => $oferta->id,
                    'carrera_sede_id' => $carreraSede->id,
                ], [
                    'ofertada' => true,
                    'cupo' => 50
                ]);
                $expediente->inscritos()->syncWithoutDetaching([$imparte->id]);
            });
        }
    }
}
