<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('spots', function (Blueprint $table) {
            $table->string('monthly_fee_range')->nullable()->after('age_range');
            $table->boolean('has_parent_duty')->default(false)->after('monthly_fee_range');
            $table->string('policy_type')->nullable()->after('has_parent_duty');
            $table->boolean('transfer_available')->default(false)->after('policy_type');
        });
    }

    public function down(): void
    {
        Schema::table('spots', function (Blueprint $table) {
            $table->dropColumn(['monthly_fee_range', 'has_parent_duty', 'policy_type', 'transfer_available']);
        });
    }
};
