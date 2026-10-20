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
            $table->integer('version')->default(1)->comment('Version number like 1,2,3...');
            $table->boolean('is_current')->default(true)->comment('1 = active version, 0 = history');

            $table->string('invoice_no', 50)->nullable()->unique()->comment('Auto-generated invoice number');
            $table->enum('channel', ['admin_pos', 'web_ecommerce', 'mobile_dealer_app', 'sr'])
                ->default('admin_pos')
                ->comment('Order source channel'); // FIXED: moible → mobile

            $table->string('source_ref', 100)->nullable()->comment('External order id (ecommerce/SR)');

            $table->foreignId('customer_id')->nullable()
                ->comment('FK to users.id. For web-lead customers, use contacts.id instead.');

            $table->enum('customer_type', ['regular', 'dealer', 'special'])
                ->default('regular')
                ->comment('Which price tier applies');

            $table->foreignId('warehouse_id')->nullable()->comment('FK to warehouses table');
            $table->foreignId('sales_person_id')->nullable()->comment('FK to users table');

            $table->decimal('sub_total', 12, 2)->default(0)->comment('Sum of line_subtotal of all items');
            $table->decimal('item_total_discount', 12, 2)->default(0)->comment('Sum of line_discount of all items');
            $table->decimal('gross_total', 12, 2)->default(0)->comment('sub_total - item_total_discount');

            $table->enum('discount_type', ['percentage', 'fixed'])->nullable()->comment('Invoice-level discount type');
            $table->decimal('discount_value', 12, 2)->default(0)->comment('Input value (e.g. 10 for 10%)');
            $table->decimal('discount_amount', 12, 2)->default(0)->comment('Actual discount in currency');

            $table->foreignId('coupon_id')->nullable()->constrained('coupons')->nullOnDelete();
            $table->enum('coupon_discount_type', ['percentage', 'fixed'])->nullable();
            $table->decimal('coupon_discount_value', 12, 2)->default(0)->comment('Input value from coupon');
            $table->decimal('coupon_discount_amount', 12, 2)->default(0)->comment('Actual coupon discount');

            $table->decimal('shipping_charge', 12, 2)->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0)->comment('Total VAT from all lines');
            $table->decimal('round_off', 5, 2)->default(0)->comment('Round-off adjustment');

            $table->decimal('final_amount', 12, 2)->default(0)->comment('Total payable by customer');
            $table->decimal('paid_amount', 12, 2)->default(0)->comment('Cache: sum of successful payments');
            $table->decimal('due_amount', 12, 2)->default(0)->comment('final_amount - paid_amount');
            $table->dateTime('sale_date')->nullable()->comment('sale create date');
            $table->enum('status', ['draft', 'quotation', 'pending', 'confirmed', 'processing', 'completed', 'returned', 'cancelled', 'rejected', 'refunded'])
                ->default('draft')
                ->comment('Overall order/transaction lifecycle status');

            $table->enum('payment_status', ['unpaid', 'partially_paid', 'paid', 'overpaid', 'returned'])
                ->default('unpaid')
                ->comment('Financial payment status');

            $table->enum('shipping_status', ['pending', 'processing', 'shipped', 'delivered', 'failed', 'returned'])
                ->default('pending')
                ->comment('Physical delivery / fulfillment status');

            $table->string('currency', 3)->default('BDT');
            $table->decimal('exchange_rate', 10, 4)->default(1);
            $table->text('notes')->nullable();

            $table->boolean('is_editable_for_admin')->default(true)->comment('Can this sale be edited?');
            $table->boolean('is_editable_for_staff')->default(true)->comment('Can this sale be edited?');
            $table->boolean('is_editable_for_customer')->default(true)->comment('Can this sale be edited?');
            
            $table->foreignId('created_by')->nullable()->comment('FK to users table');
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
