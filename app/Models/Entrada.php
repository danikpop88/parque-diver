<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Entrada extends Model
{
    use HasUuids, Auditable;

    protected $table = 'entradas';
    protected $primaryKey = 'id_entrada';

    protected $fillable = ['codigo', 'precio', 'reingreso', 'fecha', 'id_titular'];

    protected $casts = [
        'precio' => 'decimal:2',
        'reingreso' => 'boolean',
        'fecha' => 'datetime',
    ];

    public function titular(): BelongsTo
    {
        return $this->belongsTo(Titular::class, 'id_titular', 'id_titular');
    }
}
