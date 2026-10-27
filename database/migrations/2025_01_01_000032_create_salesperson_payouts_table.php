<?php
// 2025_01_01_000032_create_salesperson_payouts_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salesperson_payouts', function (Blueprint $table) {
            $table->id();
            $table->string('payout_no', 50)->unique()->comment('Unique payout number (SPO-2025-0001)');
            $table->foreignId('salesperson_id')->comment('FK to users (employee/SR)');

            $table->date('period_from');
            $table->date('period_to');

            $table->integer('commission_count')->default(0)->comment('Number of commissions included');
            $table->decimal('gross_commission', 14, 2)->default(0)->comment('Sum of earned commissions');
            $table->decimal('total_reversed', 14, 2)->default(0)->comment('Reversed due to returns');
            $table->decimal('adjustment_amount', 14, 2)->default(0)->comment('Manual adjustment');
            $table->decimal('net_payable', 14, 2)->default(0)->comment('Final commission to pay');

            $table->enum('status', ['draft', 'approved', 'paid', 'cancelled'])->default('draft');
            $table->enum('method', ['bank', 'bkash', 'nagad', 'cash', 'adjustment'])->nullable();
            $table->string('reference_no', 100)->nullable();
            $table->timestamp('paid_at')->nullable();

            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable();
            $table->foreignId('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->index(['salesperson_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salesperson_payouts');
    }
};
