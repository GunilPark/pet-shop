<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dog_goods_orders', function (Blueprint $table) {
            $table->string('payment_token', 64)->nullable()->unique()->after('admin_memo');
            $table->enum('payment_status', ['unsent', 'sent', 'paid', 'expired'])->default('unsent')->after('payment_token');
            $table->enum('consultation_status', ['none', 'waiting', 'replied', 'resolved'])->default('none')->after('payment_status');
            $table->timestamp('preview_sent_at')->nullable()->after('consultation_status');
            $table->timestamp('payment_sent_at')->nullable()->after('preview_sent_at');
        });

        Schema::create('dog_goods_consultations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('dog_goods_orders')->cascadeOnDelete();
            $table->foreignId('admin_id')->constrained('users')->restrictOnDelete();
            $table->text('message')->nullable();
            $table->text('reply_message')->nullable();
            $table->string('preview_image')->nullable();
            $table->enum('status', ['pending', 'replied', 'resolved'])->default('pending');
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dog_goods_consultations');
        Schema::table('dog_goods_orders', function (Blueprint $table) {
            $table->dropColumn(['payment_token', 'payment_status', 'consultation_status', 'preview_sent_at', 'payment_sent_at']);
        });
    }
};
