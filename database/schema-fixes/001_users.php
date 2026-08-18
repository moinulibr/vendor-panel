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
            COMMENT 'ADMIN = 1; STAFF = 2; VENDOR = 3; SR = 4; RETAILER = 5; SUPPLIER = 6; ECOMMERCE_CUSTOMER = 7; POS_CUSTOMER = 8; RESELLER = 9; DELIVERY_MAN = 10; PLUMBER = 11; GUEST = 12; OTHERS = 13'
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


//User address table
if (Schema::hasTable('user_addresses')) {

    if (!Schema::hasColumn('user_addresses', 'created_by')) {
        Schema::table('user_addresses', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')
                ->nullable()
                ->after('updated_at')
                ->comment('Creator User ID');
        });
    }

    if (!Schema::hasColumn('user_addresses', 'deleted_at')) {
        Schema::table('user_addresses', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    if (!Schema::hasColumn('user_addresses', 'user_type')) {
        Schema::table('user_addresses', function (Blueprint $table) {
            $table->tinyInteger('user_type')
                ->default(2)
                ->after('user_id')
                ->comment('ADMIN = 1; STAFF = 2; VENDOR = 3; SR = 4; RETAILER = 5; SUPPLIER = 6; ECOMMERCE_CUSTOMER = 7; POS_CUSTOMER = 8; RESELLER = 9; DELIVERY_MAN = 10; PLUMBER = 11; GUEST = 12; OTHERS = 13');
        });
    }


}


//Retailer shipping address table
if (Schema::hasTable('retailer_shipping_addresses')) {

    if (!Schema::hasColumn('retailer_shipping_addresses', 'created_by')) {
        Schema::table('retailer_shipping_addresses', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')
                ->nullable()
                ->after('updated_at')
                ->comment('Creator User ID');
        });
    }

    if (!Schema::hasColumn('retailer_shipping_addresses', 'area')) {
        Schema::table('retailer_shipping_addresses', function (Blueprint $table) {
            $table->text('area')
                ->nullable()
                ->after('address')
                ->comment('area field for retailer shipping address');
        });
    }

}