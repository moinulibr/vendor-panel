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
        Schema::table('users', function (Blueprint $table) {
            $table->tinyInteger('access_type')->default(1)->nullable()->comment('1 = enternal/official/staff, 2 = external/visitor')->after('email');
            $table->tinyInteger('user_type')->default(1)->nullable()->comment('1 = Admin, 2 = staff, 3 = retailer, 4 = others')->after('access_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'access_type',
                'user_type',
            ]);
        });
    }
};
