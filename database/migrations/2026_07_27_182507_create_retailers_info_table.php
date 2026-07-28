<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if(!Schema::hasTable('retailers')){
            Schema::create('retailers', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('user_id')->nullable();
                $table->string('shop_name')->nullable();
                $table->string('trade_license')->nullable();
                $table->text('address')->nullable();
                $table->string('status')->default('active'); // active, pending, suspended
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        if(Schema::hasTable('retailers')){
            Schema::dropIfExists('retailers');
        }
    }
};