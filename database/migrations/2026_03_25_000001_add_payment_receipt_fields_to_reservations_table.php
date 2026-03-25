<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('reservations')) {
            return;
        }

        Schema::table('reservations', function (Blueprint $table) {
            if (!Schema::hasColumn('reservations', 'payment_receipt_path')) {
                $table->string('payment_receipt_path')->nullable();
            }

            if (!Schema::hasColumn('reservations', 'payment_uploaded_at')) {
                $table->timestamp('payment_uploaded_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('reservations')) {
            return;
        }

        Schema::table('reservations', function (Blueprint $table) {
            if (Schema::hasColumn('reservations', 'payment_uploaded_at')) {
                $table->dropColumn('payment_uploaded_at');
            }

            if (Schema::hasColumn('reservations', 'payment_receipt_path')) {
                $table->dropColumn('payment_receipt_path');
            }
        });
    }
};
