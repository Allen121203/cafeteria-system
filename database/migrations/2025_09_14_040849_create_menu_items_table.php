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
Schema::create('menu_items', function (Blueprint $table) {
    $table->id();
    $table->foreignId('menu_id')->constrained()->onDelete('cascade');
    $table->string('name');   // e.g. Iced Coffee
    $table->enum('type', ['food','drink','dessert']); // to distinguish
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
