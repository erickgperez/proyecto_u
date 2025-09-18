<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SexoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sexo')->insert([
            ['codigo' => 'M', 'descripcion' => 'Masculino'],
            ['codigo' => 'F', 'descripcion' => 'Femenino'],
        ]);
    }
}
