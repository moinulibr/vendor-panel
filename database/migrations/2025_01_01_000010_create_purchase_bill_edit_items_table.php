<?php
// 2025_01_01_000010_create_purchase_bill_edit_items_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_bill_edit_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bill_id')->constrained('purchase_bills')->cascadeOnDelete();
            $table->unsignedBigInteger('bill_item_id')->nullable()->comment('Nullable for removed/added items');
            $table->enum('change_type', ['added', 'removed', 'qty_increase', 'qty_decrease', 'price_change', 'discount_change']);
            $table->integer('old_qty')->nullable();
            $table->integer('new_qty')->nullable();
            $table->decimal('old_price', 12, 2)->nullable();
            $table->decimal('new_price', 12, 2)->nullable();
            $table->decimal('old_discount', 12, 2)->nullable();
            $table->decimal('new_discount', 12, 2)->nullable();
            $table->timestamp('changed_at')->useCurrent();
            $table->timestamps();

            $table->index('bill_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_bill_edit_items');
    }
};
