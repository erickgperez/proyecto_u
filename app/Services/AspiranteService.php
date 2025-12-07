<?php

namespace App\Services;

use App\Models\Academico\Estado;
use App\Models\Academico\Estudiante;
use App\Models\Academico\EstudianteCarreraSede;
use App\Models\Estudio;
use App\Models\Ingreso\Aspirante;
use App\Models\Ingreso\ConvocatoriaAspirante;
use App\Models\Persona;
use App\Models\PlanEstudio\Grado;
use App\Models\Secundaria\Carrera;
use App\Models\Secundaria\DataBachillerato;
use App\Models\Secundaria\Institucion;
use App\Models\Secundaria\Invitacion;
use App\Models\Sexo;
use App\Models\User;
use App\Models\Workflow\Solicitud;

class AspiranteService
{
    public function createFromUser(User $user)
    {
        // Asignarle el rol de aspirante
        $user->assignRole('aspirante');

        // Agregar sus datos a persona
        $dataBach = DataBachillerato::where('nie', $user->name)->first();
        $persona = Persona::create([
            'primer_nombre' => $dataBach->primer_nombre,
            'segundo_nombre' => $dataBach->segundo_nombre,
            'tercer_nombre' => $dataBach->tercer_nombre,
            'primer_apellido' => $dataBach->primer_apellido,
            'segundo_apellido' => $dataBach->segundo_apellido,
            'tercer_apellido' => $dataBach->tercer_apellido,
        ]);
        $persona->save();

        $user->name = $persona->primer_nombre . ' ' . $persona->primer_apellido;
        $user->save();


        $sexoCodigo = (in_array($dataBach->sexo, ['Hombre', 'Masculino', 'M', 'm', 'H', 'h'])) ? 'M' : 'F';
        $sexo = Sexo::where('codigo', $sexoCodigo)->first();

        $persona->sexo()->associate($sexo);

        //Asociar el usuario a la persona
        $persona->usuarios()->syncWithoutDetaching([$user->id]);

        //Registrar la carrera de bachillerato como estudio de la persona
        $gradoBach = Grado::where('codigo', '01')->first(); //Grado de bachiller
        $estudio = Estudio::create([
            'nombre_titulo' => $dataBach->opcion_bachillerato,
            'nombre_institucion' => $dataBach->nombre_centro_educativo,
        ]);
        $estudio->grado()->associate($gradoBach);

        //Buscar la opción de bachillerato y la institución
        $carreraBach = Carrera::where('descripcion', $dataBach->opcion_bachillerato)->first();
        $institucionBach = Institucion::where('codigo', $dataBach->codigo_ce)->first();

        $estudio->carrera()->associate($carreraBach);
        $estudio->institucion()->associate($institucionBach);
        $estudio->save();

        $persona->estudios()->save($estudio);
        $persona->save();

        //Crear el aspirante
        $aspirante = Aspirante::create([
            'persona_id' => $persona->id,
            'nie' => $dataBach->nie
        ]);

        //Verificar si ya hay nota cargada
        if ($dataBach->calificacion_bachillerato) {
            $aspirante->calificacion_bachillerato = $dataBach->calificacion_bachillerato;
        }
        $aspirante->save();

        //Buscar la invitación para ponerla como aceptada
        $invitacion = Invitacion::where('nie', $user->name)->orderBy('created_at', 'desc')->first();
        if ($invitacion) {
            $invitacion->fecha_aceptacion = new \DateTime();
            $invitacion->save();

            //Asignar el aspirante a la convocatoria a la que fue invitado
            $aspirante->convocatorias()->syncWithoutDetaching([$invitacion->convocatoria_id]);
            $aspirante->save();
        }

        return $aspirante;
    }

    public function aplicarAceptarSeleccion($id)
    {
        $solicitud = Solicitud::with([
            'solicitante',
            'etapa',
            'estado',
            'modelo'
        ])->where('id', $id)->first();


        $convocatoriaAspirante = ConvocatoriaAspirante::with('solicitudCarreraSede')
            ->whereBelongsTo($solicitud->solicitante)
            ->whereBelongsTo($solicitud->modelo)
            ->first();

        $persona = $convocatoriaAspirante->aspirante->persona;
        $convocatoria = $convocatoriaAspirante->convocatoria;
        $sede = $convocatoriaAspirante->solicitudCarreraSede->carreraSede->sede;
        //Crear el registro de estudiante
        $estudiante = new Estudiante();
        $estudiante->persona_id = $persona->id;
        //Generar el carnet para el estudiante
        $estudianteService = new EstudianteService();
        $estudiante->carnet = $estudianteService->generateCarnet($persona, $convocatoria->anio_ingreso, $sede->codigo);
        $estudiante->save();

        //Agregar la carrera
        $estado = Estado::where('codigo', 'ESTUDIANTE')->first();
        $estudianteCarreraSede = new EstudianteCarreraSede();
        $estudianteCarreraSede->estudiante()->associate($estudiante);
        $estudianteCarreraSede->carreraSede()->associate($convocatoriaAspirante->solicitudCarreraSede->carreraSede);
        $estudianteCarreraSede->fecha_inicio = new \DateTime();
        $estudianteCarreraSede->estado()->associate($estado);
        $estudianteCarreraSede->save();

        //  Cambiarle rol al usuario        
        $usuario = $persona->usuarios()->role(['aspirante'])->first();
        $usuario->assignRole('estudiante');
        $usuario->removeRole('aspirante');
        //Verificar si no se ha puesto el nombre del usuario
        $usuario->name = $persona->primer_nombre . ' ' . $persona->primer_apellido;
        $usuario->save();

        $solicitud->pasarSiguienteEtapa();
        //$estadoSolicitud = WorkflowEstado::where('codigo', 'APROBADA');
        $solicitud->save();

        //Guardar en el historial de solicitud
        $solicitud->guardarHistorial();
    }
}
