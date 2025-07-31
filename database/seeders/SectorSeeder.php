<?php

namespace Database\Seeders;

use App\Models\Departamento;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SectorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('secundaria.sector')->insert([
            ['codigo' => '01', 'descripcion' => 'Público'],
            ['codigo' => '02', 'descripcion' => 'Privado'],
        ]);
    }
}
