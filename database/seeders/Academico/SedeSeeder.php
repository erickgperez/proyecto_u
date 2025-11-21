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
            ['codigo' => 'S-SS', 'nombre' => 'SAN SALVADOR'],
            ['codigo' => 'S-SO', 'nombre' => 'SONSONATE'],
            ['codigo' => 'S-CH', 'nombre' => 'CHALATENANGO'],
            ['codigo' => 'S-ZA', 'nombre' => 'ZACATECOLUCA'],
            ['codigo' => 'S-MO', 'nombre' => 'MORAZÁN'],
            ['codigo' => 'S-LU', 'nombre' => 'LA UNIÓN'],
        ]);
    }
}
