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
    Schema::table('boxes', function (Blueprint $table) {
        $table->string('condition_label_after')->nullable()->after('condition_label');
    });
}

public function down(): void
{
    Schema::table('boxes', function (Blueprint $table) {
        $table->dropColumn('condition_label_after');
    });
}
};
