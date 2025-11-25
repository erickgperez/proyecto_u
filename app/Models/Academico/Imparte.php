<?php

namespace App\Models\Academico;

use App\Models\User;
use App\Traits\HasCreateMany;
use App\Traits\HasUuid;
use App\Traits\UserStamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Imparte extends Model
{
    use UserStamps, HasUuid, HasCreateMany;

    protected $table = "academico.imparte";

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function carreraSede(): BelongsTo
    {
        return $this->belongsTo(CarreraSede::class);
    }

    public function oferta(): BelongsTo
    {
        return $this->belongsTo(Oferta::class);
    }

    public function docentes(): BelongsToMany
    {
        return $this->belongsToMany(Docente::class, 'academico.imparte_docente', 'imparte_id', 'docente_id');
    }
}
