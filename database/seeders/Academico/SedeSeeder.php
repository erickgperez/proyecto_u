<?php

namespace Database\Seeders\Academico;

use App\Models\Academico\Sede;
use Illuminate\Database\Seeder;

class SedeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Sede::createMany([
            ['codigo' => '01', 'nombre' => 'SAN SALVADOR'],
            ['codigo' => '02', 'nombre' => 'SONSONATE'],
            ['codigo' => '03', 'nombre' => 'CHALATENANGO'],
            ['codigo' => '04', 'nombre' => 'ZACATECOLUCA'],
            ['codigo' => '05', 'nombre' => 'MORAZÁN'],
            ['codigo' => '06', 'nombre' => 'LA UNIÓN'],
        ]);
    }
}
