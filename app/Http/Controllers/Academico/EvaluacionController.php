<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Models\Academico\Calificacion;
use App\Models\Academico\Evaluacion;
use App\Models\Academico\Oferta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class EvaluacionController extends Controller
{
    /**
     *
     */
    public function index($uuid): Response
    {

        $user = Auth::user();
        $persona = $user->personas()->first();
        $docente = $persona->docente;
        $oferta = Oferta::where("uuid", $uuid)->with([
            'semestre',
            'docenteTitular' => [
                'persona',
            ],
            'carreraUnidadAcademica' => [
                'unidadAcademica',
                'carrera',
            ],

        ])
            ->where('docente_titular_id', $docente->id) //Validar nuevamente que el docente sea el titular
            ->first();
        $evaluaciones = $oferta->evaluacion()->with('creator', 'updater')->orderBy('fecha')->get();

        return Inertia::render('academico/Docente/Evaluacion', ['items' => $evaluaciones, 'oferta' => $oferta]);
    }

    public function save(Request $request)
    {
        // Aunque se ha validado del lado del cliente, validar aquí también
        $request->validate([
            'codigo' => 'required|string|max:10',
            'descripcion' => 'required|string|max:255',
            'fecha' => 'nullable|date',
            'fecha_limite_ingreso_nota' => 'nullable|date',
            'porcentaje' => 'required|numeric',
            'oferta_uuid' => 'required|string',

        ]);

        $oferta = Oferta::where("uuid", $request->get('oferta_uuid'))->first();

        if ($request->get('id') === null) {
            // Está agregando uno nuevo, verificar que no exista el código para la misma asignatura ofertada
            $evaluacionCheck = Evaluacion::where('codigo', $request->get('codigo'))
                ->where('oferta_id', $oferta->id)
                ->first();
            if ($evaluacionCheck === null) {
                $evaluacion = new Evaluacion();
            } else {
                return response()->json(['status' => 'error', 'message' => 'evaluacion._codigo_ya existe_']);
            }
        } else {
            // Verificar que el nuevo código que ponga no esté utilizado por otro registro para la misma asignatura ofertada
            $evaluacionCheck = Evaluacion::where('codigo', $request->get('codigo'))
                ->where('oferta_id', $oferta->id)
                ->where('id', '!=', $request->get('id'))
                ->first();

            if ($evaluacionCheck === null) {
                $evaluacion = Evaluacion::find($request->get('id'));
            } else {
                return response()->json(['status' => 'error', 'message' => 'area._codigo_ya existe_']);
            }
        }

        $evaluacion->codigo = $request->get('codigo');
        $evaluacion->descripcion = $request->get('descripcion');
        $evaluacion->fecha = $request->get('fecha');
        $evaluacion->fecha_limite_ingreso_nota = $request->get('fecha_limite_ingreso_nota');
        $evaluacion->porcentaje = $request->get('porcentaje');
        $evaluacion->oferta_id = $oferta->id;

        $evaluacion->save();

        //Obtener la información de las relaciones del item recién creado/actualizado
        $item = Evaluacion::with('creator', 'updater')->find($evaluacion->id);

        return response()->json(['status' => 'ok', 'message' => '_datos_guardados_', 'item' => $item]);
    }

    public function delete($id)
    {
        $delete = Evaluacion::where('uuid', $id)->first()->delete();

        if ($delete == 0) {
            return response()->json(['status' => 'error', 'message' => '_no_se_encontro_registro_']);
        } else {
            return response()->json(['status' => 'ok', 'message' => $id]);
        }
    }

    public function registroNotas($uuid)
    {

        $oferta = Oferta::where("uuid", $uuid)->with([
            'semestre',
            'imparte' => ['inscritosPivot' => ['expediente' => ['estado', 'estudiante' => ['persona']]]],
            'carreraUnidadAcademica' => [
                'unidadAcademica',
                'carrera',
            ],
            'evaluacion'

        ])
            ->first();

        //Revisar que todos los expedientes tengan el registro de la evaluación
        $oferta->imparte()->each(function ($imparte) use ($oferta) {
            $imparte->inscritosPivot()->each(function ($inscrito) use ($oferta) {
                $oferta->evaluacion()->each(function ($evaluacion) use ($inscrito) {
                    //Crear si no existe
                    Calificacion::firstOrCreate([
                        'evaluacion_id' => $evaluacion->id,
                        'inscrito_id' => $inscrito->id,
                    ], [
                        'calificacion' => null,
                    ]);
                });
            });
        });

        //Volver a recuperar los datos, ya que se hicieron cambios
        $oferta = Oferta::where("uuid", $uuid)->with([
            'semestre',
            'imparte' => [
                'inscritosPivot' => [
                    'expediente' => ['estado', 'estudiante' => ['persona']],
                    'calificacion' => ['evaluacion' => function ($query) {
                        $query->orderBy('fecha', 'desc')->orderBy('codigo', 'asc');
                    }]
                ]
            ],
        ])
            ->first();
        $expedientes = [];
        foreach ($oferta->imparte as $imparte) {
            if ($imparte->inscritosPivot->count() > 0) {
                $expedientes[] = $imparte->inscritosPivot;
            }
        }


        return Inertia::render('academico/Docente/RegistroNotas', ['expedientes' => $expedientes, 'oferta' => $oferta]);
    }
}
