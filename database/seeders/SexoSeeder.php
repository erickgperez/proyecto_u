<?php

namespace Database\Seeders;

use App\Models\Sexo;
use Illuminate\Database\Seeder;

class SexoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Sexo::createMany([
            ['codigo' => 'M', 'descripcion' => 'Masculino'],
            ['codigo' => 'F', 'descripcion' => 'Femenino'],
        ]);
    }
}
