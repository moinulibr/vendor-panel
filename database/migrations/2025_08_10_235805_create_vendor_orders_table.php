<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vendor_orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('transaction_id')->nullable();
            $table->bigInteger('vendor_id')->nullable();
            $table->string('invoice_no')->nullable();
            $table->string('shipping_status',40)->nullable()->default('pending');
            $table->string('payment_status',40)->nullable()->default('due');
            $table->string('discount_type',30)->nullable();
            $table->decimal('final_amount',12,2)->nullable()->default(0);
            $table->decimal('discount_amount',12,2)->nullable()->default(0);
            $table->decimal('shipping_charge',12,2)->nullable()->default(0);
            $table->dateTime('shipped_date')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_orders');
    }
};
