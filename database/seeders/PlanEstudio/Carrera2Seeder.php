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
            ['codigo' => 'MI-S', 'nombre' => 'Soldadura', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'CERTIFICACION')->first()->id, 'certificacion_de' => Carrera::where('codigo', 'MI')->first()->id, 'estado_id' => $estadoId],
            ['codigo' => 'MI-CR', 'nombre' => 'Climatización y Refrigeración', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'CERTIFICACION')->first()->id, 'certificacion_de' => Carrera::where('codigo', 'MI')->first()->id, 'estado_id' => $estadoId],
            ['codigo' => 'MI-MP', 'nombre' => 'Mecanizado de piezas', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'CERTIFICACION')->first()->id, 'certificacion_de' => Carrera::where('codigo', 'MI')->first()->id, 'estado_id' => $estadoId],
            ['codigo' => 'EE-C4', 'nombre' => 'Electricista Categoría 4', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'CERTIFICACION')->first()->id, 'certificacion_de' => Carrera::where('codigo', 'EE')->first()->id, 'estado_id' => $estadoId],
            ['codigo' => 'EE-C3', 'nombre' => 'Electricista Categoría 3', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'CERTIFICACION')->first()->id, 'certificacion_de' => Carrera::where('codigo', 'EE')->first()->id, 'estado_id' => $estadoId],
            ['codigo' => 'EE-SF', 'nombre' => 'Sistemas Fotovoltaicos', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'CERTIFICACION')->first()->id, 'certificacion_de' => Carrera::where('codigo', 'EE')->first()->id, 'estado_id' => $estadoId],
            ['codigo' => 'TI-BD', 'nombre' => 'Gestor de Bases de Datos', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'CERTIFICACION')->first()->id, 'certificacion_de' => Carrera::where('codigo', 'TI')->first()->id, 'estado_id' => $estadoId],
            ['codigo' => 'TI-RI', 'nombre' => 'Redes e Infraestructura', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'CERTIFICACION')->first()->id, 'certificacion_de' => Carrera::where('codigo', 'TI')->first()->id, 'estado_id' => $estadoId],
            ['codigo' => 'TI-PA', 'nombre' => 'Programador Analista', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'CERTIFICACION')->first()->id, 'certificacion_de' => Carrera::where('codigo', 'TI')->first()->id, 'estado_id' => $estadoId],
            ['codigo' => 'TUR-GH', 'nombre' => 'Gestión Hotelera', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'CERTIFICACION')->first()->id, 'certificacion_de' => Carrera::where('codigo', 'TUR')->first()->id, 'estado_id' => $estadoId],
            ['codigo' => 'TUR-GA', 'nombre' => 'Gastronomía', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'CERTIFICACION')->first()->id, 'certificacion_de' => Carrera::where('codigo', 'TUR')->first()->id, 'estado_id' => $estadoId],
            ['codigo' => 'ALI-CE', 'nombre' => 'Cereales', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'CERTIFICACION')->first()->id, 'certificacion_de' => Carrera::where('codigo', 'ALI')->first()->id, 'estado_id' => $estadoId],
            ['codigo' => 'ALI-FH', 'nombre' => 'Frutas y Hortalizas', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'CERTIFICACION')->first()->id, 'certificacion_de' => Carrera::where('codigo', 'ALI')->first()->id, 'estado_id' => $estadoId],
            ['codigo' => 'ALI-CA', 'nombre' => 'Carnes', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'CERTIFICACION')->first()->id, 'certificacion_de' => Carrera::where('codigo', 'ALI')->first()->id, 'estado_id' => $estadoId],
            ['codigo' => 'ALI-LA', 'nombre' => 'Lácteos', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'CERTIFICACION')->first()->id, 'certificacion_de' => Carrera::where('codigo', 'ALI')->first()->id, 'estado_id' => $estadoId],
            ['codigo' => 'ALI-AN', 'nombre' => 'Concentrados para animales', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'CERTIFICACION')->first()->id, 'certificacion_de' => Carrera::where('codigo', 'ALI')->first()->id, 'estado_id' => $estadoId],
            ['codigo' => 'NE-MK', 'nombre' => 'Marketing de Vanguardia para Negocios', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'CERTIFICACION')->first()->id, 'certificacion_de' => null, 'estado_id' => $estadoId],
            ['codigo' => 'TEC-CON', 'nombre' => 'Construcción', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'CERTIFICACION')->first()->id, 'certificacion_de' => null, 'estado_id' => $estadoId],
            ['codigo' => 'NE-FD', 'nombre' => 'Fabricación Digital para Emprendedores', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'CERTIFICACION')->first()->id, 'certificacion_de' => null, 'estado_id' => $estadoId],
            ['codigo' => 'TEC-PL', 'nombre' => 'Plásticos', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'CERTIFICACION')->first()->id, 'certificacion_de' => null, 'estado_id' => $estadoId],
        ]);
    }
}
