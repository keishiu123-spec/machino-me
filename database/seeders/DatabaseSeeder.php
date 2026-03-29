<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // 小学校データ
        $this->call(SchoolSeeder::class);

        // 本番では管理者ユーザーは作成しない
        if (app()->environment('local', 'development', 'testing')) {
            $now = Carbon::now();
            DB::table('users')->insert([
                [
                    'id' => 1,
                    'name' => '管理者',
                    'email' => 'admin@example.com',
                    'password' => Hash::make('password'),
                    'nickname' => null,
                    'thanks_score' => 0,
                    'badge_level' => 'newcomer',
                    'is_official' => false,
                    'area_code' => 'setagaya',
                    'role' => 'admin',
                    'avatar_url' => null,
                    'bio' => null,
                    'organization_name' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ]);
        }

        // 実在スポットデータ
        $this->call(MikkeAreaSpotSeeder::class);
    }
}
