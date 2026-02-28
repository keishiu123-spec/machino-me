<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Spot;
use Illuminate\Support\Facades\Storage;

class SpotController extends Controller
{
    /**
     * ① 一覧画面（地図）を表示する
     */
    public function index()
    {
        // データベースから全投稿を取得
        $spots = Spot::all();
        return view('spots.index', compact('spots'));
    }

    /**
     * ② 投稿画面を表示する
     */
    public function create()
    {
        return view('spots.create');
    }

    /**
     * ③ 投稿内容（写真含む）をデータベースに保存する
     */
    public function store(Request $request)
    {
        // 1. バリデーション（入力チェック）
        $validated = $request->validate([
            'lat'      => 'required|numeric',
            'lng'      => 'required|numeric',
            'category' => 'required|string',
            'note'     => 'nullable|string|max:200',
            'image'    => 'nullable|image|max:10240', // 最大10MBまで
        ]);

        // 2. インスタンス作成
        $spot = new Spot($validated);
        
        // 3. テスト用ユーザーIDをセット
        // ※ログイン機能（Breeze等）導入後は auth()->id() に書き換え
        $spot->user_id = 1; 

        // 4. 画像の保存処理
        if ($request->hasFile('image')) {
            // storage/app/public/spots フォルダに保存
            $path = $request->file('image')->store('spots', 'public');
            // DBには保存先のパス（spots/filename.jpg）を記録
            $spot->image_path = $path;
        }

        // 5. DBに保存
        $spot->save();

        // 6. 保存後は地図画面（一覧）へリダイレクト
        return redirect()->route('spots.index')->with('success', '写真付きで報告が完了しました！');
    }
}