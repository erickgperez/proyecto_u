<?php

namespace Database\Seeders\PlanEstudio;

use App\Models\Academico\Estado;
use App\Models\PlanEstudio\Carrera;
use App\Models\PlanEstudio\TipoCarrera;
use Illuminate\Database\Seeder;

class Carrera2Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $estadoId = Estado::where('codigo', 'VIGENTE')->first()->id;
        Carrera::insert([
            //Certificaciones de Tecnologías de la Información
            ['codigo' => 'MCABD01', 'certificacion_de' => Carrera::where('codigo', '01')->first()->id, 'nombre' => 'Administración y gestión de bases de datos', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'CERTIFICACION')->first()->id, 'estado_id' => $estadoId],
            ['codigo' => 'MCDFS01', 'certificacion_de' => Carrera::where('codigo', '01')->first()->id, 'nombre' => 'Desarrollador Full Stack', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'CERTIFICACION')->first()->id, 'estado_id' => $estadoId],
            ['codigo' => 'MCSRI01', 'certificacion_de' => Carrera::where('codigo', '01')->first()->id, 'nombre' => 'Soporte en redes informáticas', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'CERTIFICACION')->first()->id, 'estado_id' => $estadoId],
            ['codigo' => 'MCREC01', 'certificacion_de' => Carrera::where('codigo', '01')->first()->id, 'nombre' => 'Redes de Enrutamiento y Conmutación', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'CERTIFICACION')->first()->id, 'estado_id' => $estadoId],
            ['codigo' => 'MCSIN01', 'certificacion_de' => Carrera::where('codigo', '01')->first()->id, 'nombre' => 'Seguridad Informática ', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'CERTIFICACION')->first()->id, 'estado_id' => $estadoId],
            ['codigo' => 'MCANU01', 'certificacion_de' => Carrera::where('codigo', '01')->first()->id, 'nombre' => 'Arquitecturas de nube', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'CERTIFICACION')->first()->id, 'estado_id' => $estadoId],
            ['codigo' => 'MCGIV01', 'certificacion_de' => Carrera::where('codigo', '01')->first()->id, 'nombre' => 'Gestión de infraestructuras virtualizadas', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'CERTIFICACION')->first()->id, 'estado_id' => $estadoId],
            ['codigo' => 'MCIOT01', 'certificacion_de' => Carrera::where('codigo', '01')->first()->id, 'nombre' => 'Fundamentos de Internet de las Cosas', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'CERTIFICACION')->first()->id, 'estado_id' => $estadoId],
            ['codigo' => 'MCARI01', 'certificacion_de' => Carrera::where('codigo', '01')->first()->id, 'nombre' => 'Administración de Redes Inalámbricas', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'CERTIFICACION')->first()->id, 'estado_id' => $estadoId],

            //Certificaciones de Mantenimiento Industrial
            ['codigo' => 'MCIME02', 'certificacion_de' => Carrera::where('codigo', '02')->first()->id, 'nombre' => 'Instalación y mantenimiento eléctrico básico', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'CERTIFICACION')->first()->id, 'estado_id' => $estadoId],
            ['codigo' => 'MCPFC02', 'certificacion_de' => Carrera::where('codigo', '02')->first()->id, 'nombre' => 'Procesos de fabricación convencionales', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'CERTIFICACION')->first()->id, 'estado_id' => $estadoId],
            ['codigo' => 'MCSIN02', 'certificacion_de' => Carrera::where('codigo', '02')->first()->id, 'nombre' => 'Soldadura industrial', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'CERTIFICACION')->first()->id, 'estado_id' => $estadoId],
            ['codigo' => 'MCMAR02', 'certificacion_de' => Carrera::where('codigo', '02')->first()->id, 'nombre' => 'Mantenimiento de sistemas de aire acondicionado y refrigeración', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'CERTIFICACION')->first()->id, 'estado_id' => $estadoId],
            ['codigo' => 'MCTMP02', 'certificacion_de' => Carrera::where('codigo', '02')->first()->id, 'nombre' => 'Técnicas de Mantenimiento predictivo', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'CERTIFICACION')->first()->id, 'estado_id' => $estadoId],

            //Certificaciones de Industrias Alimentarias
            ['codigo' => 'MCACC05', 'certificacion_de' => Carrera::where('codigo', '03')->first()->id, 'nombre' => 'Acreditación de cuarta categoría', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'CERTIFICACION')->first()->id, 'estado_id' => $estadoId],
            ['codigo' => 'MCATC05', 'certificacion_de' => Carrera::where('codigo', '03')->first()->id, 'nombre' => 'Acreditación tercera categoría', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'CERTIFICACION')->first()->id, 'estado_id' => $estadoId],
            ['codigo' => 'MCSEL05', 'certificacion_de' => Carrera::where('codigo', '03')->first()->id, 'nombre' => 'Certificación en seguridad eléctrica', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'CERTIFICACION')->first()->id, 'estado_id' => $estadoId],
            ['codigo' => 'MCTAL05', 'certificacion_de' => Carrera::where('codigo', '03')->first()->id, 'nombre' => 'Certificación para trabajo en alturas', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'CERTIFICACION')->first()->id, 'estado_id' => $estadoId],

            //Certificaciones de Turismo
            ['codigo' => 'MCIPT04', 'certificacion_de' => Carrera::where('codigo', '04')->first()->id, 'nombre' => 'Información y Promoción Turística Local', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'CERTIFICACION')->first()->id, 'estado_id' => $estadoId],
            ['codigo' => 'MCOST04', 'certificacion_de' => Carrera::where('codigo', '04')->first()->id, 'nombre' => 'Operación de Servicios Turísticos y Atención al Cliente', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'CERTIFICACION')->first()->id, 'estado_id' => $estadoId],
            ['codigo' => 'MCOAG04', 'certificacion_de' => Carrera::where('codigo', '04')->first()->id, 'nombre' => 'Operaciones de Alojamiento y Gastronomía Turística', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'CERTIFICACION')->first()->id, 'estado_id' => $estadoId],
            ['codigo' => 'MCEPP04', 'certificacion_de' => Carrera::where('codigo', '04')->first()->id, 'nombre' => 'Identificación y Promoción de Productos y Destinos Turísticos', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'CERTIFICACION')->first()->id, 'estado_id' => $estadoId],
            ['codigo' => 'MCOSE04', 'certificacion_de' => Carrera::where('codigo', '04')->first()->id, 'nombre' => 'Operación Sostenible y Experiencia Turística de Calidad', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'CERTIFICACION')->first()->id, 'estado_id' => $estadoId],
            ['codigo' => 'MCIPC04', 'certificacion_de' => Carrera::where('codigo', '04')->first()->id, 'nombre' => 'Innovación, Promoción y Comercialización Turística', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'CERTIFICACION')->first()->id, 'estado_id' => $estadoId],
            ['codigo' => 'MCGID05', 'certificacion_de' => Carrera::where('codigo', '04')->first()->id, 'nombre' => 'Gestión Integral del Turismo y Desarrollo de Destinos Sostenibles', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'CERTIFICACION')->first()->id, 'estado_id' => $estadoId],

            //Certificaciones de Eficiencia Energética

        ]);
    }
}
