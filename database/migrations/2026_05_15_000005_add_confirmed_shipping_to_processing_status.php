<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE dog_goods_orders MODIFY processing_status ENUM('pending','reviewing','confirmed','processing','shipping','completed','rejected') NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE dog_goods_orders MODIFY processing_status ENUM('pending','reviewing','processing','completed','rejected') NOT NULL DEFAULT 'pending'");
    }
};
