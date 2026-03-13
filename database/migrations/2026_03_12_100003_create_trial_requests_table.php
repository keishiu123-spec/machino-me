<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trial_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ambassador_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('spot_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('parent_name');
            $table->string('child_name');
            $table->string('child_age');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->text('note')->nullable();
            $table->string('status')->default('pending'); // pending, contacted, completed, cancelled
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trial_requests');
    }
};
