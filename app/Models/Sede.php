<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sede extends Model
{
    use HasUuids;

    protected $table = 'sedes';
    protected $primaryKey = 'id_sede';

    protected $fillable = ['nombre', 'ubicacion'];

    public function atracciones(): HasMany
    {
        return $this->hasMany(Atraccion::class, 'id_sede', 'id_sede');
    }
}
