<?php

namespace Database\Seeders\Workflow;

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
                'codigo' => 'SELECCION_PROCESO_CARRERA',
                'nombre' => 'Selección de proceso y carrera',
                'indicaciones' => 'Seleccione la convocatoria en la que participará, la carrera y sede de su elección. Puede elegir una carrera principal y una alternativa en caso de no ser seleccionado en la principal',
                'flujo_id' => Flujo::where('codigo', 'INGRESO_01')->first()->id
            ],
            [
                'codigo' => 'SELECCION_ASPIRANTE',
                'nombre' => 'Selección de estudiante',
                'indicaciones' => 'Su solicitud se ha creado, y se está pendiente del proceso de selección. En caso de ser seleccionado/a como estudiante, se le enviará un correo electrónico notificándole.',
                'flujo_id' => Flujo::where('codigo', 'INGRESO_01')->first()->id
            ]
        ]);
    }
}
