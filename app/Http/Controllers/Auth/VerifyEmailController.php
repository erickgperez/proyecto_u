<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Estudio;
use App\Models\Persona;
use App\Models\PlanEstudio\Grado;
use App\Models\Secundaria\DataBachillerato;
use App\Models\Sexo;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false) . '?verified=1');
        }

        if ($request->user()->markEmailAsVerified()) {
            /** @var \Illuminate\Contracts\Auth\MustVerifyEmail $user */
            $user = $request->user();

            $userLogged = event(new Verified($user));

            // ***** Registrar como aspirante
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

            $sexoCodigo = (in_array($dataBach->sexo, ['Hombre', 'Masculino', 'M', 'm', 'H', 'h'])) ? 'M' : 'F';
            $sexo = Sexo::where('codigo', $sexoCodigo);

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

            $persona->estudios()->save($estudio);
        }

        return redirect()->intended(route('dashboard', absolute: false) . '?verified=1');
    }
}
