<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dog_goods_orders', function (Blueprint $table) {
            // カスタマイズオプション（JSON）
            $table->json('custom_options')->nullable()->after('admin_memo');
            // 相談申請フラグ
            $table->boolean('is_consultation')->default(false)->after('custom_options');
        });
    }

    public function down(): void
    {
        Schema::table('dog_goods_orders', function (Blueprint $table) {
            $table->dropColumn(['custom_options', 'is_consultation']);
        });
    }
};
