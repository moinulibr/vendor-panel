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
        Schema::create('site_visits', function (Blueprint $table) {
            $table->id();
            
            $table->string('project_name')->nullable();
            $table->string('ref_no')->nullable();
            $table->text('address')->nullable();
            $table->text('description')->nullable();
            $table->text('note')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('mobile')->nullable();
            $table->date('visiting_date')->nullable();
            $table->date('next_visiting_date')->nullable();
            $table->tinyInteger('status')->nullable()->default(1);
            $table->tinyInteger('is_new')->nullable()->default(0);
            $table->bigInteger('user_id')->nullable();
        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_visits');
    }
};
