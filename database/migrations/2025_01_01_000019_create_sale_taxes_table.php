<?php
// 2025_01_01_000019_create_sale_taxes_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sale_taxes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained('sales')->cascadeOnDelete();
            $table->foreignId('item_id')->constrained('sale_items')->cascadeOnDelete();
            $table->string('tax_name', 50);
            $table->string('tax_category', 50)->nullable();
            $table->string('hs_code', 20)->nullable();
            $table->decimal('tax_rate', 5, 2);
            $table->decimal('taxable_amount', 12, 2);
            $table->decimal('tax_amount', 12, 2);
            $table->timestamps();

            $table->index('sale_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_taxes');
    }
};
