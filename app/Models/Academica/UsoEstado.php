<?php

namespace App\Models\Academica;

use App\Models\PlanEstudio\Carrera;
use App\Models\User;
use App\Traits\UserStamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class UsoEstado extends Model
{
    use UserStamps;

    protected $table = "academico.uso_estado";

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
