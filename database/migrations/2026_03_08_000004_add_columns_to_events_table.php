<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->decimal('lat', 10, 7)->nullable()->after('location_name');
            $table->decimal('lng', 10, 7)->nullable()->after('lat');
            $table->unsignedBigInteger('user_id')->nullable()->after('link_url');
            $table->json('category_tags')->nullable()->after('user_id');
            $table->string('target_age')->nullable()->after('category_tags');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['lat', 'lng', 'user_id', 'category_tags', 'target_age']);
        });
    }
};
