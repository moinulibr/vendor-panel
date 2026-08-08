<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

if (Schema::hasTable('users')) {

    if (!Schema::hasColumn('users', 'created_by')) {

        Schema::table('users', function (Blueprint $table) {

            $table->unsignedBigInteger('created_by')
                ->nullable()
                ->after('updated_at')
                ->comment('Creator User ID');
        });
    }

    if (!Schema::hasColumn('users', 'deleted_at')) {
        Schema::table('users', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    if (!Schema::hasColumn('users', 'mobile_verified_at')) {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('mobile_verified_at')->nullable();
        });
    }

    if (Schema::hasColumn('users', 'user_type')) {
        DB::statement("
            ALTER TABLE users
            MODIFY COLUMN user_type TINYINT(1)
            COMMENT 'ADMIN = 1; STAFF = 2; VENDOR = 3; SR = 4; RETAILER = 5; ECOMMERCE_CUSTOMER = 6; POS_CUSTOMER = 7; RESELLER = 8; DELIVERY_MAN = 9; PLUMBER = 10; GUEST = 11; OTHERS = 12'
        ");
    }


    if (Schema::hasColumn('users', 'status')) {
        DB::statement("
            ALTER TABLE users
            MODIFY COLUMN status TINYINT(1)
            COMMENT '1 = active, 2 = inactive, 3 = suspend, 4 = deleted; 5 = blocked'
        ");
    }
}
