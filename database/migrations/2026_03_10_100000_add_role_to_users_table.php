<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('user')->after('email'); // user, ambassador, admin
            $table->string('avatar_url')->nullable()->after('role');
            $table->text('bio')->nullable()->after('avatar_url');
            $table->string('organization_name')->nullable()->after('bio'); // 教室名
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'avatar_url', 'bio', 'organization_name']);
        });
    }
};
