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
        Schema::table('recipes', function (Blueprint $table) {
            // Drop foreign key and column
            $table->dropForeign(['menu_item_id']);
            $table->dropColumn('menu_item_id');

            // Add new column
            $table->string('menu_item_name');

            // Update unique constraint
            $table->dropUnique(['menu_item_id', 'inventory_item_id']);
            $table->unique(['menu_item_name', 'inventory_item_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recipes', function (Blueprint $table) {
            // Drop new unique constraint
            $table->dropUnique(['menu_item_name', 'inventory_item_id']);

            // Drop new column
            $table->dropColumn('menu_item_name');

            // Add back old column and constraints
            $table->foreignId('menu_item_id')->constrained()->cascadeOnDelete();
            $table->unique(['menu_item_id', 'inventory_item_id']);
        });
    }
};
