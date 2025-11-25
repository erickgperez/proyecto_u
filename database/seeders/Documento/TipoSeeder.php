<?php

namespace Database\Seeders\Documento;

use App\Models\Documento\TipoDocumento;
use App\Models\Rol;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TipoDocumento::createMany([
            ['codigo' => 'CARNE', 'descripcion' => 'Carné de identificación'],
            ['codigo' => 'EXAMEN_MEDICO', 'descripcion' => 'Exámenes médicos'],
            ['codigo' => 'DIPLOMA_BACHILLERATO', 'descripcion' => 'Diploma de bachillerato'],
            ['codigo' => 'FOTOGRAFIA', 'descripcion' => 'Fotografía'],
            ['codigo' => 'DUI', 'descripcion' => 'Documento único de identidad'],
            ['codigo' => 'NIT', 'descripcion' => 'NIT homologado'],
            ['codigo' => 'AFP', 'descripcion' => 'Carné de AFP'],
            ['codigo' => 'TITULO_BACHILLER', 'descripcion' => 'Título de bachillerato'],
            ['codigo' => 'PARTIDA_NACIMIENTO', 'descripcion' => 'Partida de nacimiento'],
            ['codigo' => 'TITULO_UNIVERSITARIO', 'descripcion' => 'Título universitario'],
        ]);

        $rolDocente = Rol::where('name', 'docente')->where('guard_name', 'web')->first();
        DB::table('documento.tipo_rol')->insert([
            ['rol_id' => $rolDocente->id, 'tipo_id' => TipoDocumento::where('codigo', 'DUI')->first()->id],
            ['rol_id' => $rolDocente->id, 'tipo_id' => TipoDocumento::where('codigo', 'NIT')->first()->id],
            ['rol_id' => $rolDocente->id, 'tipo_id' => TipoDocumento::where('codigo', 'AFP')->first()->id],
            ['rol_id' => $rolDocente->id, 'tipo_id' => TipoDocumento::where('codigo', 'TITULO_UNIVERSITARIO')->first()->id]
        ]);

        $rolEstudiante = Rol::where('name', 'estudiante')->where('guard_name', 'web')->first();
        DB::table('documento.tipo_rol')->insert([
            ['rol_id' => $rolEstudiante->id, 'tipo_id' => TipoDocumento::where('codigo', 'CARNE')->first()->id],
            ['rol_id' => $rolEstudiante->id, 'tipo_id' => TipoDocumento::where('codigo', 'EXAMEN_MEDICO')->first()->id],
            ['rol_id' => $rolEstudiante->id, 'tipo_id' => TipoDocumento::where('codigo', 'DUI')->first()->id],
            ['rol_id' => $rolEstudiante->id, 'tipo_id' => TipoDocumento::where('codigo', 'DIPLOMA_BACHILLERATO')->first()->id],
            ['rol_id' => $rolEstudiante->id, 'tipo_id' => TipoDocumento::where('codigo', 'FOTOGRAFIA')->first()->id]
        ]);
    }
}
