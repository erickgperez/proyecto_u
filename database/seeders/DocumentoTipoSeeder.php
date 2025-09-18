<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DocumentoTipoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('documento.tipo')->insert([
            ['codigo' => 'DUI', 'descripcion' => 'Documento único de identidad'],
            ['codigo' => 'TITULO_BACHILLER', 'descripcion' => 'Título de bachillerato'],
            ['codigo' => 'PARTIDA_NACIMIENTO', 'descripcion' => 'Partida de nacimiento'],
            ['codigo' => 'TITULO_UNIVERSITARIO_GRADO', 'descripcion' => 'Título universitario de carrera de grado'],
        ]);
    }
}
