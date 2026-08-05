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
        if(!Schema::hasTable('user_device_tokens')) {
            Schema::create('user_device_tokens', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->text('fcm_token')->nullable();
                $table->string('device_type')->nullable(); // 'android', 'ios', 'web'
                $table->string('device_id')->nullable()->comment('Unique Hardware ID');   // Unique Hardware ID
                $table->timestamps();

                //same user same device no duplicate token allowed
                $table->unique(['user_id', 'device_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('user_device_tokens')) {
            Schema::dropIfExists('user_device_tokens');
        }
    }
};
