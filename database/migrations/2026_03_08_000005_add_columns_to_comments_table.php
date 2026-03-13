<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // comments テーブルがまだ存在しない場合は create_comments_table で作成される
        // このマイグレーションはその後に user_id と thanks_count を追加する
        if (Schema::hasTable('comments')) {
            Schema::table('comments', function (Blueprint $table) {
                if (!Schema::hasColumn('comments', 'user_id')) {
                    $table->unsignedBigInteger('user_id')->nullable()->after('question_id');
                }
                if (!Schema::hasColumn('comments', 'thanks_count')) {
                    $table->integer('thanks_count')->default(0)->after('body');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('comments')) {
            Schema::table('comments', function (Blueprint $table) {
                $columns = [];
                if (Schema::hasColumn('comments', 'user_id')) $columns[] = 'user_id';
                if (Schema::hasColumn('comments', 'thanks_count')) $columns[] = 'thanks_count';
                if (!empty($columns)) $table->dropColumn($columns);
            });
        }
    }
};
