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
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_delivery')->unique();
            $table->foreignId('venta_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('pedido_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('cliente_id')->constrained()->onDelete('cascade');
            $table->foreignId('repartidor_id')->nullable()->constrained('users')->onDelete('set null');
            $table->date('fecha_programada');
            $table->datetime('fecha_entrega')->nullable();
            $table->time('hora_salida')->nullable();
            $table->time('hora_entrega')->nullable();
            $table->text('direccion_entrega');
            $table->text('referencia_direccion')->nullable();
            $table->string('telefono_contacto');
            $table->decimal('costo_delivery', 8, 2);
            $table->enum('metodo_pago_delivery', ['efectivo', 'tarjeta', 'transferencia'])->default('efectivo');
            $table->enum('estado', ['programado', 'asignado', 'en_ruta', 'entregado', 'no_entregado', 'cancelado'])->default('programado');
            $table->text('observaciones')->nullable();
            $table->decimal('latitud', 10, 8)->nullable();
            $table->decimal('longitud', 11, 8)->nullable();
            $table->string('foto_entrega')->nullable();
            $table->text('firma_cliente')->nullable();
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
