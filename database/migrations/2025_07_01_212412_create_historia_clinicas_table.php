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
        Schema::create('historia_clinicas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->string('numero_historia', 50)->unique();
            $table->date('fecha_apertura');
            $table->text('antecedentes_personales')->nullable();
            $table->text('antecedentes_familiares')->nullable();
            $table->text('alergias_conocidas')->nullable();
            $table->text('medicamentos_actuales')->nullable();
            $table->string('grupo_sanguineo', 10)->nullable();
            $table->decimal('peso', 5, 2)->nullable();
            $table->decimal('altura', 5, 2)->nullable();
            $table->date('fecha_ultima_consulta')->nullable();
            $table->text('observaciones_generales')->nullable();
            $table->boolean('activo')->default(true);
            $table->foreignId('usuario_creador')->constrained('users');
            $table->timestamps();
            
            $table->index(['cliente_id']);
            $table->index(['fecha_apertura']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historia_clinicas');
    }
};
