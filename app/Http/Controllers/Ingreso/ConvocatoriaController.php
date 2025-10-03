<?php

namespace App\Http\Controllers\Ingreso;

use App\Http\Controllers\Controller;
use App\Mail\CandidatoInvitado;
use App\Models\Academica\CarreraSede;
use App\Models\Calendarizacion;
use App\Models\Ingreso\Convocatoria;
use App\Models\Secundaria\DataBachillerato;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class ConvocatoriaController extends Controller
{
    /**
     *
     */
    public function index(Request $request): Response
    {

        $convocatorias = Convocatoria::with('carrerasSedes', 'creator', 'updater')->get();

        $carrerasSedes = DB::table('academico.carrera_sede as cs')
            ->select(
                's.id as sede_id',
                's.nombre as sede_nombre',
                'tc.id as tipo_carrera_id',
                'tc.descripcion as tipo_carrera',
                'cs.id as id',
                'c.nombre as nombre_carrera',
                'c.codigo as codigo_carrera'
            )
            ->join('plan_estudio.carrera as c', 'cs.carrera_id', '=', 'c.id')
            ->join('academico.sede as s', 'cs.sede_id', '=', 's.id')
            ->join('plan_estudio.tipo_carrera as tc', 'c.tipo_carrera_id', '=', 'tc.id')
            ->orderBy('s.nombre')
            ->orderBy('tc.descripcion')
            ->orderBy('c.nombre')
            ->get();
        $sedesCarreras = [];
        foreach ($carrerasSedes as $cs) {
            $sedesCarreras[$cs->sede_id]['id'] = 's-' . $cs->sede_id;
            $sedesCarreras[$cs->sede_id]['title'] = $cs->sede_nombre;
            $sedesCarreras[$cs->sede_id]['childrenn'][$cs->tipo_carrera_id]['id'] = $cs->sede_id . '-' . $cs->tipo_carrera_id;
            $sedesCarreras[$cs->sede_id]['childrenn'][$cs->tipo_carrera_id]['title'] = $cs->tipo_carrera;
            $sedesCarreras[$cs->sede_id]['childrenn'][$cs->tipo_carrera_id]['children'][] = ['id' => $cs->id, 'title' => '(' . $cs->codigo_carrera . ') ' . $cs->nombre_carrera];
        }

        $items = [];
        foreach ($sedesCarreras as $sc) {
            $sc_ = $sc;
            foreach ($sc['childrenn'] as $c) {
                $sc_['children'][] = $c;
            }
            unset($sc_['childrenn']);
            $items[] = $sc_;
        }

        return Inertia::render('ingreso/Convocatoria', ['items' => $convocatorias, 'sedesCarreras' => $items]);
    }

    public function save(Request $request)
    {
        // Aunque se ha validado del lado del cliente, validar aquí también
        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:255',
            'fecha' => 'required|date',
            'cuerpo_mensaje' => 'nullable|string',
            'afiche_file' => 'nullable|file|mimes:pdf',
            'afiche_file' => 'nullable|file|mimes:pdf',
            'carrerasSedes' => 'nullable',
        ]);

        if ($request->get('id') === null) {
            $convocatoria = new Convocatoria();
            //Crear el calendario de actividades
            $calendarizacion = new Calendarizacion();
            $calendarizacion->nombre = $request->get('nombre'); //llevará el mismo nombre que la convocatoria
            $calendarizacion->save();

            //Asociar el calendario a la convocatoria
            $convocatoria->calendario()->associate($calendarizacion);
        } else {
            $convocatoria = Convocatoria::find($request->get('id'));
        }

        $convocatoria->nombre = $request->get('nombre');
        $convocatoria->descripcion = $request->get('descripcion');
        $convocatoria->fecha = $request->get('fecha');
        $convocatoria->cuerpo_mensaje = $request->get('cuerpo_mensaje');
        $convocatoria->carrerasSedes()->sync($request->get('carreras_sedes') ?? []);

        if ($request->hasFile('afiche_file')) {
            $file = $request->file('afiche_file');

            if ($request->get('id') != null) {
                //Verificar si ya tenía un afiche cargado, en ese caso borrarlo para subir el nuevo
                $filePath = $convocatoria->afiche;
                if (Storage::exists($filePath)) {
                    Storage::delete($filePath);
                }
            }

            $path = $file->store('documents/convocatorias');

            $convocatoria->afiche = $path;
        }

        $convocatoria->save();

        $convocatoriaData = Convocatoria::with('carrerasSedes', 'creator', 'updater')->find($convocatoria->id);

        return response()->json(['status' => 'ok', 'message' => '_datos_guardados_', 'convocatoria' => $convocatoriaData]);
    }

    public function delete(int $id)
    {
        $convocatoria = Convocatoria::find($id);

        //Verificar si tiene un afiche cargado, borrarlo en ese caso
        $filePath = $convocatoria->afiche;
        if ($filePath != null && Storage::exists($filePath)) {
            Storage::delete($filePath);
        }
        $delete = Convocatoria::destroy($id);

        if ($delete == 0) {
            return response()->json(['status' => 'error', 'message' => '_no_se_encontro_registro_']);
        } else {
            return response()->json(['status' => 'ok', 'message' => $id]);
        }
    }

    public function enviarInvitacionesPendientes(int $id)
    {
        $convocatoria = Convocatoria::find($id);

        //invitaciones pendientes de envío
        $invitaciones = $convocatoria->invitaciones()->whereNull('fecha_envio_correo')->get();

        foreach ($invitaciones as $inv) {
            $bachiller = DataBachillerato::where('nie', $inv->nie)->first();
            Mail::to($bachiller->correo)->queue(
                new CandidatoInvitado($bachiller, $convocatoria)
            );
            $inv->fecha_envio_correo = new \DateTime();

            $inv->save();
        }

        // Mandar los datos actualizados
        $convocatoriaAct = Convocatoria::select('id', 'nombre', 'descripcion')
            ->where('id', $convocatoria->id)
            ->orderBy('nombre', 'asc')
            ->withCount([
                'invitaciones as invitaciones',
                'invitaciones as invitaciones_pendientes_envio' => function (Builder $query) {
                    $query->whereNull('fecha_envio_correo');
                },
                'invitaciones as invitaciones_aceptadas' => function (Builder $query) {
                    $query->whereNotNull('fecha_aceptacion');
                },
            ])
            ->first();

        return response()->json(['status' => 'ok', 'message' => '', 'convocatoria' => $convocatoriaAct]);
    }


    public function aficheDownload(int $id)
    {
        $convocatoria = Convocatoria::find($id);
        $filePath = $convocatoria->afiche;

        if (Storage::exists($filePath)) {
            return Storage::download($filePath, 'archivo.pdf');
        } else {
            abort(404);
        }
    }

    public function aficheDelete(int $id)
    {
        $convocatoria = Convocatoria::find($id);
        $filePath = $convocatoria->afiche;
        $convocatoria->afiche = null;
        $convocatoria->save();
        if (Storage::exists($filePath)) {
            Storage::delete($filePath);
        }
        return response()->json(['status' => 'ok', 'message' => $id]);
    }
}
