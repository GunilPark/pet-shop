<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('postal_code', 8)->nullable()->after('email');
            $table->string('prefecture', 20)->nullable()->after('postal_code');
            $table->string('city', 100)->nullable()->after('prefecture');
            $table->string('address_line', 200)->nullable()->after('city');
            $table->string('phone', 20)->nullable()->after('address_line');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['postal_code', 'prefecture', 'city', 'address_line', 'phone']);
        });
    }
};
