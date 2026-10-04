<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('ingredients', function (Blueprint $table) {
      $table->id();
      $table->foreignId('box_id')->constrained()->cascadeOnDelete();
      $table->string('name');
      $table->string('icon_key', 10);
      $table->string('hint_text');
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('ingredients');
  }
};
