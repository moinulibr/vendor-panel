<?php
// 2025_01_01_000007_create_purchase_receipts_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_receipts', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_no', 50)->unique()->comment('Unique receipt number (PR-2025-0001)');
            $table->foreignId('po_id')->nullable()->comment('FK to purchase_orders (null for direct receipt without PO)');
            $table->foreignId('vendor_id')->comment('FK to users');
            $table->foreignId('warehouse_id')->comment('FK to warehouses — where goods are stored');

            $table->enum('status', ['draft', 'partial', 'received', 'rejected'])
                ->default('draft')
                ->comment('Receipt status');

            $table->date('received_date')->comment('Date goods were physically received');
            $table->string('challan_no', 100)->nullable()->comment('Vendor challan/delivery note number');
            $table->string('vehicle_no', 50)->nullable()->comment('Vehicle number for delivery');
            $table->string('driver_name', 100)->nullable()->comment('Driver name');
            $table->string('driver_phone', 20)->nullable();

            $table->decimal('total_qty_received', 12, 2)->default(0)->comment('Total qty received');
            $table->decimal('total_qty_rejected', 12, 2)->default(0)->comment('Total qty rejected');

            $table->text('notes')->nullable();
            $table->foreignId('received_by')->nullable()->comment('FK to users — who received');
            $table->timestamps();

            $table->index(['po_id', 'status']);
            $table->index(['vendor_id', 'received_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_receipts');
    }
};
