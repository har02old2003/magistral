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
        Schema::create('movimiento_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade');
            $table->enum('tipo_movimiento', ['ingreso', 'egreso', 'ajuste', 'transferencia']);
            $table->integer('cantidad');
            $table->integer('stock_anterior');
            $table->integer('stock_actual');
            $table->decimal('precio_costo', 10, 2)->nullable();
            $table->string('lote', 50)->nullable();
            $table->date('fecha_vencimiento')->nullable();
            $table->date('fecha_movimiento');
            $table->string('motivo', 255)->nullable();
            $table->text('observaciones')->nullable();
            $table->string('documento_referencia', 100)->nullable();
            $table->foreignId('usuario_id')->constrained('users');
            $table->foreignId('proveedor_id')->nullable()->constrained('proveedores')->onDelete('set null');
            $table->timestamps();
            
            $table->index(['tipo_movimiento', 'fecha_movimiento']);
            $table->index(['producto_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimiento_stocks');
    }
};
