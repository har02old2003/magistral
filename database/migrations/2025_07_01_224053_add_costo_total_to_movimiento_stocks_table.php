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
        Schema::table('movimiento_stocks', function (Blueprint $table) {
            $table->decimal('costo_total', 10, 2)->nullable()->after('precio_costo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('movimiento_stocks', function (Blueprint $table) {
            $table->dropColumn('costo_total');
        });
    }
};
