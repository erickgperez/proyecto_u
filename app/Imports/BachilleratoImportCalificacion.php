<?php

namespace App\Imports;

use App\Models\Ingreso\Aspirante;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithSkipDuplicates;

class BachilleratoImportCalificacion implements ToCollection, WithHeadingRow, WithChunkReading, WithBatchInserts, WithCustomCsvSettings, WithSkipDuplicates
{

    public function collection(Collection $rows)
    {

        foreach ($rows as $row) {
            $aspirante = Aspirante::where('nie', $row['nie'])->first();
            if ($aspirante) {
                $aspirante->calificacion_bachillerato = $row['calificacion'] ?? $row['nota'];
            }
        }
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
            'input_encoding' => 'UTF-8',
            'delimiter' => "\t"
        ];
    }
}
