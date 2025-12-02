<?php

namespace App\Models\Academico;

use App\Models\User;
use App\Traits\UserStamps;
use Illuminate\Database\Eloquent\Model;

class FormaImparte extends Model
{
    use UserStamps;

    protected $table = "academico.forma_imparte";

    protected $fillable = [
        'codigo',
        'descripcion',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
