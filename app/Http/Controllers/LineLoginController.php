<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class LineLoginController extends Controller
{
    public function redirect()
    {
        if (empty(config('services.line.client_id'))) {
            return redirect('/')->with('error', 'LINEログインが設定されていません。管理者に連絡してください。');
        }

        return Socialite::driver('line')->redirect();
    }

    public function callback()
    {
        try {
            $lineUser = Socialite::driver('line')->user();
        } catch (\Exception $e) {
            return redirect('/')->with('error', 'LINEログインに失敗しました。もう一度お試しください。');
        }

        $user = User::where('line_id', $lineUser->getId())->first();

        if ($user) {
            // 既存ユーザー：アバター更新
            $user->update([
                'avatar' => $lineUser->getAvatar(),
                'name' => $lineUser->getName() ?: $user->name,
            ]);
        } else {
            // メールで既存アカウントを検索（アカウントリンク）
            $email = $lineUser->getEmail();
            if ($email) {
                $user = User::where('email', $email)->first();
            }

            if ($user) {
                // 既存アカウントにLINE IDを紐付け
                $user->update([
                    'line_id' => $lineUser->getId(),
                    'avatar' => $lineUser->getAvatar(),
                ]);
            } else {
                // 新規ユーザー作成
                $user = User::create([
                    'name' => $lineUser->getName() ?: 'LINEユーザー',
                    'email' => $email ?: 'line_' . $lineUser->getId() . '@line.local',
                    'line_id' => $lineUser->getId(),
                    'avatar' => $lineUser->getAvatar(),
                    'password' => bcrypt(Str::random(24)),
                    'role' => 'user',
                ]);
            }
        }

        auth()->login($user, true);

        return redirect()->intended(route('mypage.index'))->with('success', 'ようこそ、' . $user->name . 'さん！');
    }
}
