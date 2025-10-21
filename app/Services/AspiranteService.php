<?php

namespace App\Services;

use App\Models\Estudio;
use App\Models\Ingreso\Aspirante;
use App\Models\Persona;
use App\Models\PlanEstudio\Grado;
use App\Models\Secundaria\Carrera;
use App\Models\Secundaria\DataBachillerato;
use App\Models\Secundaria\Institucion;
use App\Models\Secundaria\Invitacion;
use App\Models\Sexo;
use App\Models\User;

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
}
