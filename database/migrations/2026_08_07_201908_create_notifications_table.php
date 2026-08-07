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
        if(!Schema::hasTable('notifications')) {
            Schema::create('notifications', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable(); // Recipient User/Admin ID
                $table->string('title')->nullable();
                $table->text('body')->nullable();
                $table->string('type')->default('system'); // order_created, order_cancelled, offer, payment
                $table->string('target_channel')->default('app'); // 'app', 'web_admin', 'all'
                $table->json('data')->nullable(); // Extra metadata: {"order_id": 105, "click_action": "/admin/orders/105"}
                $table->timestamp('read_at')->nullable();
                $table->timestamps();

                // High performance composite indexes
                $table->index(['user_id', 'read_at']);
                $table->index(['user_id', 'target_channel']);
                $table->index(['user_id', 'created_at']);
            });   
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if(Schema::hasTable('notifications')){
            Schema::dropIfExists('notifications');
        }
    }
};
