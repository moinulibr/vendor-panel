<?php
// 2025_01_01_000031_create_salesperson_commissions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salesperson_commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('salesperson_id')->comment('FK to users (employee/SR)');
            $table->foreignId('sale_id')->comment('FK to sales');
            $table->foreignId('sale_item_id')->comment('FK to sale_items');
            $table->string('invoice_no', 50)->nullable()->comment('Invoice number snapshot');
            $table->date('sale_date')->comment('Sale date snapshot');

            $table->decimal('base_amount', 12, 2)->default(0)->comment('Base amount for commission calculation');
            $table->enum('commission_type', ['percentage', 'fixed'])->nullable();
            $table->decimal('commission_value', 8, 2)->default(0)->comment('Commission rate/value');
            $table->decimal('commission_amount', 12, 2)->default(0)->comment('Actual commission earned');

            $table->enum('status', ['pending', 'approved', 'paid', 'cancelled', 'reversed'])
                ->default('pending')
                ->comment('pending=earned not paid; paid=paid via payout');

            $table->foreignId('payout_id')->nullable()->comment('FK to salesperson_payouts');
            $table->timestamp('paid_at')->nullable();

            $table->decimal('reversed_amount', 12, 2)->default(0)
                ->comment('Amount reversed due to returns');

            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['salesperson_id', 'status']);
            $table->index('sale_id');
            $table->index('sale_item_id');
            $table->index('payout_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salesperson_commissions');
    }
};
