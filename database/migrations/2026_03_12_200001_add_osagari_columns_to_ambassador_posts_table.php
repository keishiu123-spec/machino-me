<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ambassador_posts', function (Blueprint $table) {
            $table->boolean('has_osagari')->default(false)->after('mood_tag');
            $table->string('osagari_item', 100)->nullable()->after('has_osagari');
            $table->string('osagari_size', 50)->nullable()->after('osagari_item');
        });
    }

    public function down(): void
    {
        Schema::table('ambassador_posts', function (Blueprint $table) {
            $table->dropColumn(['has_osagari', 'osagari_item', 'osagari_size']);
        });
    }
};
