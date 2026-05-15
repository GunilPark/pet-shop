<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('dog_goods_items', function (Blueprint $table) {
            $table->json('product_images')->nullable()->after('thumbnail_image');
        });
    }
    public function down(): void {
        Schema::table('dog_goods_items', function (Blueprint $table) {
            $table->dropColumn('product_images');
        });
    }
};
