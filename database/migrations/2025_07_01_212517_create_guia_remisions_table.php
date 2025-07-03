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
        Schema::create('guia_remisions', function (Blueprint $table) {
            $table->id();
            $table->string('numero_guia')->unique();
            $table->foreignId('cliente_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('proveedor_id')->nullable()->constrained('proveedores')->onDelete('set null');
            $table->string('destinatario');
            $table->text('direccion_destino');
            $table->string('tipo_traslado')->default('venta'); // venta, compra, traslado
            $table->enum('estado', ['emitida', 'en_transito', 'entregada', 'anulada'])->default('emitida');
            $table->date('fecha_emision');
            $table->date('fecha_traslado')->nullable();
            $table->string('transportista')->nullable();
            $table->string('ruc_transportista')->nullable();
            $table->string('placa_vehiculo')->nullable();
            $table->text('observaciones')->nullable();
            $table->decimal('peso_total', 8, 2)->default(0);
            $table->integer('cantidad_bultos')->default(1);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guia_remisions');
    }
};
