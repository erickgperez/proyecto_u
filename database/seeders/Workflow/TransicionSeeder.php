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

            //Transiciones del proceso CONFIGURACION_CONVOCATORIA
            [
                'codigo' => 'OFERTAR',
                'nombre' => 'Seleccionar sedes/carreras',
                'flujo_id' => Flujo::where('codigo', 'CONFIGURACION_CONVOCATORIA')->first()->id,
                'etapa_origen_id' => Etapa::where('codigo', 'OFERTA')->first()->id,
                'estado_origen_id' => Estado::where('codigo', 'INICIO')->first()->id,
                'etapa_destino_id' => Etapa::where('codigo', 'CONFIGURACION')->first()->id,
                'estado_destino_id' => Estado::where('codigo', 'EN_TRAMITE')->first()->id,

            ],
            [
                'codigo' => 'CONFIGURAR',
                'nombre' => 'Configurar parámetros',
                'flujo_id' => Flujo::where('codigo', 'CONFIGURACION_CONVOCATORIA')->first()->id,
                'etapa_origen_id' => Etapa::where('codigo', 'CONFIGURACION')->first()->id,
                'estado_origen_id' => Estado::where('codigo', 'EN_TRAMITE')->first()->id,
                'etapa_destino_id' => Etapa::where('codigo', 'CARGA_CANDIDATOS')->first()->id,
                'estado_destino_id' => Estado::where('codigo', 'EN_TRAMITE')->first()->id,

            ],
            [
                'codigo' => 'CARGAR_CANDIDATOS',
                'nombre' => 'Configurar parámetros',
                'flujo_id' => Flujo::where('codigo', 'CONFIGURACION_CONVOCATORIA')->first()->id,
                'etapa_origen_id' => Etapa::where('codigo', 'CARGA_CANDIDATOS')->first()->id,
                'estado_origen_id' => Estado::where('codigo', 'EN_TRAMITE')->first()->id,
                'etapa_destino_id' => Etapa::where('codigo', 'INVITACIONES')->first()->id,
                'estado_destino_id' => Estado::where('codigo', 'EN_TRAMITE')->first()->id,
            ],
            [
                'codigo' => 'INVITAR',
                'nombre' => 'Enviar invitaciones',
                'flujo_id' => Flujo::where('codigo', 'CONFIGURACION_CONVOCATORIA')->first()->id,
                'etapa_origen_id' => Etapa::where('codigo', 'INVITACIONES')->first()->id,
                'estado_origen_id' => Estado::where('codigo', 'EN_TRAMITE')->first()->id,
                'etapa_destino_id' => Etapa::where('codigo', 'CARGA_NOTA_PRUEBA_BACHILLERATO')->first()->id,
                'estado_destino_id' => Estado::where('codigo', 'EN_TRAMITE')->first()->id,
            ],
            [
                'codigo' => 'CARGAR_NOTA',
                'nombre' => 'Enviar invitaciones',
                'flujo_id' => Flujo::where('codigo', 'CONFIGURACION_CONVOCATORIA')->first()->id,
                'etapa_origen_id' => Etapa::where('codigo', 'CARGA_NOTA_PRUEBA_BACHILLERATO')->first()->id,
                'estado_origen_id' => Estado::where('codigo', 'EN_TRAMITE')->first()->id,
                'etapa_destino_id' => Etapa::where('codigo', 'SELECCION_ASPIRANTES')->first()->id,
                'estado_destino_id' => Estado::where('codigo', 'EN_TRAMITE')->first()->id,
            ],
        ]);
    }
}
