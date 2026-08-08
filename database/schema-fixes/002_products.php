<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

if (Schema::hasTable('products')) {

    if (!Schema::hasColumn('products', 'created_by')) {

        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')
                ->nullable()
                ->after('updated_at')
                ->comment('Creator User ID');
        });
    }

    if (Schema::hasColumn('products', 'user_id')) {
        DB::statement("
            ALTER TABLE products
            MODIFY COLUMN user_id BIGINT UNSIGNED NULL
            COMMENT 'Foreign key reference to vendor (users.id)'
        ");
    }

}
