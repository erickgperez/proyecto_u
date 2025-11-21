<?php

namespace App\Models\Secundaria;

use App\Traits\HasCreateMany;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    use HasUuid, HasCreateMany;

    protected $table = "secundaria.sector";
}
