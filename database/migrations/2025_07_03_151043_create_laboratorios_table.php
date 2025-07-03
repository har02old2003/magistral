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
        Schema::create('laboratorios', function (Blueprint $table) {
            $table->id();
            $table->string('numero_lote')->unique();
            $table->string('nombre_medicamento');
            $table->text('descripcion')->nullable();
            $table->string('formula_quimica')->nullable();
            $table->text('instrucciones_generales')->nullable();
            $table->integer('cantidad_producir')->default(1);
            $table->string('unidad_medida')->default('unidades');
            $table->decimal('temperatura_optima', 5, 2)->nullable();
            $table->integer('tiempo_fabricacion_minutos')->nullable();
            $table->string('equipos_requeridos')->nullable();
            $table->text('precauciones_seguridad')->nullable();
            $table->enum('estado', ['borrador', 'en_proceso', 'completado', 'cancelado'])->default('borrador');
            $table->dateTime('fecha_inicio')->nullable();
            $table->dateTime('fecha_fin')->nullable();
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('producto_id')->nullable()->constrained('productos')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laboratorios');
    }
};
