<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('paso_laboratorios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laboratorio_id')->constrained('laboratorios')->onDelete('cascade');
            $table->integer('orden_paso');
            $table->string('titulo_paso');
            $table->text('descripcion_paso');
            $table->text('instrucciones_detalladas')->nullable();
            $table->integer('tiempo_estimado_minutos')->nullable();
            $table->string('equipos_necesarios')->nullable();
            $table->text('materiales_requeridos')->nullable();
            $table->text('observaciones')->nullable();
            $table->boolean('completado')->default(false);
            $table->dateTime('fecha_completado')->nullable();
            $table->foreignId('usuario_completo')->nullable()->constrained('users')->onDelete('set null');
            $table->text('notas_completado')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paso_laboratorios');
    }
};
