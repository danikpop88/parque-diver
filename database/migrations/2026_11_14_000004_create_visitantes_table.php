<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visitantes', function (Blueprint $table) {
            $table->uuid('id_visitante')->primary();
            $table->string('nombre_visitante', 150);
            $table->unsignedSmallInteger('edad');
            $table->decimal('estatura', 4, 2); // metros
            $table->foreignUuid('id_titular')->constrained('titulares', 'id_titular');
            $table->timestamps();

            $table->foreignId('id_usuario_creacion')->constrained('users');
            $table->foreignId('id_usuario_edita')->nullable()->constrained('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitantes');
    }
};
