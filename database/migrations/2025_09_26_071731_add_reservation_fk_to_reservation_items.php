<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('reservation_items', function (Blueprint $table) {
            // add reservation_id if it's missing
            if (!Schema::hasColumn('reservation_items', 'reservation_id')) {
                $table->foreignId('reservation_id')
                      ->after('id')
                      ->constrained('reservations')
                      ->cascadeOnDelete();
            }

            // ensure menu_id exists too (Eloquent also expects this)
            if (!Schema::hasColumn('reservation_items', 'menu_id')) {
                $table->foreignId('menu_id')
                      ->after('reservation_id')
                      ->constrained('menus')
                      ->cascadeOnDelete();
            }

            // ensure quantity exists
            if (!Schema::hasColumn('reservation_items', 'quantity')) {
                $table->unsignedInteger('quantity')->default(1)->after('menu_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('reservation_items', function (Blueprint $table) {
            if (Schema::hasColumn('reservation_items', 'reservation_id')) {
                $table->dropForeign(['reservation_id']);
                $table->dropColumn('reservation_id');
            }
            if (Schema::hasColumn('reservation_items', 'menu_id')) {
                $table->dropForeign(['menu_id']);
                $table->dropColumn('menu_id');
            }
            if (Schema::hasColumn('reservation_items', 'quantity')) {
                $table->dropColumn('quantity');
            }
        });
    }
};
