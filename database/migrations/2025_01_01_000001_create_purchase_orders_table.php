<?php
// 2025_01_01_000005_create_purchase_orders_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('po_no', 50)->unique()->comment('Unique PO number (PO-2025-0001)');
            $table->foreignId('vendor_id')->comment('FK to users — supplier');
            $table->foreignId('warehouse_id')->comment('FK to warehouses — where goods will be received');

            $table->enum('status', ['draft', 'pending_approval', 'approved', 'partial', 'received', 'cancelled'])
                ->default('draft')
                ->comment('PO lifecycle status');

            $table->date('order_date')->comment('Date PO was created');
            $table->date('expected_date')->nullable()->comment('Expected delivery date');

            $table->decimal('subtotal', 12, 2)->default(0)->comment('Sum of line totals before discounts');
            $table->decimal('discount_amount', 12, 2)->default(0)->comment('Order-level discount');
            $table->decimal('tax_amount', 12, 2)->default(0)->comment('Total tax');
            $table->decimal('shipping_charge', 12, 2)->default(0)->comment('Shipping / freight charge');
            $table->decimal('total', 12, 2)->default(0)->comment('Final PO value');

            $table->string('currency', 3)->default('BDT');
            $table->text('notes')->nullable();
            $table->text('terms')->nullable()->comment('Terms & conditions');
            $table->boolean('is_editable')->default(true)->comment('Can this PO be edited?');
            $table->foreignId('created_by')->nullable()->comment('FK to users');
            $table->foreignId('approved_by')->nullable()->comment('FK to users who approved');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->index(['vendor_id', 'status']);
            $table->index('order_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
