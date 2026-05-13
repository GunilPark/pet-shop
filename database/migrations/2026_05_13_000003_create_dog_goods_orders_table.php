<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dog_goods_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('dog_profile_id')->constrained()->restrictOnDelete();
            $table->foreignId('item_id')->constrained('dog_goods_items')->restrictOnDelete();
            $table->enum('order_status', [
                'pending', 'paid', 'preparing', 'shipping', 'delivered', 'canceled',
            ])->default('pending');
            $table->enum('processing_status', [
                'pending', 'reviewing', 'processing', 'completed', 'rejected',
            ])->default('pending');
            $table->string('uploaded_image')->nullable();
            $table->string('processed_image')->nullable();
            $table->text('admin_memo')->nullable();
            $table->timestamp('ordered_at')->useCurrent();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['order_status', 'processing_status']);
            $table->index(['user_id', 'ordered_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dog_goods_orders');
    }
};
