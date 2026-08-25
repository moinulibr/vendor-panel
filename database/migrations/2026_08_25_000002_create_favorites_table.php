<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('favorites')) {
            Schema::create('favorites', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id');
                $table->foreignId('product_id');
                $table->foreignId('variation_id');
                $table->string('type')->nullable()->comment('single, variable');
                $table->timestamps();

                $table->unique(['user_id', 'product_id', 'variation_id'], 'favorite_product_variation_unique');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('favorites')) {
            Schema::dropIfExists('favorites');
        }
    }
};
