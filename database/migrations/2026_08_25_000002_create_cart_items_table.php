<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained('carts')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('variation_id')->nullable()->constrained('variations')->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 15, 4)->default(0);
            $table->decimal('discount_amount', 15, 4)->default(0);
            $table->string('discount_type')->nullable();
            $table->foreignId('discount_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->unique(['cart_id', 'product_id', 'variation_id'], 'cart_product_variation_unique');
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
