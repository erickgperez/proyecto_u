<?php

namespace Database\Seeders\Academico;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SedeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('academico.sede')->insert([
            ['codigo' => 'S-SS', 'nombre' => 'SAN SALVADOR'],
            ['codigo' => 'S-SO', 'nombre' => 'SONSONATE'],
            ['codigo' => 'S-CH', 'nombre' => 'CHALATENANGO'],
            ['codigo' => 'S-ZA', 'nombre' => 'ZACATECOLUCA'],
            ['codigo' => 'S-MO', 'nombre' => 'MORAZÁN'],
            ['codigo' => 'S-LU', 'nombre' => 'LA UNIÓN'],
        ]);
    }
}
