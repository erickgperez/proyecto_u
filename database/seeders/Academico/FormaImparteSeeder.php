<?php

namespace Database\Seeders\Academico;

use App\Models\Academico\FormaImparte;
use Illuminate\Database\Seeder;

class FormaImparteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FormaImparte::createMany([
            ['codigo' => 'TITULAR', 'descripcion' => 'Profesor titular de la asignatura'],
            ['codigo' => 'ASOCIADO', 'descripcion' => 'Profesor asociado de la asignatura'],
        ]);
    }
}
