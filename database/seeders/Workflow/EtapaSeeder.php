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
                'codigo' => 'SELECCION_CARRERA',
                'nombre' => 'Selección carrera/sede',
                'indicaciones' => 'Seleccione la carrera y sede de su elección. Puede elegir una carrera principal y dos alternativa; estas se usarán en caso de no ser seleccionado en la principal',
                'flujo_id' => Flujo::where('codigo', 'INGRESO_01')->first()->id
            ],
            [
                'codigo' => 'SOLICITUD',
                'nombre' => 'Completar los datos de la solicitud',
                'indicaciones' => 'Verifique los datos a continuación y complete los que hagan falta',
                'flujo_id' => Flujo::where('codigo', 'INGRESO_01')->first()->id
            ],
            [
                'codigo' => 'SELECCION_ASPIRANTE',
                'nombre' => 'Selección de estudiante',
                'indicaciones' => 'Su solicitud se ha creado, y se está pendiente del proceso de selección. En caso de ser seleccionado/a como estudiante, se le enviará un correo electrónico notificándole.',
                'flujo_id' => Flujo::where('codigo', 'INGRESO_01')->first()->id
            ],
            //Ejemplo de etapas del proceso CONFIGURACION_CONVOCATORIA
            [
                'codigo' => 'OFERTA',
                'nombre' => 'Oferta de carreras',
                'indicaciones' => 'Cargue las sedes/carreras que se ofertarán en la convocatoria. Menú Convocatoria->Gestionar convocatoria->Acción: Oferta de carreras',
                'flujo_id' => Flujo::where('codigo', 'CONFIGURACION_CONVOCATORIA')->first()->id
            ],
            [
                'codigo' => 'CONFIGURACION',
                'nombre' => 'Parámetros de configuración',
                'indicaciones' => 'Defina los parámetros de configuración de la convocatoria. Menú Convocatoria->Gestionar convocatoria->Acción: Configuración',
                'flujo_id' => Flujo::where('codigo', 'CONFIGURACION_CONVOCATORIA')->first()->id
            ],
            [
                'codigo' => 'INVITACIONES',
                'nombre' => 'Envio de invitaciones',
                'indicaciones' => 'Seleccione los candidatos a los que se le enviarán invitaciones para participar en la convocatoria. Menú Convocatoria->Candidatos',
                'flujo_id' => Flujo::where('codigo', 'CONFIGURACION_CONVOCATORIA')->first()->id
            ],
            [
                'codigo' => 'CARGA_NOTA_PRUEBA_BACHILLERATO',
                'nombre' => 'Carga de nota de bachillerato',
                'indicaciones' => 'Cargue el archivo con la nota obtenida por los estudiantes en su prueba de bachillerato. Menú Aspirantes->Nota bachillerato',
                'flujo_id' => Flujo::where('codigo', 'CONFIGURACION_CONVOCATORIA')->first()->id
            ],
            [
                'codigo' => 'SELECCION_ASPIRANTES',
                'nombre' => 'Selección de aspirantes',
                'indicaciones' => 'Realizar la selección de aspirantes que serán estudiantes de la universidada. Menú Aspirantes->Selección',
                'flujo_id' => Flujo::where('codigo', 'CONFIGURACION_CONVOCATORIA')->first()->id
            ]
        ]);
    }
}
