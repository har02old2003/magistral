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
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->decimal('precio_compra', 10, 2);
            $table->decimal('precio_venta', 10, 2);
            $table->integer('stock_actual')->default(0);
            $table->integer('stock_minimo')->default(5);
            $table->string('lote');
            $table->date('fecha_vencimiento');
            $table->enum('meses_vencimiento', ['12', '18', '24'])->default('12');
            $table->string('presentacion')->nullable();
            $table->string('principio_activo')->nullable();
            $table->string('concentracion')->nullable();
            $table->string('laboratorio')->nullable();
            $table->string('registro_sanitario')->nullable();
            $table->boolean('requiere_receta')->default(false);
            $table->boolean('activo')->default(true);
            $table->foreignId('categoria_id')->constrained('categorias');
            $table->foreignId('marca_id')->constrained('marcas');
            $table->foreignId('proveedor_id')->constrained('proveedores');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
