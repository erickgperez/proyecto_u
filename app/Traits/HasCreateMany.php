<?php

namespace App\Traits;

trait HasCreateMany
{
    /**
     * Crear múltiples registros usando Eloquent::create()
     * de forma limpia y reutilizable.
     *
     * @param array $records
     * @return \Illuminate\Support\Collection
     */
    public static function createMany(array $records)
    {
        return collect($records)->map(function ($record) {
            return static::create($record);
        });
    }
}
