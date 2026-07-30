<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if(!Schema::hasTable('retailer_shipping_addresses')){
            Schema::create('retailer_shipping_addresses', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('retailer_id')->nullable();
                $table->string('title')->nullable()->comment('e.g., Shop, Warehouse, Branch 1'); // e.g., Shop, Warehouse, Branch 1
                $table->string('contact_person')->nullable();
                $table->string('contact_mobile')->nullable();
                $table->text('address')->nullable();
                $table->string('division')->nullable();
                $table->string('district')->nullable();
                $table->string('upazila')->nullable();
                $table->integer('division_id')->nullable();
                $table->integer('district_id')->nullable();
                $table->integer('upazila_id')->nullable();
                $table->boolean('is_default')->default(false);
                $table->timestamp('deleted_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        if(Schema::hasTable('retailer_shipping_addresses')){
            Schema::dropIfExists('retailer_shipping_addresses');
        }
    }
};