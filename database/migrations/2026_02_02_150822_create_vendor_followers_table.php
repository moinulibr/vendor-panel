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
        if(!Schema::hasTable('vendor_followers')) {
            Schema::create('vendor_followers', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('vendor_id');
                $table->unsignedBigInteger('customer_id');
                $table->timestamps();

                $table->unique(['vendor_id', 'customer_id']);

                $table->foreign('vendor_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
            });
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('vendor_followers')) {
            Schema::dropIfExists('vendor_followers');
        }
    }
};
