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
            $table->enum('channel', ['pos', 'web', 'app', 'sr'])->default('pos');
            $table->string('source_ref', 100)->nullable()->comment('External order id (ecommerce/SR)');
            $table->foreignId('customer_id')->nullable()->comment('Customer: Foreign key (FK) referencing the users.id table. However, for web-lead customers, the contact_id from the contacts table will be used instead.');

            $table->enum('customer_type', ['regular', 'dealer','special'])->default('regular');
            $table->foreignId('warehouse_id')->nullable();
            $table->foreignId('sales_person_id')->nullable()->comment('FK to users table.');

            $table->decimal('sub_total', 12, 2)->default(0)->comment('Calculated: sum of sub_total of all items.');
            $table->decimal('item_total_discount', 12, 2)->default(0)->comment('Calculated: sum of item_total_discount of all items.');
            $table->decimal('gross_total', 12, 2)->default(0)->comment('Calculated: sum of gross_total of all items.');
            $table->enum('discount_type', ['percentage', 'fixed'])->nullable();
            $table->decimal('discount_value', 12, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);

            $table->foreignId('coupon_id')->nullable()->constrained('coupons')->nullOnDelete();
            $table->enum('coupon_discount_type', ['percentage', 'fixed'])->nullable();
            $table->decimal('coupon_discount_value', 12, 2)->default(0);
            $table->decimal('coupon_discount_amount', 12, 2)->default(0);

            $table->decimal('shipping_charge', 12, 2)->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('round_off', 5, 2)->default(0);

            $table->decimal('final_amount', 12, 2)->default(0);
            $table->decimal('paid_amount', 12, 2)->default(0);
            $table->decimal('due_amount', 12, 2)->default(0);

            $table->enum('status', ['draft', 'confirmed', 'processing', 'completed', 'returned', 'cancelled', 'rejected', 'refunded'])
                ->default('draft')
                ->comment('Overall order or transaction lifecycle status');

            $table->enum('payment_status', ['unpaid', 'partially_paid', 'paid', 'overpaid', 'returned'])
                ->default('unpaid')
                ->comment('Financial payment status of the transaction');

            $table->enum('shipping_status', ['pending', 'processing', 'shipped', 'delivered', 'failed', 'returned'])
                ->default('pending')
                ->comment('Physical delivery or fulfillment status');
                
            $table->string('currency', 3)->default('BDT');
            $table->decimal('exchange_rate', 10, 4)->default(1);
            $table->text('notes')->nullable();

            $table->foreignId('created_by')->nullable()->comment('FK to users table.');
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
