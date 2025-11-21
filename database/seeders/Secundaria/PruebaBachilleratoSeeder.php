<?php

namespace Database\Seeders\Secundaria;

use App\Models\Secundaria\PruebaBachillerato;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PruebaBachilleratoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PruebaBachillerato::createMany([
            ['codigo' => 'AVANZO', 'descripcion' => ''],
        ]);
    }
}
