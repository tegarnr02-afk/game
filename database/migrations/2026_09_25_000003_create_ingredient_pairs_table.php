<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('ingredient_pairs', function (Blueprint $table) {
      $table->id();
      $table->foreignId('box_id')->constrained()->cascadeOnDelete();
      $table->foreignId('ingredient_a_id')->constrained('ingredients')->cascadeOnDelete();
      $table->foreignId('ingredient_b_id')->constrained('ingredients')->cascadeOnDelete();
     $table->enum('status', ['hijau', 'kuning', 'merah']);
      $table->text('description');
      $table->string('title');
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('ingredient_pairs');
  }
};
