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
Schema::create('recipes', function (Blueprint $table) {
    $table->id();
    $table->foreignId('menu_item_id')->constrained()->onDelete('cascade');
    $table->foreignId('inventory_item_id')->constrained()->onDelete('cascade');
    $table->decimal('quantity', 8, 2); // e.g. 1.5
    $table->string('unit'); // matches inventory (kg, pcs, liters)
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
