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
        Schema::create('sms_settings', function (Blueprint $table) {
            $table->id();
    
            $table->string('method', 100)->nullable();
            $table->string('url')->nullable();
            $table->string('message', 100)->nullable();
            $table->text('send_to')->nullable();
            $table->text('key_1')->nullable();
            $table->text('key_2')->nullable();
            $table->text('key_3')->nullable();
            $table->text('key_value_1')->nullable();
            $table->text('key_value_2')->nullable();
            $table->text('key_value_3')->nullable();
            $table->tinyInteger('status')->nullable()->default(1);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_settings');
    }
};
