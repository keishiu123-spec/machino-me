<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->tinyInteger('cost_performance')->nullable()->after('skill_growth');
            $table->tinyInteger('teacher_passion')->nullable()->after('cost_performance');
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn(['cost_performance', 'teacher_passion']);
        });
    }
};
