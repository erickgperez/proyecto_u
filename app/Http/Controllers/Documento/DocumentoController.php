<?php

namespace App\Http\Controllers\Documento;

use App\Http\Controllers\Controller;
use App\Models\Documento\Archivo;
use App\Models\Documento\Documento;
use App\Models\Persona;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DocumentoController extends Controller
{

    public function documentoSave(Request $request)
    {
        $request->validate([
            'numero'  => 'nullable|string|max:100',
            'archivo_file' => 'required|file|mimes:jpeg,png,bmp,gif,svg,webp,pdf',
            'tipo_id' => ['required', 'integer', Rule::exists('pgsql.documento.tipo', 'id')],
            'persona_id' => ['required', 'integer', Rule::exists('persona', 'id')],
        ]);

        $persona = Persona::find($request->get('persona_id'));

        $file = $request->file('archivo_file');
        $mimeType = $file->getMimeType();
        $fileName = $file->getClientOriginalName();
        $fileSize = $file->getSize();
        $path = $file->store('documents/personas');

        //Crear el documento
        $documento = new Documento();
        $documento->numero = $request->get('numero');
        $documento->tipo_id = $request->get('tipo_id');
        $documento->fecha_emision = $request->get('fecha_emision');
        $documento->fecha_expiracion = $request->get('fecha_expiracion');
        $documento->save();

        $persona->documentos()->syncWithoutDetaching([$documento->id]);


        //Crear el registro para guardar información del archivo
        $archivo = new Archivo();
        $archivo->nombre_original = $fileName;
        $archivo->tipo = $mimeType;
        $archivo->ruta = $path;
        $archivo->tamanio = $fileSize;
        $archivo->save();

        $documento->archivos()->sync([$archivo->id]);

        return response()->json(['status' => 'ok', 'message' => '_datos_guardados_', 'item' => '']);
    }

    public function documentosPersona(int $id)
    {
        $persona = Persona::where('id', $id)->with(['documentos' => ['archivos', 'tipo']])->first();

        $documentos = $persona->documentos;
        return response()->json(['status' => 'ok', 'message' => '_datos_guardados_', 'documentos' => $documentos]);
    }

    public function documentoDescargar(int $id)
    {
        $documento = Documento::find($id);
        $archivo = $documento->archivos[0];
        $filePath = $archivo->ruta;

        $path = storage_path('app/private/' . $filePath);

        if (Storage::exists($filePath)) {
            return response()->file($path, [
                'Content-Disposition' => 'inline; filename="' . $archivo->nombre_original . '"',
                'Content-Type' => Storage::mimeType($archivo->tipo), // Automatically determine MIME type
            ]);
        } else {
            abort(404);
        }
    }
}
