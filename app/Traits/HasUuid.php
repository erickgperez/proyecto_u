<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasUuid
{
    /**
     * Boot the trait and assign a UUID when creating the model.
     */
    protected static function bootHasUuid()
    {
        static::creating(function ($model) {
            // Si no existe un uuid, generarlo automáticamente
            if (!$model->uuid) {
                $model->uuid = Str::uuid()->toString();
            }
        });
    }

    /**
     * Override the route model binding to use the uuid column.
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }
}
