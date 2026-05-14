<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dog_goods_items', function (Blueprint $table) {
            $table->enum('product_type', ['basic', 'nose_print', 'silhouette'])
                  ->default('basic')
                  ->after('name');
            $table->text('nose_print_guide')->nullable()->after('description');
            $table->text('silhouette_guide')->nullable()->after('nose_print_guide');
        });
    }

    public function down(): void
    {
        Schema::table('dog_goods_items', function (Blueprint $table) {
            $table->dropColumn('product_type');
        });
    }
};
