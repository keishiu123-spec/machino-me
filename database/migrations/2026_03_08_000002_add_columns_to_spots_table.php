<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('spots', function (Blueprint $table) {
            $table->string('image_path')->nullable()->after('user_id');
            $table->string('link_url')->nullable()->after('image_path');
            $table->string('age_range')->nullable()->after('link_url');
            $table->string('parent_role')->nullable()->after('age_range');
            $table->json('category_tags')->nullable()->after('parent_role');
        });
    }

    public function down(): void
    {
        Schema::table('spots', function (Blueprint $table) {
            $table->dropColumn(['image_path', 'link_url', 'age_range', 'parent_role', 'category_tags']);
        });
    }
};
