<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->tinyInteger('satisfaction')->nullable()->after('body');
            $table->tinyInteger('skill_growth')->nullable()->after('satisfaction');
            $table->tinyInteger('parent_burden')->nullable()->after('skill_growth');
            $table->string('vibe_tag')->nullable()->after('parent_burden');
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn(['satisfaction', 'skill_growth', 'parent_burden', 'vibe_tag']);
        });
    }
};
