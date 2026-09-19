<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if(!Schema::hasTable('user_details')){
            Schema::create('user_details', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('user_id')->nullable();
                $table->tinyInteger('type')->nullable()->default(1)->comment('like purpose- 1 = web app user, 2 = mobile app user, 3 = other');
                $table->string('shop_name')->nullable();
                $table->string('trade_license')->nullable();
                $table->text('address')->nullable();
                $table->string('status')->default('active'); // active, pending, suspended
                $table->string('license_image')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        if(Schema::hasTable('user_details')){
            Schema::dropIfExists('user_details');
        }
    }
};