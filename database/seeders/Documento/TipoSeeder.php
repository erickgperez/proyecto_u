<?php

namespace Database\Seeders\Documento;

use App\Models\Documento\TipoDocumento;
use Illuminate\Database\Seeder;

class TipoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TipoDocumento::createMany([
            ['codigo' => 'DUI', 'descripcion' => 'Documento único de identidad'],
            ['codigo' => 'TITULO_BACHILLER', 'descripcion' => 'Título de bachillerato'],
            ['codigo' => 'PARTIDA_NACIMIENTO', 'descripcion' => 'Partida de nacimiento'],
            ['codigo' => 'TITULO_UNIVERSITARIO_GRADO', 'descripcion' => 'Título universitario de carrera de grado'],
        ]);
    }
}
