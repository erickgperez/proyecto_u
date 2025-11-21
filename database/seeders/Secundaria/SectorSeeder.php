<?php

namespace Database\Seeders\Secundaria;

use App\Models\Secundaria\Sector;
use Illuminate\Database\Seeder;

class SectorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Sector::createMany([
            ['codigo' => '01', 'descripcion' => 'Público'],
            ['codigo' => '02', 'descripcion' => 'Privado'],
        ]);
    }
}
