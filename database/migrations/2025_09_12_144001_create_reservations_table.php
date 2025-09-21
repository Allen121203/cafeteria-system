<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
Schema::create('reservations', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->date('date');
    $table->time('time'); // ✅ Added time column
    $table->integer('guests');
    $table->enum('status', ['pending', 'approved', 'declined'])->default('pending');
    $table->timestamps();
});

    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
