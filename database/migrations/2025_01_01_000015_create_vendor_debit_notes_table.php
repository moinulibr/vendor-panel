<?php
// 2025_01_01_000018_create_vendor_debit_notes_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendor_debit_notes', function (Blueprint $table) {
            $table->id();
            $table->string('debit_note_no', 50)->unique()->comment('Unique debit note number (DN-2025-0001)');
            $table->foreignId('vendor_id')->comment('FK to vendors');
            $table->foreignId('return_id')->nullable()->comment('FK to purchase_returns (source)');

            $table->decimal('amount', 14, 2)->comment('Debit note amount');
            $table->enum('status', ['open', 'applied', 'closed'])->default('open')
                ->comment('open = not yet applied, applied = adjusted to bill, closed = settled');
            $table->foreignId('applied_to_bill_id')->nullable()->comment('FK to purchase_bills — which bill it was applied to');
            $table->timestamp('applied_at')->nullable();

            $table->text('reason')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->comment('FK to users');
            $table->timestamps();

            $table->index(['vendor_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_debit_notes');
    }
};
