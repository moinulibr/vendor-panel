<?php
// 2025_01_01_000023_create_sale_edit_history_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sale_edit_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->comment('FK to sales table');
            $table->integer('version_from');
            $table->integer('version_to');
            $table->enum('change_type', [
                'qty_changed',
                'item_added',
                'item_removed',
                'price_changed',
                'discount_changed',
                'shipping_changed',
                'combined',
                'other',
            ]);
            $table->decimal('delta_amount', 12, 2)->default(0);
            $table->text('reason')->nullable();
            $table->foreignId('changed_by')->nullable()->comment('FK to users table');
            $table->timestamp('changed_at')->useCurrent();
            $table->timestamps();

            $table->index('sale_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_edit_history');
    }
};
