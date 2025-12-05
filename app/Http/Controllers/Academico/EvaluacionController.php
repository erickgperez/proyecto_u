<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Models\Academico\Calificacion;
use App\Models\Academico\Docente;
use App\Models\Academico\Evaluacion;
use App\Models\Academico\Imparte;
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

        $imparte = Imparte::where("uuid", $uuid)->with([
            'oferta' => [
                'carreraUnidadAcademica' => [
                    'unidadAcademica',
                    'carrera',
                ],
                'evaluacion'
            ],
            'inscritosPivot' => ['expediente' => ['estado', 'estudiante' => ['persona']]],

        ])
            ->first();

        $evaluaciones = [];
        foreach ($imparte->oferta->evaluacion as $ev) {
            $evaluaciones[] = [
                'key' => $ev->uuid,
                'codigo' => $ev->codigo,
                'descripcion' => $ev->descripcion,
                'ponderacion' => $ev->porcentaje,
                'visible' => true,
                'fecha' => $ev->fecha,
                'fecha_limite_ingreso_nota' => $ev->fecha_limite_ingreso_nota,
                'editable' => ($ev->fecha > now() && $ev->fecha_limite_ingreso_nota < now()),
                'tooltipVisible' => false,
            ];
        }

        //Ordenar primero por fecha y luego por código
        usort($evaluaciones, function ($a, $b) {
            return ($a['fecha'] <=> $b['fecha']) ?: ($a['codigo'] <=> $b['codigo']);
        });


        //Revisar que todos los expedientes tengan el registro de la evaluación
        $expedientes = [];
        $imparte->inscritosPivot()->each(function ($inscrito) use ($imparte, &$expedientes) {
            $expediente = [
                'id' => $inscrito->id,
                'nombre' => $inscrito->expediente->estudiante->persona->apellidos . ', ' . $inscrito->expediente->estudiante->persona->nombre,
                'carnet' => $inscrito->expediente->estudiante->carnet,
            ];
            $imparte->oferta->evaluacion()->each(function ($evaluacion) use ($inscrito, &$expediente) {
                //Crear si no existe
                $calificacion = Calificacion::firstOrCreate([
                    'evaluacion_id' => $evaluacion->id,
                    'inscrito_id' => $inscrito->id,
                ], [
                    'calificacion' => null,
                ]);

                $expediente[$evaluacion->uuid] = $calificacion->calificacion;
            });
            $expedientes[] = $expediente;
        });

        //ordenar por nombre
        usort($expedientes, function ($a, $b) {
            return $a['nombre'] <=> $b['nombre'];
        });

        return Inertia::render('academico/Docente/RegistroNotas', ['expedientes' => $expedientes, 'evaluaciones' => $evaluaciones]);
    }
}
