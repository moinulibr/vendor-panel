<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if(!Schema::hasTable('shipping_addresses')){
            Schema::create('shipping_addresses', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable();
                $table->foreignId('user_detail_id')->nullable();
                $table->tinyInteger('type')->nullable()->default(1)->comment('1 = web app admin (pos) user, 2 = dealer mobile app user, 3 = SR Panel Web user, 4 = ecommerce user, 5 = other');
                $table->string('title')->nullable()->comment('e.g., Shop, Warehouse, Branch 1'); // e.g., Shop, Warehouse, Branch 1
                $table->string('contact_person')->nullable();
                $table->string('contact_mobile')->nullable();
                $table->text('address')->nullable();
                $table->text('area')->nullable()->comment('area field for mobile (dealer) app shipping address');
                $table->string('division')->nullable();
                $table->string('district')->nullable();
                $table->string('upazila')->nullable();
                $table->integer('division_id')->nullable();
                $table->integer('district_id')->nullable();
                $table->integer('upazila_id')->nullable();
                $table->boolean('is_default')->default(false);
                $table->foreignId('created_by')->nullable()->comment('Creator User ID');
                $table->string('status')->default('active'); // active, pending, suspended
                $table->timestamp('deleted_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        if(Schema::hasTable('shipping_addresses')){
            Schema::dropIfExists('shipping_addresses');
        }
    }
};