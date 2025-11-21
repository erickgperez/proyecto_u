<?php

namespace Database\Seeders;

use App\Models\Departamento;
use Illuminate\Database\Seeder;

class DepartamentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Departamento::insert([
            ['codigo' => '01', 'descripcion' => 'AHUACHAPAN'],
            ['codigo' => '02', 'descripcion' => 'SANTA ANA'],
            ['codigo' => '03', 'descripcion' => 'SONSONATE'],
            ['codigo' => '04', 'descripcion' => 'CHALATENANGO'],
            ['codigo' => '05', 'descripcion' => 'LA LIBERTAD'],
            ['codigo' => '06', 'descripcion' => 'SAN SALVADOR'],
            ['codigo' => '07', 'descripcion' => 'CUSCATLAN'],
            ['codigo' => '08', 'descripcion' => 'LA PAZ'],
            ['codigo' => '09', 'descripcion' => 'CABAÑAS'],
            ['codigo' => '10', 'descripcion' => 'SAN VICENTE'],
            ['codigo' => '11', 'descripcion' => 'USULUTAN'],
            ['codigo' => '12', 'descripcion' => 'SAN MIGUEL'],
            ['codigo' => '13', 'descripcion' => 'MORAZÁN'],
            ['codigo' => '14', 'descripcion' => 'LA UNIÓN'],
        ]);
    }
}
