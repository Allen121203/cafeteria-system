<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void {
    Schema::create('reservation_items', function (Blueprint $t) {
      $t->id();
      $t->foreignId('reservation_id')->constrained()->cascadeOnDelete();
      $t->foreignId('menu_id')->constrained()->cascadeOnDelete();
      $t->unsignedInteger('quantity')->default(1); // number of bundles ordered
      $t->timestamps();
      $t->unique(['reservation_id','menu_id']);
    });
  }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
