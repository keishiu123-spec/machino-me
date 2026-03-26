<?php

namespace App\Http\Controllers;

use App\Models\AmbassadorPost;
use App\Models\AmbassadorQuestion;
use App\Models\AmbassadorAnswer;
use App\Models\TrialRequest;
use App\Models\Spot;
use App\Models\User;
use Illuminate\Http\Request;

class AmbassadorPostController extends Controller
{
    // アンバサダー通信タイムライン（誰でも閲覧可能）
    public function index()
    {
        $ambassadors = User::where('role', 'ambassador')
            ->withCount('ambassadorPosts')
            ->get();

        $allPosts = AmbassadorPost::with(['user', 'spot'])
            ->whereHas('user', fn($q) => $q->where('role', 'ambassador'))
            ->latest()
            ->get();

        return view('ambassador.index', compact('ambassadors', 'allPosts'));
    }

    // アンバサダー個別ページ（投稿一覧 + Q&A + 体験申込）
    public function show(User $ambassador)
    {
        if (! $ambassador->isAmbassador()) {
            abort(404);
        }

        $posts = AmbassadorPost::with('spot')
            ->where('user_id', $ambassador->id)
            ->latest()
            ->paginate(20);

        $ambassador->load('managedSpots');

        // 公開Q&A
        $questions = AmbassadorQuestion::with(['user', 'answers.user'])
            ->where('ambassador_user_id', $ambassador->id)
            ->latest()
            ->get();

        return view('ambassador.show', compact('ambassador', 'posts', 'questions'));
    }

    // 投稿フォーム（アンバサダー専用）
    public function create()
    {
        $user = auth()->user();
        $spots = Spot::where('ambassador_user_id', $user->id)->get();

        return view('ambassador.create', compact('spots'));
    }

    // 投稿保存（アンバサダー専用）
    public function store(Request $request)
    {
        $request->validate([
            'spot_id' => 'required|exists:spots,id',
            'photo' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'message' => 'required|string|max:200',
            'mood_tag' => 'nullable|string|max:20',
            'has_osagari' => 'nullable|boolean',
            'osagari_item' => 'nullable|required_if:has_osagari,1|string|max:100',
            'osagari_size' => 'nullable|string|max:50',
        ]);

        $user = auth()->user();

        $spot = Spot::where('id', $request->spot_id)
            ->where('ambassador_user_id', $user->id)
            ->firstOrFail();

        $photoPath = $request->file('photo')->store('ambassador', 'public');

        AmbassadorPost::create([
            'user_id' => $user->id,
            'spot_id' => $spot->id,
            'photo_path' => $photoPath,
            'message' => $request->message,
            'mood_tag' => $request->mood_tag,
            'has_osagari' => $request->boolean('has_osagari'),
            'osagari_item' => $request->has_osagari ? $request->osagari_item : null,
            'osagari_size' => $request->has_osagari ? $request->osagari_size : null,
        ]);

        return redirect()->route('ambassador.show', $user)
            ->with('success', '通信を投稿しました！');
    }

    // ===== 公開Q&A =====

    // 質問投稿
    public function storeQuestion(Request $request, User $ambassador)
    {
        $request->validate(['body' => 'required|string|max:500']);

        $userId = auth()->id() ?? 1;

        $q = AmbassadorQuestion::create([
            'ambassador_user_id' => $ambassador->id,
            'user_id' => $userId,
            'body' => $request->body,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'id' => $q->id,
                'body' => $q->body,
                'user_name' => $q->user->nickname ?? $q->user->name,
                'created_at' => $q->created_at->diffForHumans(),
            ]);
        }

        return back()->with('success', '質問を投稿しました！');
    }

    // 回答投稿
    public function storeAnswer(Request $request, AmbassadorQuestion $question)
    {
        $request->validate(['body' => 'required|string|max:1000']);

        $userId = auth()->id() ?? 1;

        $a = AmbassadorAnswer::create([
            'ambassador_question_id' => $question->id,
            'user_id' => $userId,
            'body' => $request->body,
        ]);

        if ($request->wantsJson()) {
            $user = $a->user;
            return response()->json([
                'id' => $a->id,
                'body' => $a->body,
                'user_name' => $user->nickname ?? $user->name,
                'is_ambassador' => $user->isAmbassador(),
                'created_at' => $a->created_at->diffForHumans(),
            ]);
        }

        return back()->with('success', '回答しました！');
    }

    // ===== 体験申込 =====

    // 体験申込フォーム表示
    public function trialForm(Request $request, User $ambassador)
    {
        if (! $ambassador->isAmbassador()) {
            abort(404);
        }
        $ambassador->load('managedSpots');

        $osagariPost = null;
        if ($request->filled('osagari_post')) {
            $osagariPost = AmbassadorPost::where('id', $request->osagari_post)
                ->where('has_osagari', true)
                ->first();
        }

        return view('ambassador.trial', compact('ambassador', 'osagariPost'));
    }

    // 体験申込保存
    public function storeTrial(Request $request, User $ambassador)
    {
        $request->validate([
            'spot_id' => 'required|exists:spots,id',
            'parent_name' => 'required|string|max:100',
            'child_name' => 'required|string|max:100',
            'child_age' => 'required|string|max:20',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'note' => 'nullable|string|max:500',
        ]);

        TrialRequest::create([
            'ambassador_user_id' => $ambassador->id,
            'spot_id' => $request->spot_id,
            'user_id' => auth()->id(),
            'parent_name' => $request->parent_name,
            'child_name' => $request->child_name,
            'child_age' => $request->child_age,
            'phone' => $request->phone,
            'email' => $request->email,
            'note' => $request->note,
        ]);

        return redirect()->route('ambassador.show', $ambassador)
            ->with('success', '体験申込を送信しました！先生からのご連絡をお待ちください。');
    }

    // ===== アンバサダー管理画面 =====

    public function dashboard()
    {
        $user = auth()->user();

        // 未回答の質問
        $questions = AmbassadorQuestion::with(['user', 'answers.user'])
            ->where('ambassador_user_id', $user->id)
            ->latest()
            ->get();

        // 体験申込
        $trialRequests = TrialRequest::with(['spot', 'user'])
            ->where('ambassador_user_id', $user->id)
            ->latest()
            ->get();

        $spots = Spot::where('ambassador_user_id', $user->id)->get();

        return view('ambassador.dashboard', compact('questions', 'trialRequests', 'spots'));
    }

    // 体験申込ステータス更新
    public function updateTrialStatus(Request $request, TrialRequest $trial)
    {
        $user = auth()->user();
        if ($trial->ambassador_user_id !== $user->id && $user->role !== 'admin') {
            abort(403);
        }

        $request->validate(['status' => 'required|in:pending,contacted,completed,cancelled']);
        $trial->update(['status' => $request->status]);

        return back()->with('success', 'ステータスを更新しました');
    }
}
