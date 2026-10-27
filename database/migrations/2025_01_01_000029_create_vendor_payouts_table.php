<?php
// 2025_01_01_000029_create_vendor_payouts_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendor_payouts', function (Blueprint $table) {
            $table->id();
            $table->string('payout_no', 50)->unique()->comment('Unique payout number (VPO-2025-0001)');
            $table->foreignId('vendor_id')->comment('FK to users (vendor)');

            $table->date('period_from')->comment('Payout period start');
            $table->date('period_to')->comment('Payout period end');

            $table->integer('sale_count')->default(0)->comment('Number of sales included');
            $table->decimal('gross_sale_amount', 14, 2)->default(0)->comment('Total sale value');
            $table->decimal('total_commission', 14, 2)->default(0)->comment('Total platform commission');
            $table->decimal('total_return_amount', 14, 2)->default(0)->comment('Returns deducted');
            $table->decimal('adjustment_amount', 14, 2)->default(0)->comment('Manual adjustment (+/-)');
            $table->decimal('net_payable', 14, 2)->default(0)->comment('Final amount payable to vendor');

            $table->enum('status', ['draft', 'pending_approval', 'approved', 'processing', 'paid', 'rejected', 'cancelled'])
                ->default('draft')->comment('Payout lifecycle status');
            $table->enum('method', ['bank', 'bkash', 'nagad', 'cash', 'adjustment'])->nullable()
                ->comment('Payment method used');
            $table->string('reference_no', 100)->nullable()->comment('Bank/Bkash transaction reference');
            $table->timestamp('paid_at')->nullable()->comment('Actual payment timestamp');

            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->comment('FK to users — who created');
            $table->foreignId('approved_by')->nullable()->comment('FK to users — who approved');
            $table->timestamp('approved_at')->nullable();

            $table->timestamps();

            $table->index(['vendor_id', 'status']);
            $table->index('period_from');
            $table->index('period_to');
            $table->index('paid_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_payouts');
    }
};
