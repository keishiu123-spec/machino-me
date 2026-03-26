<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpotController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\MyPageController;
use App\Http\Controllers\AmbassadorPostController;
use App\Http\Controllers\LineLoginController;

// ==========================================
// 公開ルート（認証不要）
// ==========================================

// マップ・スポット（閲覧）
Route::get('/', [SpotController::class, 'index'])->name('home');
Route::get('/spots', [SpotController::class, 'index'])->name('spots.index');

// スポット登録（ゲストでも投稿可能 — コアバリュー）
Route::get('/spots/create', [SpotController::class, 'create'])->name('spots.create');
Route::post('/spots', [SpotController::class, 'store'])->name('spots.store');

// スポット詳細（モーダル表示のためリダイレクト）
Route::get('/spots/{spot}', [SpotController::class, 'show'])->name('spots.show');

// 体験レポート投稿（ゲストでも投稿可能）
Route::post('/spots/{spot}/reviews', [SpotController::class, 'storeReview'])->name('spots.reviews.store');

// マイページ（ゲスト: LINE誘導 / 認証済み: フル表示）
Route::get('/mypage', [MyPageController::class, 'index'])->name('mypage.index');

// LINEログイン
Route::get('/auth/line', [LineLoginController::class, 'redirect'])->name('auth.line');
Route::get('/auth/line/callback', [LineLoginController::class, 'callback'])->name('auth.line.callback');

// v2以降: 質問箱（閲覧）
// Route::get('/questions', [QuestionController::class, 'index'])->name('questions.index');
// Route::get('/questions/{question}', [QuestionController::class, 'show'])->name('questions.show');

// v2以降: アンバサダー通信（公開）
// Route::get('/ambassador', [AmbassadorPostController::class, 'index'])->name('ambassador.index');
// Route::get('/ambassador/{ambassador}', [AmbassadorPostController::class, 'show'])->name('ambassador.show');

// v2以降: 体験申込
// Route::get('/ambassador/{ambassador}/trial', [AmbassadorPostController::class, 'trialForm'])->name('ambassador.trial');
// Route::post('/ambassador/{ambassador}/trial', [AmbassadorPostController::class, 'storeTrial'])->name('ambassador.trial.store');

// v2以降: 検索
// Route::get('/search', [SearchController::class, 'index'])->name('search.index');

// ==========================================
// 認証必須ルート（ログインしないと使えない機能）
// ==========================================
Route::middleware('auth')->group(function () {
    // お気に入り
    Route::post('/spots/{spot}/favorite', [MyPageController::class, 'toggleFavorite'])->name('spots.favorite');

    // マイスクール設定
    Route::post('/mypage/school', [MyPageController::class, 'updateSchool'])->name('mypage.updateSchool');

    // v2以降: 質問投稿
    // Route::get('/questions/create', [QuestionController::class, 'create'])->name('questions.create');
    // Route::post('/questions', [QuestionController::class, 'store'])->name('questions.store');

    // ログアウト
    Route::post('/logout', function () {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/')->with('success', 'ログアウトしました');
    })->name('logout');
});

// v2以降: アンバサダー専用ルート
// Route::middleware(['auth', \App\Http\Middleware\EnsureUserIsAmbassador::class])->group(function () {
//     Route::get('/ambassador-post/create', [AmbassadorPostController::class, 'create'])->name('ambassador.create');
//     Route::post('/ambassador-post', [AmbassadorPostController::class, 'store'])->name('ambassador.store');
//     Route::get('/ambassador-dashboard', [AmbassadorPostController::class, 'dashboard'])->name('ambassador.dashboard');
//     Route::patch('/ambassador-trial/{trial}/status', [AmbassadorPostController::class, 'updateTrialStatus'])->name('ambassador.trial.updateStatus');
// });

// デモ用：開発環境でのみ有効
if (app()->environment('local', 'development')) {
    Route::get('/dev/login-as/{userId}', function ($userId) {
        auth()->loginUsingId($userId);
        $user = auth()->user();
        return redirect()->route('mypage.index')->with('success', $user->name . ' としてログインしました');
    })->name('dev.login');
}
