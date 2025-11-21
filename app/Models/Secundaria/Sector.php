<?php

namespace App\Models\Secundaria;

use App\Traits\HasCreateMany;
use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{

    use HasCreateMany;

    protected $table = "secundaria.sector";
}
