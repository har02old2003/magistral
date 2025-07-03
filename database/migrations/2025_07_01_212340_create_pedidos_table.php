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
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->string('numero_pedido', 50)->unique();
            $table->foreignId('cliente_id')->nullable()->constrained('clientes')->onDelete('set null');
            $table->foreignId('proveedor_id')->nullable()->constrained('proveedores')->onDelete('set null');
            $table->date('fecha_pedido');
            $table->date('fecha_entrega_estimada')->nullable();
            $table->date('fecha_entrega_real')->nullable();
            $table->enum('estado', ['pendiente', 'confirmado', 'preparando', 'en_camino', 'entregado', 'cancelado'])->default('pendiente');
            $table->enum('tipo_pedido', ['compra', 'venta', 'delivery']);
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('igv', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->text('observaciones')->nullable();
            $table->string('direccion_entrega', 500)->nullable();
            $table->string('telefono_contacto', 20)->nullable();
            $table->foreignId('usuario_id')->constrained('users');
            $table->boolean('activo')->default(true);
            $table->timestamps();
            
            $table->index(['estado', 'tipo_pedido']);
            $table->index(['fecha_pedido']);
            $table->index(['activo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
