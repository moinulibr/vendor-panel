<?php
// 2025_01_01_000004_create_payment_ledger_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_ledger', function (Blueprint $table) {
            $table->id();
            $table->enum('party_type', ['customer', 'vendor'])->comment('Who is this entry for');
            $table->unsignedBigInteger('party_id')->comment('customer_id or vendor_id based on party_type');

            $table->date('entry_date')->comment('Date of transaction');
            $table->timestamp('entry_at')->useCurrent()->comment('Exact timestamp');

            $table->enum('entry_type', [
                'sale',                     // Customer purchased (debit customer)
                'purchase',                 // We purchased from vendor (credit us)
                'payment_received',         // Customer paid us
                'payment_made',             // We paid vendor
                'refund_paid',              // We refunded customer
                'refund_received',          // Vendor refunded us
                'return_credit',            // Return adjustment
                'advance_paid',             // Advance from customer / to vendor
                'advance_adjusted',         // Advance applied to bill
                'credit_issued',            // Store credit issued
                'credit_used',              // Store credit used
                'discount_adjustment',      // Manual discount adjustment
                'write_off',                // Bad debt write-off
                'opening_balance',          // Opening balance entry
            ])->comment('Type of ledger entry');

            $table->string('reference_type', 50)->nullable()->comment('Model name: Sale, SalePayment, PurchaseBill, etc.');
            $table->unsignedBigInteger('reference_id')->nullable()->comment('ID of referenced record');
            $table->string('reference_no', 50)->nullable()->comment('Human-readable ref no (INV-001, PAY-002)');

            $table->decimal('debit', 14, 2)->default(0)->comment('Increase in balance (money owed to us / we owe)');
            $table->decimal('credit', 14, 2)->default(0)->comment('Decrease in balance');
            $table->decimal('running_balance', 14, 2)->default(0)->comment('Balance after this entry');
            $table->string('currency', 3)->default('BDT');
            $table->decimal('exchange_rate', 10, 4)->default(1);

            $table->text('description')->nullable()->comment('Human-readable description');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->comment('FK to users — who created this entry');
            $table->timestamps();

            $table->index(['party_type', 'party_id', 'entry_date']);
            $table->index(['reference_type', 'reference_id']);
            $table->index('entry_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_ledger');
    }
};
