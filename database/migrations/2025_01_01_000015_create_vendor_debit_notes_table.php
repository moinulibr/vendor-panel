<?php
// 2025_01_01_000015_create_vendor_debit_notes_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendor_debit_notes', function (Blueprint $table) {
            $table->id();
            $table->string('debit_note_no', 50)->unique();
            $table->foreignId('vendor_id')->constrained('vendors')->restrictOnDelete();
            $table->foreignId('return_id')->constrained('purchase_returns')->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->enum('status', ['open', 'applied', 'closed'])->default('open');
            $table->foreignId('applied_to_bill_id')->nullable()->constrained('purchase_bills')->nullOnDelete();
            $table->timestamps();

            $table->index(['vendor_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_debit_notes');
    }
};
