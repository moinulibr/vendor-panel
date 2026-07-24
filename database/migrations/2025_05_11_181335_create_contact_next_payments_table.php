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
        Schema::create('contact_next_payments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('contact_id')->nullable();
            $table->dateTime('next_payment_date')->nullable();
            $table->decimal('next_due_amount', 12, 2)->nullable()->default(0);
            $table->bigInteger('transaction_id')->nullable();
            //$table->string('invoice_no')->nullable();
            $table->string('note')->nullable();
            
            $table->dateTime('current_date')->nullable();
            $table->decimal('current_reveived_amount', 12, 2)->nullable()->default(0);
            $table->string('current_note')->nullable();

            $table->tinyInteger('status')->nullable()->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
