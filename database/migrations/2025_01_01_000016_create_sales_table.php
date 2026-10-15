<?php
// 2025_01_01_000016_create_sales_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable()->comment('Original sale id when this is an edited version');
            $table->integer('version')->default(1);
            $table->boolean('is_current')->default(true);

            $table->string('invoice_no', 50)->unique();
            $table->enum('channel', ['pos', 'web', 'app', 'dealer', 'sr'])->default('pos');
            $table->string('source_ref', 100)->nullable()->comment('External order id (ecommerce/SR)');
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->enum('customer_type', ['retail', 'regular', 'dealer'])->default('retail');
            $table->foreignId('warehouse_id')->constrained('warehouses')->restrictOnDelete();
            $table->foreignId('salesperson_id')->nullable()->constrained('users')->nullOnDelete();

            $table->enum('status', ['draft', 'confirmed', 'partially_paid', 'paid', 'overpaid', 'returned', 'cancelled'])
                ->default('draft');

            $table->decimal('sub_total', 12, 2)->default(0);
            $table->decimal('item_total_discount', 12, 2)->default(0);
            $table->decimal('gross_total', 12, 2)->default(0);
            $table->enum('discount_type', ['percentage', 'fixed'])->nullable();
            $table->decimal('discount_value', 12, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);

            $table->foreignId('coupon_id')->nullable()->constrained('coupons')->nullOnDelete();
            $table->decimal('coupon_discount', 12, 2)->default(0);
            $table->decimal('shipping_charge', 12, 2)->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('round_off', 5, 2)->default(0);

            $table->decimal('final_amount', 12, 2)->default(0);
            $table->decimal('paid_amount', 12, 2)->default(0);
            $table->decimal('due_amount', 12, 2)->default(0);

            $table->string('currency', 3)->default('BDT');
            $table->decimal('exchange_rate', 10, 4)->default(1);
            $table->text('notes')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['customer_id', 'is_current', 'status']);
            $table->index(['parent_id', 'version']);
            $table->index(['channel', 'status']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
