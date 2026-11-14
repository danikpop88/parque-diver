<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('titulares', function (Blueprint $table) {
            $table->uuid('id_titular')->primary();
            $table->string('nombre', 150);
            $table->string('cedula', 20)->unique();
            $table->string('telefono', 20)->nullable();
            $table->timestamps(); // created_at = fecha_creacion, updated_at = fecha_edicion

            // Auditoría. Si users.id NO es bigint, cambia foreignId por foreignUuid.
            $table->foreignId('id_usuario_creacion')->constrained('users');
            $table->foreignId('id_usuario_edita')->nullable()->constrained('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('titulares');
    }
};
