<?php

namespace Database\Seeders;

use App\Models\Departamento;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MunicipioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('municipio')->insert([
            ['codigo' => '0101', 'descripcion' => 'Ahuachapán Centro', 'departamento_id' => Departamento::where('codigo', '01')->first()->id],
            ['codigo' => '0102', 'descripcion' => 'Ahuachapán Norte', 'departamento_id' => Departamento::where('codigo', '01')->first()->id],
            ['codigo' => '0103', 'descripcion' => 'Ahuachapán Sur', 'departamento_id' => Departamento::where('codigo', '01')->first()->id],
            ['codigo' => '0201', 'descripcion' => 'Santa Ana Centro', 'departamento_id' => Departamento::where('codigo', '02')->first()->id],
            ['codigo' => '0202', 'descripcion' => 'Santa Ana Este', 'departamento_id' => Departamento::where('codigo', '02')->first()->id],
            ['codigo' => '0203', 'descripcion' => 'Santa Ana Norte', 'departamento_id' => Departamento::where('codigo', '02')->first()->id],
            ['codigo' => '0204', 'descripcion' => 'Santa Ana Oeste', 'departamento_id' => Departamento::where('codigo', '02')->first()->id],
            ['codigo' => '0301', 'descripcion' => 'Sonsonate Centro', 'departamento_id' => Departamento::where('codigo', '03')->first()->id],
            ['codigo' => '0302', 'descripcion' => 'Sonsonate Este', 'departamento_id' => Departamento::where('codigo', '03')->first()->id],
            ['codigo' => '0303', 'descripcion' => 'Sonsonate Norte', 'departamento_id' => Departamento::where('codigo', '03')->first()->id],
            ['codigo' => '0304', 'descripcion' => 'Sonsonate Oeste', 'departamento_id' => Departamento::where('codigo', '03')->first()->id],
            ['codigo' => '0401', 'descripcion' => 'Chalatenango Centro', 'departamento_id' => Departamento::where('codigo', '04')->first()->id],
            ['codigo' => '0402', 'descripcion' => 'Chalatenango Norte', 'departamento_id' => Departamento::where('codigo', '04')->first()->id],
            ['codigo' => '0403', 'descripcion' => 'Chalatenango Sur', 'departamento_id' => Departamento::where('codigo', '04')->first()->id],
            ['codigo' => '0501', 'descripcion' => 'La Libertad Centro', 'departamento_id' => Departamento::where('codigo', '05')->first()->id],
            ['codigo' => '0502', 'descripcion' => 'La Libertad Costa', 'departamento_id' => Departamento::where('codigo', '05')->first()->id],
            ['codigo' => '0503', 'descripcion' => 'La Libertad Este', 'departamento_id' => Departamento::where('codigo', '05')->first()->id],
            ['codigo' => '0504', 'descripcion' => 'La Libertad Norte', 'departamento_id' => Departamento::where('codigo', '05')->first()->id],
            ['codigo' => '0505', 'descripcion' => 'La Libertad Oeste', 'departamento_id' => Departamento::where('codigo', '05')->first()->id],
            ['codigo' => '0506', 'descripcion' => 'La Libertad Sur', 'departamento_id' => Departamento::where('codigo', '05')->first()->id],
            ['codigo' => '0601', 'descripcion' => 'San Salvador Centro', 'departamento_id' => Departamento::where('codigo', '06')->first()->id],
            ['codigo' => '0602', 'descripcion' => 'San Salvador Este', 'departamento_id' => Departamento::where('codigo', '06')->first()->id],
            ['codigo' => '0603', 'descripcion' => 'San Salvador Norte', 'departamento_id' => Departamento::where('codigo', '06')->first()->id],
            ['codigo' => '0604', 'descripcion' => 'San Salvador Oeste', 'departamento_id' => Departamento::where('codigo', '06')->first()->id],
            ['codigo' => '0605', 'descripcion' => 'San Salvador Sur', 'departamento_id' => Departamento::where('codigo', '06')->first()->id],
            ['codigo' => '0701', 'descripcion' => 'Cuscatlán Norte', 'departamento_id' => Departamento::where('codigo', '07')->first()->id],
            ['codigo' => '0702', 'descripcion' => 'Cuscatlán Sur', 'departamento_id' => Departamento::where('codigo', '07')->first()->id],
            ['codigo' => '0801', 'descripcion' => 'La Paz Centro', 'departamento_id' => Departamento::where('codigo', '08')->first()->id],
            ['codigo' => '0802', 'descripcion' => 'La Paz Este', 'departamento_id' => Departamento::where('codigo', '08')->first()->id],
            ['codigo' => '0803', 'descripcion' => 'La Paz Oeste', 'departamento_id' => Departamento::where('codigo', '08')->first()->id],
            ['codigo' => '0901', 'descripcion' => 'Cabañas Este', 'departamento_id' => Departamento::where('codigo', '09')->first()->id],
            ['codigo' => '0902', 'descripcion' => 'Cabañas Oeste', 'departamento_id' => Departamento::where('codigo', '09')->first()->id],
            ['codigo' => '1001', 'descripcion' => 'San Vicente Norte', 'departamento_id' => Departamento::where('codigo', '10')->first()->id],
            ['codigo' => '1002', 'descripcion' => 'San Vicente Sur', 'departamento_id' => Departamento::where('codigo', '10')->first()->id],
            ['codigo' => '1101', 'descripcion' => 'Usulután Este', 'departamento_id' => Departamento::where('codigo', '11')->first()->id],
            ['codigo' => '1102', 'descripcion' => 'Usulután Norte', 'departamento_id' => Departamento::where('codigo', '11')->first()->id],
            ['codigo' => '1103', 'descripcion' => 'Usulután Oeste', 'departamento_id' => Departamento::where('codigo', '11')->first()->id],
            ['codigo' => '1201', 'descripcion' => 'San Miguel Centro', 'departamento_id' => Departamento::where('codigo', '12')->first()->id],
            ['codigo' => '1202', 'descripcion' => 'San Miguel Norte', 'departamento_id' => Departamento::where('codigo', '12')->first()->id],
            ['codigo' => '1203', 'descripcion' => 'San Miguel Oeste', 'departamento_id' => Departamento::where('codigo', '12')->first()->id],
            ['codigo' => '1301', 'descripcion' => 'Morazán Norte', 'departamento_id' => Departamento::where('codigo', '13')->first()->id],
            ['codigo' => '1302', 'descripcion' => 'Morazán Sur', 'departamento_id' => Departamento::where('codigo', '13')->first()->id],
            ['codigo' => '1401', 'descripcion' => 'La Unión Norte', 'departamento_id' => Departamento::where('codigo', '14')->first()->id],
            ['codigo' => '1402', 'descripcion' => 'La Unión Sur', 'departamento_id' => Departamento::where('codigo', '14')->first()->id],
        ]);
    }
}
