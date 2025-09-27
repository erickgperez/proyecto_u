<?php

namespace Database\Seeders;

use App\Models\Workflow\Etapa;
use App\Models\Workflow\Flujo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransicionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('workflow.etapa')->insert([
            [
                'nombre' => 'Seleccionar carrera',
                'flujo_id' => Flujo::where('nombre', 'Proceso de ingreso universitario')->first()->id,
                'etapa_origen_id' => Etapa::where('nombre', 'Selección de proceso y carrera')->first()->id,
                'etapa_destino_id' => Etapa::where('nombre', 'Ingreso de datos de la solicitud')->first()->id,
            ],
            [
                'nombre' => 'Completar solicitud',
                'flujo_id' => Flujo::where('nombre', 'Proceso de ingreso universitario')->first()->id,
                'etapa_origen_id' => Etapa::where('nombre', 'Ingreso de datos de la solicitud')->first()->id,
                'etapa_destino_id' => Etapa::where('nombre', 'Documentación')->first()->id,
            ],
        ]);
    }
}
