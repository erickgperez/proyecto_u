<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SecundariaSectorSeeder extends Seeder
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
