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
        Schema::create('contabilidad', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_asiento');
            $table->string('numero_asiento', 50);
            $table->enum('tipo_asiento', ['venta', 'compra', 'gasto', 'ingreso', 'ajuste', 'apertura', 'cierre']);
            $table->string('concepto', 500);
            $table->decimal('debe', 10, 2)->default(0);
            $table->decimal('haber', 10, 2)->default(0);
            $table->string('cuenta_contable', 20);
            $table->string('subcuenta', 20)->nullable();
            $table->string('centro_costo', 50)->nullable();
            $table->string('documento_referencia', 100)->nullable();
            $table->foreignId('venta_id')->nullable()->constrained('ventas')->onDelete('set null');
            $table->unsignedBigInteger('compra_id')->nullable();
            $table->enum('estado', ['borrador', 'contabilizado', 'anulado'])->default('borrador');
            $table->text('observaciones')->nullable();
            $table->foreignId('usuario_id')->constrained('users');
            $table->timestamps();
            
            $table->index(['fecha_asiento', 'tipo_asiento']);
            $table->index(['cuenta_contable']);
            $table->index(['estado']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contabilidad');
    }
};
