<?php

namespace App\Models\Workflow;

use App\Models\User;
use App\Traits\HasCreateMany;
use App\Traits\HasUuid;
use App\Traits\UserStamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class FlujoModelo extends Model
{
    use UserStamps, HasUuid, HasCreateMany;

    protected $table = 'workflow.flujo_modelo';

    protected $fillable = [
        'flujo_id',
    ];

    public function flujo(): BelongsTo
    {
        return $this->belongsTo(Flujo::class, 'flujo_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function modelo(): MorphTo
    {
        return $this->morphTo();
    }
}
