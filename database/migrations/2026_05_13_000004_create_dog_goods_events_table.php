<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dog_goods_events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->datetime('started_at');
            $table->datetime('ended_at');
            $table->string('location')->nullable();
            $table->unsignedSmallInteger('max_capacity')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_active', 'started_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dog_goods_events');
    }
};
