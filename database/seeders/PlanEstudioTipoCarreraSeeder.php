<?php

namespace Database\Seeders;

use App\Models\Departamento;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlanEstudioTipoCarreraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('plan_estudio.tipo_carrera')->insert([
            ['codigo' => 'TECNICA', 'descripcion' => 'Técnica', 'grado_id' => DB::table('plan_estudio.grado')->where('codigo', '01')->first()->id],
        ]);
    }
}
