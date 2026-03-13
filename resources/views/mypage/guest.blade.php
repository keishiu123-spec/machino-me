@extends('layouts.app')

@section('content')
<div class="bg-surface-50 min-h-screen pb-24">

    {{-- Hero --}}
    <div class="bg-white px-6 pt-10 pb-8 text-center border-b border-gray-100/40">
        <div class="w-20 h-20 bg-gradient-to-br from-brand-200 to-brand-400 rounded-3xl flex items-center justify-center mx-auto mb-5 shadow-[0_8px_30px_rgba(34,197,94,0.2)]">
            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="none" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        </div>
        <h1 class="text-xl font-black text-gray-900 mb-2">マイページ</h1>
        <p class="text-sm text-gray-500 leading-relaxed">
            ログインすると、お子さんの学校を拠点に<br>
            <span class="text-brand-600 font-bold">徒歩分数で教室を比較</span>できます
        </p>
    </div>

    {{-- Features Preview --}}
    <div class="px-4 pt-5 space-y-3 pb-6">
        <div class="card flex items-center gap-3.5 p-4">
            <div class="w-10 h-10 bg-gradient-to-br from-brand-50 to-brand-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <span class="text-lg">🏫</span>
            </div>
            <div>
                <p class="text-sm font-bold text-gray-900">マイ・スクール設定</p>
                <p class="text-[11px] text-gray-400">学校からの徒歩分数が一目でわかる</p>
            </div>
        </div>
        <div class="card flex items-center gap-3.5 p-4">
            <div class="w-10 h-10 bg-gradient-to-br from-amber-50 to-amber-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <span class="text-lg">💛</span>
            </div>
            <div>
                <p class="text-sm font-bold text-gray-900">お気に入り比較</p>
                <p class="text-[11px] text-gray-400">検討中の教室をまとめて比較・管理</p>
            </div>
        </div>
        <div class="card flex items-center gap-3.5 p-4">
            <div class="w-10 h-10 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <span class="text-lg">📡</span>
            </div>
            <div>
                <p class="text-sm font-bold text-gray-900">ご近所ライブフィード</p>
                <p class="text-[11px] text-gray-400">学校の近くの教室からのリアルな声を優先表示</p>
            </div>
        </div>
    </div>

    {{-- Login CTA --}}
    <div class="px-4 pb-6">
        <a href="{{ route('dev.login', 1) }}"
           class="block w-full bg-gradient-to-r from-brand-500 to-brand-600 text-white font-bold py-4 rounded-2xl shadow-[0_8px_24px_rgba(34,197,94,0.25)] text-center text-sm active:scale-[0.98] transition-all">
            デモユーザーでログイン
        </a>
        <p class="text-[10px] text-gray-300 text-center mt-2">テストユーザー（田中 美咲）としてログインします</p>
    </div>

    {{-- Demo Login Options --}}
    <div class="px-4 pb-8">
        <p class="text-[10px] font-bold text-gray-400 mb-3 text-center section-label">他のユーザーで試す</p>
        <div class="grid grid-cols-2 gap-2">
            <a href="{{ route('dev.login', 2) }}" class="card p-3 text-center active:scale-95 transition-transform">
                <p class="text-sm font-bold text-gray-700">佐藤 健太</p>
                <p class="text-[10px] text-gray-400">けんパパ</p>
            </a>
            <a href="{{ route('dev.login', 3) }}" class="card p-3 text-center active:scale-95 transition-transform">
                <p class="text-sm font-bold text-gray-700">鈴木 あゆみ</p>
                <p class="text-[10px] text-gray-400">あゆママ</p>
            </a>
        </div>
    </div>
</div>
@endsection
