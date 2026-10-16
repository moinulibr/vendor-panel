<?php
// 2025_01_01_000011_create_purchase_bill_taxes_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_bill_taxes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bill_id')->comment('FK to purchase_bills');
            $table->foreignId('item_id')->nullable()->comment('FK to purchase_bill_items (null for bill-level tax)');

            $table->string('tax_name', 50)->comment('e.g. VAT, AIT, Customs Duty');
            $table->string('tax_category', 50)->nullable()->comment('e.g. Standard 5%');
            $table->string('hs_code', 20)->nullable()->comment('Harmonized System Code (Mushak)');

            $table->decimal('tax_rate', 5, 2)->default(0)->comment('Tax rate %');
            $table->decimal('taxable_amount', 12, 2)->default(0)->comment('Amount tax is applied on');
            $table->decimal('tax_amount', 12, 2)->default(0)->comment('Actual tax amount');

            $table->timestamps();

            $table->index('bill_id');
            $table->index('item_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_bill_taxes');
    }
};
