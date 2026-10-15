<?php
// 2025_01_01_000005_create_purchase_bills_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_bills', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable()->comment('Original bill id when this is an edited version');
            $table->integer('version')->default(1);
            $table->boolean('is_current')->default(true)->comment('1 = active version, 0 = history');

            $table->string('bill_no', 50)->unique();
            $table->string('vendor_bill_no', 50)->nullable();
            $table->foreignId('po_id')->nullable()->constrained('purchase_orders')->nullOnDelete();
            $table->foreignId('grn_id')->nullable()->constrained('goods_receipts')->nullOnDelete();
            $table->foreignId('vendor_id')->constrained('vendors')->restrictOnDelete();
            $table->foreignId('warehouse_id')->constrained('warehouses')->restrictOnDelete();

            $table->date('bill_date');
            $table->date('due_date')->nullable();
            $table->enum('status', ['draft', 'confirmed', 'partially_paid', 'paid', 'returned', 'cancelled'])
                ->default('draft');

            $table->decimal('sub_total', 12, 2)->default(0);
            $table->decimal('item_total_disc', 12, 2)->default(0);
            $table->decimal('gross_total', 12, 2)->default(0);
            $table->enum('discount_type', ['percentage', 'fixed'])->nullable();
            $table->decimal('discount_value', 12, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('freight', 12, 2)->default(0);
            $table->decimal('landing_cost', 12, 2)->default(0);
            $table->decimal('round_off', 5, 2)->default(0);
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->decimal('paid_amount', 12, 2)->default(0);
            $table->decimal('due_amount', 12, 2)->default(0);

            $table->string('currency', 3)->default('BDT');
            $table->decimal('exchange_rate', 10, 4)->default(1);
            $table->text('notes')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['vendor_id', 'is_current', 'status']);
            $table->index(['parent_id', 'version']);
            $table->index('bill_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_bills');
    }
};
