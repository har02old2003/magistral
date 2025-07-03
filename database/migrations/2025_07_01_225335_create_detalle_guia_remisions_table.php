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
        Schema::create('detalle_guia_remisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guia_remision_id')->constrained()->onDelete('cascade');
            $table->foreignId('producto_id')->constrained()->onDelete('cascade');
            $table->integer('cantidad');
            $table->string('unidad_medida')->default('UND');
            $table->decimal('peso_unitario', 8, 3)->default(0);
            $table->decimal('peso_total', 8, 3)->default(0);
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_guia_remisions');
    }
};
