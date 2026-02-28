<?php

use Illuminate\Support\Facades\Route;
// ↓ ここを追加（Controllerを使えるようにする）
use App\Http\Controllers\SpotController;

Route::get('/', function () {
    return view('welcome');
});

// --- ここから「まちの目」用のルート ---

// 投稿画面を表示するURL (GET /report)
Route::get('/report', [SpotController::class, 'create'])->name('spots.create');

// 投稿データを保存するURL (POST /report)
Route::post('/report', [SpotController::class, 'store'])->name('spots.store');

// routes/web.php に追記
Route::get('/map', [SpotController::class, 'index'])->name('spots.index');