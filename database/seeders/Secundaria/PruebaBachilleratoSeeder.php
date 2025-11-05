<?php

namespace Database\Seeders\Secundaria;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PruebaBachilleratoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('secundaria.prueba_bachillerato')->insert([
            ['codigo' => 'AVANZO', 'descripcion' => ''],
        ]);
    }
}
