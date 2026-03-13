<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ambassador_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('spot_id')->constrained()->cascadeOnDelete();
            $table->string('photo_path');
            $table->string('message', 200);
            $table->string('mood_tag')->nullable(); // レッスン風景, 生徒の成長, 教室の雰囲気, お知らせ
            $table->timestamps();

            $table->index(['spot_id', 'created_at']);
            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ambassador_posts');
    }
};
