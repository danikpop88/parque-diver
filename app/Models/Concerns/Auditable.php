<?php

namespace App\Models\Concerns;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

/**
 * Llena automáticamente id_usuario_creacion / id_usuario_edita
 * con el usuario autenticado al crear o editar.
 */
trait Auditable
{
    public static function bootAuditable(): void
    {
        static::creating(function ($model) {
            if (Auth::check()) {
                $model->id_usuario_creacion ??= Auth::id();
            }
        });

        static::updating(function ($model) {
            if (Auth::check()) {
                $model->id_usuario_edita = Auth::id();
            }
        });
    }

    public function usuarioCreacion(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario_creacion');
    }

    public function usuarioEdita(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario_edita');
    }
}
