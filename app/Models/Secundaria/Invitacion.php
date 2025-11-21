<?php

namespace App\Models\Secundaria;

use App\Models\Ingreso\Convocatoria;
use App\Models\User;
use App\Traits\UserStamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invitacion extends Model
{
    use UserStamps;
    //
    protected $table = "secundaria.invitacion";

    protected $fillable = [
        'nie',
        'codigo',
        'convocatoria_id',
        'invitado'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function convocatoria(): BelongsTo
    {
        return $this->belongsTo(Convocatoria::class);
    }
}
