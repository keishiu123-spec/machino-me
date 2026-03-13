<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->decimal('lat', 10, 7)->nullable()->after('image_path');
            $table->decimal('lng', 10, 7)->nullable()->after('lat');
            $table->unsignedBigInteger('user_id')->nullable()->after('lng');
            $table->string('target_age')->nullable()->after('user_id');
            $table->string('status')->default('open')->after('target_age');
        });
    }

    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn(['lat', 'lng', 'user_id', 'target_age', 'status']);
        });
    }
};
