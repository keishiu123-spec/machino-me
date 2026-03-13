<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Spot;
use App\Models\Comment;
use App\Models\Review;
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
        return view('spots.index', compact('spots'));
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
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'category' => 'required|string',
            'note' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'link_url' => 'nullable|url',
        ]);

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
                    'user_id' => auth()->id() ?? 1,
                    'body' => $request->note,
                    'satisfaction' => 3,
                    'skill_growth' => 3,
                    'cost_performance' => 3,
                    'teacher_passion' => 3,
                    'parent_burden' => 3,
                    'vibe_tag' => 'エンジョイ勢',
                ]);
            }

            return redirect()->route('spots.index', ['focus' => $existing->id])
                ->with('success', '既存のスポット「' . $existing->title . '」に情報を追加しました！');
        }

        // --- 新規登録 ---
        $data = $request->only(['title', 'lat', 'lng', 'category', 'note', 'link_url', 'google_place_id', 'monthly_fee_range', 'policy_type', 'age_range']);
        $data['user_id'] = auth()->id() ?? 1;
        $data['has_parent_duty'] = $request->boolean('has_parent_duty');
        $data['transfer_available'] = $request->boolean('transfer_available');

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('spots', 'public');
        }

        $spot = Spot::create($data);

        return redirect()->route('spots.index', ['focus' => $spot->id])
            ->with('success', 'スポットを登録しました！');
    }

    // レビュー投稿
    public function storeReview(Request $request, Spot $spot)
    {
        $request->validate([
            'vibe_tag' => 'required|string',
            'satisfaction' => 'required|integer|min:1|max:5',
            'skill_growth' => 'required|integer|min:1|max:5',
            'cost_performance' => 'required|integer|min:1|max:5',
            'teacher_passion' => 'required|integer|min:1|max:5',
            'parent_burden' => 'required|integer|min:1|max:5',
            'body' => 'required|string|max:2000',
        ]);

        Review::create([
            'spot_id' => $spot->id,
            'user_id' => 1,
            'vibe_tag' => $request->vibe_tag,
            'satisfaction' => $request->satisfaction,
            'skill_growth' => $request->skill_growth,
            'cost_performance' => $request->cost_performance,
            'teacher_passion' => $request->teacher_passion,
            'parent_burden' => $request->parent_burden,
            'body' => $request->body,
        ]);

        return redirect()->route('spots.index')->with('success', '口コミを投稿しました！');
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
            'user_id'     => 1,
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