<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

if (Schema::hasTable('orders')) {

    if (!Schema::hasColumn('orders', 'approved_by')) {

        Schema::table('orders', function (Blueprint $table) {

            $table->unsignedBigInteger('approved_by')
                ->nullable()
                ->after('status')
                ->comment('Approved User ID');
        });
    }
}
