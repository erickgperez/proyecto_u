<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Rol extends Model
{
    protected $table = 'public.roles';

    protected $attributes = [
        'guard_name' => 'web',
    ];

    public function permisos(): BelongsToMany
    {
        return $this->belongsToMany(Permisos::class, 'role_has_permissions', 'role_id', 'permission_id');
    }
}
