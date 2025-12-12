<?php

namespace App\Imports;

use App\Models\Secundaria\DataBachillerato;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithSkipDuplicates;

class BachilleratoImport implements ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts, WithCustomCsvSettings, WithSkipDuplicates
{

    public function model(array $row)
    {

        return new DataBachillerato([
            'nie' => $row['nie'],
            'correo' => $row['correo_electronico'],
            'primer_nombre' => $row['primer_nombre'],
            'segundo_nombre' => $row['segundo_nombre'],
            'tercer_nombre' => $row['tercer_nombre'],
            'primer_apellido' => $row['primer_apellido'],
            'segundo_apellido' => $row['segundo_apellido'],
            'tercer_apellido' => $row['tercer_apellido'],
            'sexo' => $row['sexo'],
            'edad' => $row['edad'],
            'sector' => $row['sector'],
            'grado' => $row['grado'],
            'codigo_ce' => $row['codigo_ce'],
            'nombre_centro_educativo' => $row['nombre_de_centro_educativo'],
            'direccion' => $row['direccion'],
            'codigo_depto' => $row['codigo_depto'],
            'departamento' => $row['departamento'],
            'codigo_distrito' => $row['codigo_distrito'],
            'distrito' => $row['distrito'],
            'opcion_bachillerato' => $row['opcion_de_bach_tecnico']
        ]);
    }

    public function batchSize(): int
    {
        return 1000;
    }
    public function chunkSize(): int
    {
        return 1000;
    }
    public function getCsvSettings(): array
    {
        return [
            'delimiter' => "\t"
        ];
    }
}
