<?php

namespace Database\Seeders\PlanEstudio;

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
        Carrera::insert([
            ['codigo' => 'MI-S', 'nombre' => 'Soldadura', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'CERTIFICACION')->first()->id, 'certificacion_de' => Carrera::where('codigo', 'MI')->first()->id],
            ['codigo' => 'MI-CR', 'nombre' => 'Climatización y Refrigeración', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'CERTIFICACION')->first()->id, 'certificacion_de' => Carrera::where('codigo', 'MI')->first()->id],
            ['codigo' => 'MI-MP', 'nombre' => 'Mecanizado de piezas', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'CERTIFICACION')->first()->id, 'certificacion_de' => Carrera::where('codigo', 'MI')->first()->id],
            ['codigo' => 'EE-C4', 'nombre' => 'Electricista Categoría 4', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'CERTIFICACION')->first()->id, 'certificacion_de' => Carrera::where('codigo', 'EE')->first()->id],
            ['codigo' => 'EE-C3', 'nombre' => 'Electricista Categoría 3', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'CERTIFICACION')->first()->id, 'certificacion_de' => Carrera::where('codigo', 'EE')->first()->id],
            ['codigo' => 'EE-SF', 'nombre' => 'Sistemas Fotovoltaicos', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'CERTIFICACION')->first()->id, 'certificacion_de' => Carrera::where('codigo', 'EE')->first()->id],
            ['codigo' => 'TI-BD', 'nombre' => 'Gestor de Bases de Datos', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'CERTIFICACION')->first()->id, 'certificacion_de' => Carrera::where('codigo', 'TI')->first()->id],
            ['codigo' => 'TI-RI', 'nombre' => 'Redes e Infraestructura', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'CERTIFICACION')->first()->id, 'certificacion_de' => Carrera::where('codigo', 'TI')->first()->id],
            ['codigo' => 'TI-PA', 'nombre' => 'Programador Analista', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'CERTIFICACION')->first()->id, 'certificacion_de' => Carrera::where('codigo', 'TI')->first()->id],
            ['codigo' => 'TUR-GH', 'nombre' => 'Gestión Hotelera', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'CERTIFICACION')->first()->id, 'certificacion_de' => Carrera::where('codigo', 'TUR')->first()->id],
            ['codigo' => 'TUR-GA', 'nombre' => 'Gastronomía', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'CERTIFICACION')->first()->id, 'certificacion_de' => Carrera::where('codigo', 'TUR')->first()->id],
            ['codigo' => 'ALI-CE', 'nombre' => 'Cereales', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'CERTIFICACION')->first()->id, 'certificacion_de' => Carrera::where('codigo', 'ALI')->first()->id],
            ['codigo' => 'ALI-FH', 'nombre' => 'Frutas y Hortalizas', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'CERTIFICACION')->first()->id, 'certificacion_de' => Carrera::where('codigo', 'ALI')->first()->id],
            ['codigo' => 'ALI-CA', 'nombre' => 'Carnes', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'CERTIFICACION')->first()->id, 'certificacion_de' => Carrera::where('codigo', 'ALI')->first()->id],
            ['codigo' => 'ALI-LA', 'nombre' => 'Lácteos', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'CERTIFICACION')->first()->id, 'certificacion_de' => Carrera::where('codigo', 'ALI')->first()->id],
            ['codigo' => 'ALI-AN', 'nombre' => 'Concentrados para animales', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'CERTIFICACION')->first()->id, 'certificacion_de' => Carrera::where('codigo', 'ALI')->first()->id],
            ['codigo' => 'NE-MK', 'nombre' => 'Marketing de Vanguardia para Negocios', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'CERTIFICACION')->first()->id, 'certificacion_de' => null],
            ['codigo' => 'TEC-CON', 'nombre' => 'Construcción', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'CERTIFICACION')->first()->id, 'certificacion_de' => null],
            ['codigo' => 'NE-FD', 'nombre' => 'Fabricación Digital para Emprendedores', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'CERTIFICACION')->first()->id, 'certificacion_de' => null],
            ['codigo' => 'TEC-PL', 'nombre' => 'Plásticos', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'CERTIFICACION')->first()->id, 'certificacion_de' => null],
        ]);
    }
}
