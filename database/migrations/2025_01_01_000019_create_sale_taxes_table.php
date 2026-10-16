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
            $table->foreignId('sale_id')->comment('FK to sales table'); // FIXED: removed nullable
            $table->foreignId('sale_item_id')->nullable()->comment('FK to sale_items table (null for invoice-level tax)');

            $table->string('tax_name', 50)->nullable()->comment('e.g. VAT, GST, AIT');
            $table->string('tax_category', 50)->nullable()->comment('e.g. Standard 5%, Luxury 15%');
            $table->string('hs_code', 20)->nullable()->comment('Harmonized System Code (Mushak)');

            $table->decimal('tax_rate', 5, 2)->default(0)->comment('Tax rate in %');
            $table->decimal('taxable_amount', 12, 2)->default(0)->comment('Amount on which tax applied');
            $table->decimal('tax_amount', 12, 2)->default(0)->comment('Actual tax');

            $table->timestamps();

            $table->index('sale_id');
            $table->index('sale_item_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_taxes');
    }
};
