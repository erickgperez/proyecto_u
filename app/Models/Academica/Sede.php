<?php

namespace App\Models\Academica;

use App\Models\Distrito;
use App\Models\User;
use App\Traits\UserStamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sede extends Model
{
    use UserStamps;

    protected $table = "academico.sede";

    protected $fillable = [
        'codigo',
        'nombre',
        'distrito_id'
    ];


    public function distrito(): BelongsTo
    {
        return $this->belongsTo(Distrito::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
