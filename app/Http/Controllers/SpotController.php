<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Spot;
use App\Models\Comment;
use App\Models\Review;
use App\Models\School;
use Illuminate\Support\Facades\Storage;

class SpotController extends Controller
{
    // マップ画面
    public function index(Request $request)
    {
        $query = Spot::query();
        if ($request->has('category') && $request->category != 'すべて') {
            $query->where('category', $request->category);
        }
        $spots = $query->with(['reviews', 'latestAmbassadorPost'])
            ->withCount(['ambassadorPosts as osagari_count' => fn($q) => $q->where('has_osagari', true)])
            ->latest()->get();

        // ユーザーのマイ・スクール情報を取得
        $mySchool = null;
        if (auth()->check() && auth()->user()->my_school_id) {
            $mySchool = auth()->user()->mySchool;
        }

        $schools = School::all();

        return view('spots.index', compact('spots', 'mySchool', 'schools'));
    }

    // スポット詳細 → マップのモーダルで表示するためリダイレクト
    public function show(Spot $spot)
    {
        return redirect('/')->with('open_spot', $spot->id);
    }

    // 投稿画面
    public function create()
    {
        $existingSpots = Spot::select('id', 'title', 'lat', 'lng', 'google_place_id')->get();
        return view('spots.create', compact('existingSpots'));
    }

    // 新規投稿の保存（重複チェック付き）
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
            'category' => 'required|string|max:50',
            'note' => 'nullable|string|max:2000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'link_url' => 'nullable|url|max:500',
            'google_place_id' => 'nullable|string|max:300',
            'monthly_fee_range' => 'nullable|string|max:50',
            'policy_type' => 'nullable|string|max:20',
            'age_range' => 'nullable|string|max:20',
        ], [
            'lat.between' => '有効な緯度を入力してください。地図上で位置を指定してください。',
            'lng.between' => '有効な経度を入力してください。地図上で位置を指定してください。',
            'lat.required' => '位置情報が取得できませんでした。地図上で場所を指定してください。',
            'lng.required' => '位置情報が取得できませんでした。地図上で場所を指定してください。',
            'title.required' => '場所の名前を入力してください。',
            'category.required' => 'カテゴリを選択してください。',
        ]);

        // 世田谷区周辺（東京都付近）の緯度経度かチェック
        $lat = (float) $request->lat;
        $lng = (float) $request->lng;
        if ($lat < 35.0 || $lat > 36.0 || $lng < 139.0 || $lng > 140.5) {
            return back()->withErrors(['lat' => '世田谷区周辺の位置を指定してください。位置情報が正しくない可能性があります。'])->withInput();
        }

        // --- 重複チェック ---
        $existing = null;

        // 1. google_place_id が一致するスポットを検索
        if ($request->filled('google_place_id')) {
            $existing = Spot::where('google_place_id', $request->google_place_id)->first();
        }

        // 2. place_id がなければ、同名 + 近距離（200m以内）で検索
        if (!$existing) {
            $existing = Spot::where('title', $request->title)
                ->whereRaw('ABS(lat - ?) < 0.002 AND ABS(lng - ?) < 0.002', [
                    $request->lat, $request->lng
                ])
                ->first();
        }

        // 既存スポットが見つかった → 情報をマージ
        if ($existing) {
            $updates = [];

            // 画像がなければ更新
            if ($request->hasFile('image') && !$existing->image_path) {
                $updates['image_path'] = $request->file('image')->store('spots', 'public');
            }

            // 各フィールドを補完（既存値が空なら新しい値で埋める）
            foreach (['monthly_fee_range', 'policy_type', 'age_range', 'link_url', 'category'] as $field) {
                if ($request->filled($field) && empty($existing->$field)) {
                    $updates[$field] = $request->$field;
                }
            }
            if ($request->filled('google_place_id') && !$existing->google_place_id) {
                $updates['google_place_id'] = $request->google_place_id;
            }

            if ($updates) {
                $existing->update($updates);
            }

            // ユーザーのメモをレビューとして保存
            if ($request->filled('note')) {
                Review::create([
                    'spot_id' => $existing->id,
                    'user_id' => auth()->id(), // ゲストの場合は null
                    'body' => $request->note,
                    'vibe_tag' => 'エンジョイ勢',
                ]);
            }

            return redirect()->route('spots.index', ['focus' => $existing->id])
                ->with('success', '既存のスポット「' . $existing->title . '」に情報を追加しました！');
        }

        // --- 新規登録 ---
        $data = $request->only(['title', 'lat', 'lng', 'category', 'note', 'link_url', 'google_place_id', 'monthly_fee_range', 'policy_type', 'age_range', 'parent_role']);
        $data['user_id'] = auth()->id(); // ゲストの場合は null
        $data['has_parent_duty'] = $request->boolean('has_parent_duty');
        $data['transfer_available'] = $request->boolean('transfer_available');

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('spots', 'public');
        }

        $spot = Spot::create($data);

        return redirect()->route('spots.index', ['focus' => $spot->id])
            ->with('success', 'スポットを登録しました！');
    }

    // レビュー投稿 — v1: 3ステップ体験レポート（雰囲気・価格帯・一言）
    public function storeReview(Request $request, Spot $spot)
    {
        $request->validate([
            'vibe_tag' => 'required|string',
            'monthly_fee' => 'required|integer',
            'body' => 'nullable|string|max:100',
        ]);

        Review::create([
            'spot_id' => $spot->id,
            'user_id' => auth()->id(),
            'vibe_tag' => $request->vibe_tag,
            'monthly_fee' => $request->monthly_fee,
            'body' => $request->body,
        ]);

        return redirect()->route('spots.index')->with('success', '体験レポートを投稿しました！');
    }

    // タイムライン一覧
    public function questionList()
    {
        $questions = Spot::with('comments')
                         ->where('category', 'like', '%質問%')
                         ->orWhere('category', 'like', '%教えて%')
                         ->latest()
                         ->get();

        return view('questions.index', compact('questions'));
    }

    public function storeComment(Request $request, $questionId)
    {
        $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        $comment = Comment::create([
            'question_id' => $questionId,
            'body'        => $request->body,
            'user_id'     => auth()->id(),
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'id' => $comment->id,
                'body' => $comment->body,
                'thanks_count' => 0,
                'created_at' => $comment->created_at->diffForHumans(),
            ]);
        }

        return back()->with('success', '知恵をシェアしました！');
    }
}