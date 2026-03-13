<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('safety_logs', function (Blueprint $table) {
            $table->id();
            $table->decimal('lat', 10, 7);
            $table->decimal('lng', 10, 7);
            $table->string('danger_type');
            $table->string('time_zone')->nullable();
            $table->text('note')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->integer('reaction_count')->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('safety_logs');
    }
};
