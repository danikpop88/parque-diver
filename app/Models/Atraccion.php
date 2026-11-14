<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Atraccion extends Model
{
    use HasUuids;

    protected $table = 'atracciones';
    protected $primaryKey = 'id_atraccion';

    protected $fillable = ['nombre', 'edad_minima', 'estatura_minima', 'imagen', 'id_sede'];

    protected $casts = [
        'estatura_minima' => 'decimal:2',
    ];

    public function sede(): BelongsTo
    {
        return $this->belongsTo(Sede::class, 'id_sede', 'id_sede');
    }
}
