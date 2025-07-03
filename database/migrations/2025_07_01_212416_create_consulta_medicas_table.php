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
        Schema::create('consulta_medicas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('historia_clinica_id')->constrained('historia_clinicas')->onDelete('cascade');
            $table->date('fecha_consulta');
            $table->time('hora_consulta');
            $table->text('motivo_consulta');
            $table->text('sintomas')->nullable();
            $table->text('examen_fisico')->nullable();
            $table->text('diagnostico');
            $table->text('tratamiento')->nullable();
            $table->text('medicamentos_recetados')->nullable();
            $table->text('recomendaciones')->nullable();
            $table->date('proxima_cita')->nullable();
            $table->text('observaciones')->nullable();
            $table->foreignId('medico_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('usuario_registro')->constrained('users');
            $table->timestamps();
            
            $table->index(['fecha_consulta']);
            $table->index(['historia_clinica_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consulta_medicas');
    }
};
