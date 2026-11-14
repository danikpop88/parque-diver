<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Visitante extends Model
{
    use HasUuids, Auditable;

    protected $table = 'visitantes';
    protected $primaryKey = 'id_visitante';

    protected $fillable = ['nombre_visitante', 'edad', 'estatura', 'id_titular'];

    protected $casts = [
        'estatura' => 'decimal:2',
    ];

    public function titular(): BelongsTo
    {
        return $this->belongsTo(Titular::class, 'id_titular', 'id_titular');
    }
}
