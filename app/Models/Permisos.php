<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permisos extends Model
{

    protected $table = "public.permissions";

    protected $attributes = [
        'guard_name' => 'web',
    ];
}
