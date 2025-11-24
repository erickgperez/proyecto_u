<?php

namespace Database\Seeders\Academico;

use App\Models\Academico\TipoCurso;
use Illuminate\Database\Seeder;

class TipoCursoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TipoCurso::createMany([
            ['codigo' => 'NM', 'descripcion' => 'Normal'],
            ['codigo' => 'EX', 'descripcion' => 'Extemporáneo'],
            ['codigo' => 'EQ', 'descripcion' => 'Equivalencia']
        ]);
    }
}
