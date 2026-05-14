<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        \Illuminate\Support\Facades\DB::statement(
            "ALTER TABLE dog_goods_items MODIFY product_type ENUM('basic','nose_print_tag','silhouette_tag','silhouette_keychain','name_tag') NOT NULL DEFAULT 'basic'"
        );
    }

    public function down(): void
    {
        \Illuminate\Support\Facades\DB::statement(
            "ALTER TABLE dog_goods_items MODIFY product_type ENUM('basic','nose_print_tag','silhouette_tag','silhouette_keychain') NOT NULL DEFAULT 'basic'"
        );
    }
};
