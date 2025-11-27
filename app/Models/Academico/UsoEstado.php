<?php

namespace App\Models\Academico;

use App\Models\User;
use App\Traits\HasCreateMany;
use App\Traits\HasUuid;
use App\Traits\UserStamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UsoEstado extends Model
{
    use UserStamps, HasUuid, HasCreateMany;

    protected $table = "academico.uso_estado";

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function estados(): HasMany
    {
        return $this->hasMany(Estado::class);
    }
}
