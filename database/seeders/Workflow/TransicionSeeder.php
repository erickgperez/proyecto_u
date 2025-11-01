<?php

namespace Database\Seeders\Workflow;

use App\Models\Workflow\Estado;
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
        DB::table('workflow.transicion')->insert([
            [
                'codigo' => 'SELECCIONAR_CARRERA',
                'nombre' => 'Seleccionar carrera',
                'flujo_id' => Flujo::where('codigo', 'INGRESO_01')->first()->id,
                'etapa_origen_id' => Etapa::where('codigo', 'SELECCION_CARRERA')->first()->id,
                'estado_origen_id' => Estado::where('codigo', 'INICIO')->first()->id,
                'etapa_destino_id' => Etapa::where('codigo', 'SOLICITUD')->first()->id,
                'estado_destino_id' => Estado::where('codigo', 'EN_TRAMITE')->first()->id,

            ],
            [
                'codigo' => 'COMPLETAR_SOLICITUD',
                'nombre' => 'Completar solicitud',
                'flujo_id' => Flujo::where('codigo', 'INGRESO_01')->first()->id,
                'etapa_origen_id' => Etapa::where('codigo', 'SOLICITUD')->first()->id,
                'estado_origen_id' => Estado::where('codigo', 'EN_TRAMITE')->first()->id,
                'etapa_destino_id' => Etapa::where('codigo', 'SELECCION_ASPIRANTE')->first()->id,
                'estado_destino_id' => Estado::where('codigo', 'EN_TRAMITE')->first()->id,
            ],
        ]);
    }
}
