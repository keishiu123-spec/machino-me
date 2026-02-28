<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
        public function up(): void
        {
            Schema::create('spots', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // 投稿者ID
                $table->decimal('lat', 10, 8);               // 緯度
                $table->decimal('lng', 11, 8);               // 経度
                $table->string('category');                  // 危険タグ（車、死角など）
                $table->text('note')->nullable();            // 補足メモ（任意）
                $table->string('image_path')->nullable();    // 写真の保存先パス（任意）
                $table->integer('status')->default(0);       // 0:未対応, 1:調査中, 2:対策済
                $table->timestamps();                        // 作成日時・更新日時
            });
        }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spots');
    }
};
