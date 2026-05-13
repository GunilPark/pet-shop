<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dog_event_applies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('dog_goods_events')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('dog_profile_id')->constrained()->restrictOnDelete();
            $table->enum('apply_status', ['applied', 'approved', 'rejected', 'canceled'])->default('applied');
            $table->timestamp('applied_at')->useCurrent();
            $table->timestamps();
            $table->softDeletes();

            // 同一イベントへの重複申請防止（犬単位）
            $table->unique(['event_id', 'dog_profile_id']);
            $table->index(['event_id', 'apply_status']);
            $table->index(['user_id', 'applied_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dog_event_applies');
    }
};
