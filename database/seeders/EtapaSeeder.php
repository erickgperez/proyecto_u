<?php

namespace Database\Seeders;

use App\Models\Workflow\Flujo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EtapaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('workflow.etapa')->insert([
            [
                'nombre' => 'Selección de proceso y carrera',
                'indicaciones' => 'Seleccione la convocatoria en la que participará, la carrera y sede de su elección. Puede elegir una carrera principal y una alternativa en caso de no ser seleccionado en la principal',
                'flujo_id' => Flujo::where('nombre', 'Proceso de ingreso universitario')->first()->id
            ],
            [
                'nombre' => 'Ingreso de datos de la solicitud',
                'indicaciones' => 'Verifique los datos generales que ya están cargados. Ingrese los que hagan falta',
                'flujo_id' => Flujo::where('nombre', 'Proceso de ingreso universitario')->first()->id
            ],
            [
                'nombre' => 'Documentación',
                'indicaciones' => 'Suba los archivos de los documentos que se le indican a continuación.',
                'flujo_id' => Flujo::where('nombre', 'Proceso de ingreso universitario')->first()->id
            ],
        ]);
    }
}
