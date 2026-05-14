<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE dog_goods_items MODIFY product_type ENUM('basic','nose_print_tag','silhouette_tag','silhouette_keychain') NOT NULL DEFAULT 'basic'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE dog_goods_items MODIFY product_type ENUM('basic','nose_print','silhouette') NOT NULL DEFAULT 'basic'");
    }
};
