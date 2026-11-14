<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Titular extends Model
{
    use HasUuids, Auditable;

    protected $table = 'titulares';
    protected $primaryKey = 'id_titular';

    protected $fillable = ['nombre', 'cedula', 'telefono'];

    public function visitantes(): HasMany
    {
        return $this->hasMany(Visitante::class, 'id_titular', 'id_titular');
    }

    public function entradas(): HasMany
    {
        return $this->hasMany(Entrada::class, 'id_titular', 'id_titular');
    }
}
