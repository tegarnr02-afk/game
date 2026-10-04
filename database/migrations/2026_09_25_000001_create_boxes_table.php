<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('boxes', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('condition_label');
      $table->boolean('is_locked')->default(false);
      $table->unsignedTinyInteger('order')->unique();
      $table->string('face_type'); // dry, oily, sensitive, aging
      $table->string('icon', 10)->default('✨');
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('boxes');
  }
};
