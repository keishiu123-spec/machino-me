<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpotController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\MyPageController;
use App\Http\Controllers\AmbassadorPostController;

// マップ・スポット
Route::resource('spots', SpotController::class);
Route::get('/', [SpotController::class, 'index'])->name('home');

// 質問・タイムライン
Route::resource('questions', QuestionController::class);
Route::post('/questions/{question}/comments', [SpotController::class, 'storeComment'])->name('comments.store');
Route::post('/api/comments/{comment}/thanks', function (\App\Models\Comment $comment) {
    $comment->increment('thanks_count');
    return response()->json(['thanks_count' => $comment->thanks_count]);
})->name('api.comments.thanks');

// レビュー投稿
Route::post('/spots/{spot}/reviews', [SpotController::class, 'storeReview'])->name('spots.reviews.store');

// アンバサダー通信（公開 — 誰でも閲覧可能）
Route::get('/ambassador', [AmbassadorPostController::class, 'index'])->name('ambassador.index');
Route::get('/ambassador/{ambassador}', [AmbassadorPostController::class, 'show'])->name('ambassador.show');

// アンバサダー公開Q&A（誰でも質問可能）
Route::post('/ambassador/{ambassador}/questions', [AmbassadorPostController::class, 'storeQuestion'])->name('ambassador.questions.store');
Route::post('/ambassador-questions/{question}/answers', [AmbassadorPostController::class, 'storeAnswer'])->name('ambassador.answers.store');

// 体験申込（公開フォーム、非公開データ）
Route::get('/ambassador/{ambassador}/trial', [AmbassadorPostController::class, 'trialForm'])->name('ambassador.trial');
Route::post('/ambassador/{ambassador}/trial', [AmbassadorPostController::class, 'storeTrial'])->name('ambassador.trial.store');

// アンバサダー通信（投稿 — アンバサダー権限が必要）
Route::middleware(['auth', \App\Http\Middleware\EnsureUserIsAmbassador::class])->group(function () {
    Route::get('/ambassador-post/create', [AmbassadorPostController::class, 'create'])->name('ambassador.create');
    Route::post('/ambassador-post', [AmbassadorPostController::class, 'store'])->name('ambassador.store');
    // アンバサダー管理画面
    Route::get('/ambassador-dashboard', [AmbassadorPostController::class, 'dashboard'])->name('ambassador.dashboard');
    Route::patch('/ambassador-trial/{trial}/status', [AmbassadorPostController::class, 'updateTrialStatus'])->name('ambassador.trial.updateStatus');
});

// アンバサダー通信の最新状態（ポーリング用JSON API）
Route::get('/api/ambassador-pulse', function () {
    $data = \App\Models\AmbassadorPost::select('spot_id', \Illuminate\Support\Facades\DB::raw('MAX(created_at) as latest_at'))
        ->groupBy('spot_id')
        ->pluck('latest_at', 'spot_id');
    return response()->json($data);
})->name('api.ambassador-pulse');

// マイページ
Route::get('/mypage', [MyPageController::class, 'index'])->name('mypage.index');
Route::post('/mypage/school', [MyPageController::class, 'updateSchool'])->name('mypage.updateSchool');
Route::post('/spots/{spot}/favorite', [MyPageController::class, 'toggleFavorite'])->name('spots.favorite');

// 検索画面（レガシー）
Route::get('/search', [SearchController::class, 'index'])->name('search.index');

// デモ用：アンバサダーとしてログイン
Route::get('/dev/login-as/{userId}', function ($userId) {
    auth()->loginUsingId($userId);
    $user = auth()->user();
    $redirect = $user->isAmbassador() ? 'ambassador.create' : 'mypage.index';
    return redirect()->route($redirect)->with('success', $user->name . ' としてログインしました');
})->name('dev.login');
