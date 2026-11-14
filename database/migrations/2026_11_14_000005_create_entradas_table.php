<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entradas', function (Blueprint $table) {
            $table->uuid('id_entrada')->primary();
            $table->string('codigo', 20)->unique();
            $table->decimal('precio', 10, 2);
            $table->boolean('reingreso')->default(false);
            $table->timestamp('fecha')->useCurrent(); // fecha de compra
            $table->foreignUuid('id_titular')->constrained('titulares', 'id_titular');
            $table->timestamps();

            $table->foreignId('id_usuario_creacion')->constrained('users');
            $table->foreignId('id_usuario_edita')->nullable()->constrained('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entradas');
    }
};
