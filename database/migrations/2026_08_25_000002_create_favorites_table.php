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
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('cascade');
                $table->foreignId('variation_id')->nullable()->constrained('variations')->onDelete('cascade');
                $table->string('type')->nullable()->comment('single, variable');
                $table->string('favorite_from')->nullable()->comment('retailer_app, web, sr etc.');
                $table->unsignedBigInteger('created_by')->nullable()->comment('created by user (staff/retailer)');

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
