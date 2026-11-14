<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('atracciones', function (Blueprint $table) {
            $table->uuid('id_atraccion')->primary();
            $table->string('nombre', 150);
            $table->unsignedSmallInteger('edad_minima');
            $table->decimal('estatura_minima', 4, 2); // metros, ej. 1.20
            $table->string('imagen')->nullable();     // para subir imágenes
            $table->foreignUuid('id_sede')->constrained('sedes', 'id_sede');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('atracciones');
    }
};
