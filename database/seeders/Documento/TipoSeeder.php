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
            ['codigo' => 'CARNE', 'descripcion' => 'Carné de identificación', 'multiple' => false],
            ['codigo' => 'EXAMEN_MEDICO', 'descripcion' => 'Exámenes médicos', 'multiple' => false],
            ['codigo' => 'DIPLOMA_BACHILLERATO', 'descripcion' => 'Diploma de bachillerato', 'multiple' => false],
            ['codigo' => 'FOTOGRAFIA', 'descripcion' => 'Fotografía', 'multiple' => false],
            ['codigo' => 'DUI', 'descripcion' => 'Documento único de identidad', 'multiple' => false],
            ['codigo' => 'NIT', 'descripcion' => 'NIT homologado', 'multiple' => false],
            ['codigo' => 'AFP', 'descripcion' => 'Carné de AFP', 'multiple' => false],
            ['codigo' => 'TITULO_BACHILLER', 'descripcion' => 'Título de bachillerato', 'multiple' => false],
            ['codigo' => 'PARTIDA_NACIMIENTO', 'descripcion' => 'Partida de nacimiento', 'multiple' => false],
            ['codigo' => 'TITULO_UNIVERSITARIO', 'descripcion' => 'Título universitario', 'multiple' => true],
            ['codigo' => 'OTROS_ATESTADOS', 'descripcion' => 'Otros atestados', 'multiple' => true],
        ]);

        $rolDocente = Rol::where('name', 'docente')->where('guard_name', 'web')->first();
        DB::table('documento.tipo_rol')->insert([
            ['rol_id' => $rolDocente->id, 'tipo_id' => TipoDocumento::where('codigo', 'DUI')->first()->id],
            ['rol_id' => $rolDocente->id, 'tipo_id' => TipoDocumento::where('codigo', 'NIT')->first()->id],
            ['rol_id' => $rolDocente->id, 'tipo_id' => TipoDocumento::where('codigo', 'AFP')->first()->id],
            ['rol_id' => $rolDocente->id, 'tipo_id' => TipoDocumento::where('codigo', 'TITULO_UNIVERSITARIO')->first()->id],
            ['rol_id' => $rolDocente->id, 'tipo_id' => TipoDocumento::where('codigo', 'OTROS_ATESTADOS')->first()->id]
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
