<?php
// 2025_01_01_000012_create_purchase_bill_edit_history_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_bill_edit_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bill_id')->comment('FK to purchase_bills (original bill id)');
            $table->integer('version_from')->comment('From version');
            $table->integer('version_to')->comment('To version');

            $table->enum('change_type', [
                'qty_changed',
                'item_added',
                'item_removed',
                'price_changed',
                'discount_changed',
                'freight_changed',
                'tax_changed',
                'combined',
                'other',
            ])->comment('Type of edit');

            $table->decimal('delta_amount', 12, 2)->default(0)->comment('Change in total_amount (new - old)');
            $table->text('reason')->nullable()->comment('Why was edit made');
            $table->foreignId('changed_by')->nullable()->comment('FK to users');
            $table->timestamp('changed_at')->useCurrent();
            $table->timestamps();

            $table->index('bill_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_bill_edit_history');
    }
};
