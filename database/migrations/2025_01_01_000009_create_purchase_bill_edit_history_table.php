<?php
// 2025_01_01_000009_create_purchase_bill_edit_history_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_bill_edit_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bill_id')->constrained('purchase_bills')->cascadeOnDelete();
            $table->integer('version_from');
            $table->integer('version_to');
            $table->enum('change_type', [
                'qty_changed',
                'item_added',
                'item_removed',
                'price_changed',
                'discount_changed',
                'freight_changed',
                'combined'
            ]);
            $table->decimal('delta_amount', 12, 2)->default(0);
            $table->text('reason')->nullable();
            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
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
