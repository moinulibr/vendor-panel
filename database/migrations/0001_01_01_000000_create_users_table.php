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
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // bigint unsigned, auto_increment, primary key
            $table->string('name', 255)->nullable(); // Not Null [v]
            //$table->tinyInteger('access_type')->default(1)->nullable()->comment('1 = enternal/official/staff, 2 = external/visitor');
            //$table->tinyInteger('user_type')->default(1)->nullable()->comment('1 = Admin, 2 = staff, 3 = retailer, 4 = others');
            $table->string('last_name', 255)->nullable(); // Nullable [ ]
            $table->string('email', 255)->unique()->nullable(); // Not Null [v], Unique
            $table->string('image', 255)->nullable(); // Nullable [ ]
            $table->string('mobile', 50)->nullable(); // Nullable [ ]
            $table->string('gender', 40)->default('male')->nullable(); // Default 'male'
            $table->date('dob')->nullable(); // Nullable [ ]
            $table->tinyInteger('status')->default(1)->nullable(); // Default 1
            $table->bigInteger('contact_id')->nullable(); // Nullable [ ]
            $table->timestamp('email_verified_at')->nullable(); // Nullable [ ]
            $table->string('password', 255)->nullable(); // স্ক্রিনশটে Nullable [ ] দেওয়া
            $table->rememberToken(); // varchar(100), Nullable
            $table->timestamps(); // created_at এবং updated_at (timestamp)
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
