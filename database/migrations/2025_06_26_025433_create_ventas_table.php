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
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->string('numero_ticket')->unique();
            $table->datetime('fecha_venta');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('igv', 10, 2);
            $table->decimal('total', 10, 2);
            $table->enum('tipo_pago', ['efectivo', 'tarjeta', 'transferencia'])->default('efectivo');
            $table->enum('estado', ['completada', 'cancelada', 'pendiente'])->default('completada');
            $table->text('observaciones')->nullable();
            $table->foreignId('cliente_id')->nullable()->constrained('clientes');
            $table->foreignId('user_id')->constrained('users'); // Vendedor
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
