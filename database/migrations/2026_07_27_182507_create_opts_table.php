<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if(!Schema::hasTable('otps')){
            Schema::create('otps', function (Blueprint $table) {
                $table->id();
                $table->string('mobile', 15)->index();
                $table->string('code', 6);
                $table->string('purpose', 30); // login, register, reset_password
                $table->timestamp('expires_at');
                $table->boolean('is_used')->default(false);
                $table->tinyInteger('attempts')->default(0);
                $table->timestamps();

                $table->index(['mobile', 'purpose', 'is_used']);
            });
        }
        
    }

    public function down(): void
    {
        if(Schema::hasTable('otps')){
            Schema::dropIfExists('otps');
        }
    }
};