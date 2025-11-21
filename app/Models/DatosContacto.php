<?php

namespace App\Models;

use App\Models\User;
use App\Traits\HasCreateMany;
use App\Traits\HasUuid;
use App\Traits\UserStamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DatosContacto extends Model
{
    use UserStamps, HasUuid, HasCreateMany;

    protected $table = "datos_contacto";

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function persona(): BelongsTo
    {
        return $this->belongsTo(Persona::class);
    }

    public function distritoResidencia(): BelongsTo
    {
        return $this->belongsTo(Distrito::class, 'residencia_distrito_id');
    }
}
