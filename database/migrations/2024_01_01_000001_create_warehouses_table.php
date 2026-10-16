<?php
// 2025_01_01_000001_create_warehouses_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150)->comment('Warehouse/Outlet/Store name');
            $table->string('code', 30)->unique()->comment('Unique code like WH-01, OUT-02');
            $table->enum('type', ['warehouse', 'outlet', 'store', 'transit'])
                ->default('warehouse')
                ->comment('Type of location');
            $table->enum('location_type', ['physical', 'virtual'])
                ->default('physical')
                ->comment('Virtual = online/dropship source');
            $table->string('address', 255)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('postal_code', 20)->nullable();
            $table->string('country', 100)->default('Bangladesh');
            $table->string('phone', 20)->nullable();
            $table->string('email', 100)->nullable();

            $table->foreignId('manager_id')->nullable()->comment('FK to users table — in-charge person');
            $table->foreignId('parent_id')->nullable()->comment('For sub-location hierarchy');

            $table->boolean('is_default')->default(false)->comment('Default warehouse for POS/sales');
            $table->boolean('is_active')->default(true);
            $table->boolean('allow_sale')->default(true)->comment('Can sale from this location?');
            $table->boolean('allow_purchase')->default(true)->comment('Can receive purchase here?');

            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['type', 'is_active']);
            $table->index('is_default');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warehouses');
    }
};
