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
        Schema::create('proformas', function (Blueprint $table) {
            $table->id();
            $table->string('numero_proforma', 50)->unique();
            $table->foreignId('cliente_id')->nullable()->constrained('clientes')->onDelete('set null');
            $table->date('fecha_proforma');
            $table->date('fecha_vencimiento');
            $table->enum('estado', ['pendiente', 'aprobada', 'rechazada', 'convertida', 'vencida'])->default('pendiente');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('igv', 10, 2);
            $table->decimal('total', 10, 2);
            $table->text('observaciones')->nullable();
            $table->text('condiciones')->nullable();
            $table->string('contacto', 200)->nullable();
            $table->foreignId('venta_id')->nullable()->constrained('ventas')->onDelete('set null');
            $table->foreignId('usuario_id')->constrained('users');
            $table->boolean('activo')->default(true);
            $table->timestamps();
            
            $table->index(['estado', 'fecha_proforma']);
            $table->index(['cliente_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proformas');
    }
};
