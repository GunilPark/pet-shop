<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dog_goods_orders', function (Blueprint $table) {
            $table->unsignedSmallInteger('quantity')->default(1)->after('item_id');
            $table->string('shipping_name')->nullable()->after('quantity');
            $table->string('postal_code', 10)->nullable()->after('shipping_name');
            $table->string('prefecture', 20)->nullable()->after('postal_code');
            $table->string('city', 100)->nullable()->after('prefecture');
            $table->string('address_line', 200)->nullable()->after('city');
            $table->string('phone', 20)->nullable()->after('address_line');

            $table->index('postal_code');
        });
    }

    public function down(): void
    {
        Schema::table('dog_goods_orders', function (Blueprint $table) {
            $table->dropIndex(['postal_code']);
            $table->dropColumn(['quantity', 'shipping_name', 'postal_code', 'prefecture', 'city', 'address_line', 'phone']);
        });
    }
};
