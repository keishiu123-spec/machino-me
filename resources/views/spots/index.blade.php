@extends('layouts.app')

@section('content')
<div class="bg-white min-h-screen pb-20 relative">

    {{-- Search Bar --}}
    <div class="absolute top-4 left-4 right-4 z-[1100]">
        <div class="bg-white/90 backdrop-blur-xl rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.08)] border border-white/60 overflow-visible">
            <div class="px-4 py-3 flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="#9ca3af" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input id="map-search" type="text" placeholder="スポットを検索..." autocomplete="off"
                    class="text-sm w-full outline-none font-medium text-gray-700 bg-transparent placeholder-gray-400">
                <button id="search-clear" class="hidden text-gray-300 hover:text-gray-500 transition-colors" onclick="clearSearch()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="14" y1="4" x2="4" y2="14"/><line x1="4" y1="4" x2="14" y2="14"/></svg>
                </button>
            </div>
            <div id="search-dropdown" class="hidden border-t border-gray-100/50"></div>
        </div>
    </div>

    {{-- School Filter --}}
    <div class="absolute top-[72px] left-0 right-0 z-[1002] px-4">
        <div class="bg-white/90 backdrop-blur-xl rounded-xl shadow-sm border border-gray-100/60 px-3 py-2 flex items-center gap-2">
            <span class="text-[10px] font-bold text-gray-500 flex-shrink-0">🏫</span>
            <select id="school-select" onchange="onSchoolChange()" class="text-xs font-bold text-gray-700 bg-transparent outline-none flex-1 min-w-0">
                <option value="">小学校で絞り込み</option>
            </select>
            <div class="flex bg-gray-100 rounded-lg p-0.5 flex-shrink-0">
                <button id="dist-1km" onclick="setDistance(1)" class="dist-btn active px-2 py-1 rounded-md text-[10px] font-bold transition-all">1km</button>
                <button id="dist-2km" onclick="setDistance(2)" class="dist-btn px-2 py-1 rounded-md text-[10px] font-bold transition-all">2km</button>
            </div>
            <button id="school-clear" onclick="clearSchoolFilter()" class="hidden text-gray-300 hover:text-gray-500 flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="3" x2="3" y2="12"/><line x1="3" y1="3" x2="12" y2="12"/></svg>
            </button>
        </div>
    </div>

    {{-- Category Filter --}}
    <div class="absolute top-[116px] left-0 right-0 z-[1001] px-4">
        <div class="flex gap-2 overflow-x-auto no-scrollbar py-1">
            <button onclick="filterCategory('すべて')" class="cat-btn active flex-shrink-0 px-3.5 py-1.5 rounded-full text-xs font-bold border transition-all" data-cat="すべて">すべて</button>
            <button onclick="filterCategory('スポーツ少年団')" class="cat-btn flex-shrink-0 px-3.5 py-1.5 rounded-full text-xs font-bold border transition-all" data-cat="スポーツ少年団">⚽ 少年団</button>
            <button onclick="filterCategory('個人教室')" class="cat-btn flex-shrink-0 px-3.5 py-1.5 rounded-full text-xs font-bold border transition-all" data-cat="個人教室">🎹 個人教室</button>
            <button onclick="filterCategory('塾・学習')" class="cat-btn flex-shrink-0 px-3.5 py-1.5 rounded-full text-xs font-bold border transition-all" data-cat="塾・学習">📚 塾・学習</button>
            <button onclick="filterCategory('施設')" class="cat-btn flex-shrink-0 px-3.5 py-1.5 rounded-full text-xs font-bold border transition-all" data-cat="施設">🏊 施設</button>
        </div>
    </div>

    {{-- Toggle List View --}}
    <button id="toggle-list-btn" onclick="toggleListView()"
        class="absolute top-[156px] right-4 z-[1002] w-10 h-10 bg-white rounded-xl shadow-lg flex items-center justify-center text-gray-500 hover:text-brand-600 active:scale-90 transition-all border border-gray-100">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
    </button>

    {{-- Map --}}
    <div id="map" style="height:100vh;width:100%;"></div>

    {{-- Sidebar Card List --}}
    <div id="sidebar-list" class="fixed inset-x-0 bottom-[64px] z-[2500] mx-auto translate-y-full transition-transform duration-500 ease-out" style="max-width:430px;max-height:50vh;">
        <div class="bg-white rounded-t-[24px] shadow-[0_-8px_40px_rgba(0,0,0,0.12)] overflow-hidden flex flex-col" style="max-height:50vh;">
            <div class="flex items-center justify-between px-5 pt-4 pb-2">
                <p class="text-sm font-black text-gray-800">教室リスト</p>
                <button onclick="toggleListView()" class="text-gray-400 hover:text-gray-600 p-1"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="14" y1="4" x2="4" y2="14"/><line x1="4" y1="4" x2="14" y2="14"/></svg></button>
            </div>
            <div id="sidebar-cards" class="overflow-y-auto overscroll-contain px-4 pb-4 space-y-3"></div>
        </div>
    </div>

    {{-- Compare Floating Bar --}}
    <div id="compare-bar" class="fixed bottom-[80px] left-4 right-4 z-[2600] max-w-md mx-auto hidden">
        <div class="bg-brand-600 text-white rounded-2xl shadow-[0_8px_32px_rgba(34,197,94,0.4)] px-4 py-3 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="text-sm font-bold" id="compare-count">0</span>
                <span class="text-xs opacity-80">件選択中</span>
            </div>
            <div class="flex gap-2">
                <button onclick="openCompareView()" class="bg-white text-brand-700 px-4 py-1.5 rounded-xl text-xs font-bold active:scale-95 transition-all">比較する</button>
                <button onclick="clearCompare()" class="bg-brand-700 px-3 py-1.5 rounded-xl text-xs font-bold active:scale-95 transition-all">クリア</button>
            </div>
        </div>
    </div>

    {{-- Compare Modal --}}
    <div id="compare-overlay" class="fixed inset-0 z-[7000] hidden">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeCompareView()"></div>
        <div class="absolute inset-x-0 bottom-0 max-w-md mx-auto">
            <div class="bg-white rounded-t-[28px] shadow-[0_-16px_60px_rgba(0,0,0,0.2)] max-h-[90vh] overflow-hidden flex flex-col">
                <div class="flex items-center justify-between px-5 pt-5 pb-3">
                    <h3 class="text-base font-black text-gray-900">📊 教室を比較</h3>
                    <button onclick="closeCompareView()" class="text-gray-400 hover:text-gray-600"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="14" y1="5" x2="5" y2="14"/><line x1="5" y1="5" x2="14" y2="14"/></svg></button>
                </div>
                <div id="compare-body" class="flex-1 overflow-y-auto overscroll-contain px-5 pb-8"></div>
            </div>
        </div>
    </div>

    {{-- Detail Modal --}}
    <div id="detail-overlay" class="fixed inset-0 z-[5000]" style="pointer-events:none;">
        <div id="detail-backdrop" class="absolute inset-0 bg-black/0 transition-all duration-400" style="pointer-events:none;" onclick="closeDetail()"></div>
        <div id="detail-card" class="absolute inset-x-0 bottom-0 z-10" style="max-width:430px;margin:0 auto;transform:translateY(100%);transition:transform 0.45s cubic-bezier(0.32,0.72,0,1);pointer-events:none;">
            <div class="bg-white rounded-t-[28px] shadow-[0_-16px_60px_rgba(0,0,0,0.18)] max-h-[88vh] overflow-hidden flex flex-col">
                {{-- Drag handle --}}
                <div id="detail-drag-handle" class="flex justify-center pt-3 pb-1 cursor-grab active:cursor-grabbing">
                    <div class="w-10 h-1 bg-gray-300 rounded-full"></div>
                </div>
                <div id="detail-hero" class="relative w-full overflow-hidden" style="min-height:220px;">
                    <div id="hero-bg" class="absolute inset-0"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
                    <button id="detail-close-btn" class="absolute top-3 right-3 w-10 h-10 bg-black/40 backdrop-blur-sm rounded-full flex items-center justify-center text-white hover:bg-black/60 transition-all active:scale-90 z-10">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="14" y1="5" x2="5" y2="14"/><line x1="5" y1="5" x2="14" y2="14"/></svg>
                    </button>
                    <div class="absolute bottom-0 left-0 right-0 p-5 z-10">
                        <div id="hero-badges" class="flex flex-wrap gap-1.5 mb-2"></div>
                        <h2 id="hero-title" class="text-2xl font-black text-white leading-tight drop-shadow-lg"></h2>
                    </div>
                </div>
                <div id="detail-body" class="flex-1 overflow-y-auto overscroll-contain px-5 pt-5 pb-8"></div>
            </div>
        </div>
    </div>

    {{-- Review Modal --}}
    <div id="review-overlay" class="fixed inset-0 z-[6000] hidden">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeReviewForm()"></div>
        <div class="absolute inset-x-0 bottom-0 max-w-md mx-auto">
            <div class="bg-white rounded-t-[28px] shadow-[0_-16px_60px_rgba(0,0,0,0.2)] p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base font-black text-gray-900">この教室の暗黙知を共有する</h3>
                    <button onclick="closeReviewForm()" class="text-gray-400 hover:text-gray-600"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="14" y1="5" x2="5" y2="14"/><line x1="5" y1="5" x2="14" y2="14"/></svg></button>
                </div>
                <form id="review-form" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="spot_id" id="review-spot-id">
                    <div>
                        <label class="text-xs font-bold text-gray-500 mb-2 block">あなたはどのタイプ？ <span class="text-red-500">*必須</span></label>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach(['ガチ勢','エンジョイ勢','のびのび系','受験特化'] as $vibe)
                            <label>
                                <input type="radio" name="vibe_tag" value="{{ $vibe }}" class="hidden peer" {{ $loop->index===1?'checked':'' }}>
                                <div class="peer-checked:bg-brand-500 peer-checked:text-white peer-checked:border-brand-500 bg-gray-50 text-gray-600 border border-gray-200 rounded-xl py-2.5 text-center text-xs font-bold cursor-pointer transition-all active:scale-95">{{ $vibe }}</div>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="text-[10px] font-bold text-gray-500 mb-1 block text-center">満足度</label>
                            <select name="satisfaction" class="w-full bg-gray-50 rounded-lg px-2 py-2 text-sm text-center border border-gray-200 font-bold">
                                @for($i=5;$i>=1;$i--)<option value="{{$i}}">{{str_repeat('★',$i)}}{{str_repeat('☆',5-$i)}}</option>@endfor
                            </select>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-500 mb-1 block text-center">上達度</label>
                            <select name="skill_growth" class="w-full bg-gray-50 rounded-lg px-2 py-2 text-sm text-center border border-gray-200 font-bold">
                                @for($i=5;$i>=1;$i--)<option value="{{$i}}">{{str_repeat('★',$i)}}{{str_repeat('☆',5-$i)}}</option>@endfor
                            </select>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-500 mb-1 block text-center">コスパ</label>
                            <select name="cost_performance" class="w-full bg-gray-50 rounded-lg px-2 py-2 text-sm text-center border border-gray-200 font-bold">
                                @for($i=5;$i>=1;$i--)<option value="{{$i}}">{{str_repeat('★',$i)}}{{str_repeat('☆',5-$i)}}</option>@endfor
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-[10px] font-bold text-gray-500 mb-1 block text-center">先生の熱意</label>
                            <select name="teacher_passion" class="w-full bg-gray-50 rounded-lg px-2 py-2 text-sm text-center border border-gray-200 font-bold">
                                @for($i=5;$i>=1;$i--)<option value="{{$i}}">{{str_repeat('★',$i)}}{{str_repeat('☆',5-$i)}}</option>@endfor
                            </select>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-500 mb-1 block text-center">親の負担<span class="text-[8px] text-gray-400">（★多=楽）</span></label>
                            <select name="parent_burden" class="w-full bg-gray-50 rounded-lg px-2 py-2 text-sm text-center border border-gray-200 font-bold">
                                @for($i=5;$i>=1;$i--)<option value="{{$i}}">{{str_repeat('★',$i)}}{{str_repeat('☆',5-$i)}}</option>@endfor
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500 mb-1 block">口コミ（親のリアルな声）</label>
                        <textarea name="body" rows="3" placeholder="実際に通ってみて分かったこと、公式HPに載ってない情報を教えてください"
                            class="w-full bg-gray-50 rounded-xl px-4 py-3 text-sm outline-none border border-gray-200 focus:border-brand-400 focus:ring-2 focus:ring-brand-100 resize-none" required></textarea>
                    </div>
                    <button type="submit"
                        class="w-full bg-brand-500 hover:bg-brand-600 text-white font-bold py-3.5 rounded-2xl shadow-[0_8px_24px_rgba(34,197,94,0.3)] active:scale-[0.98] transition-all text-sm">
                        口コミを投稿する
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    #map { height: 100vh; width: 100%; }
    .gm-style iframe + div { border: none !important; }

    .spot-pin {
        display: flex; flex-direction: column; align-items: center;
        cursor: pointer;
        filter: drop-shadow(0 4px 12px rgba(0,0,0,0.18));
        transition: transform 0.3s cubic-bezier(0.34,1.56,0.64,1), filter 0.3s, opacity 0.4s;
    }
    .spot-pin:hover, .spot-pin.active {
        transform: scale(1.12) translateY(-4px);
        filter: drop-shadow(0 8px 20px rgba(0,0,0,0.28));
        z-index: 9999 !important;
    }
    .spot-pin.faded { opacity: 0.2; pointer-events: none; }
    .spot-pin-photo {
        width: 64px; height: 64px; border-radius: 50%;
        overflow: hidden; border: 3px solid white;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .spot-pin-photo img { width: 100%; height: 100%; object-fit: cover; }
    .spot-pin-photo-placeholder {
        width: 100%; height: 100%;
        display: flex; align-items: center; justify-content: center; font-size: 28px;
    }
    .spot-pin-tail { width: 12px; height: 12px; transform: rotate(45deg); margin-top: -7px; border-radius: 0 0 3px 0; }
    .spot-pin-label { margin-top: 2px; padding: 2px 8px; border-radius: 8px; font-size: 10px; font-weight: 800; color: #1e293b; background: white; box-shadow: 0 1px 6px rgba(0,0,0,0.1); text-align: center; white-space: nowrap; }
    .spot-pin-badges { display: flex; gap: 2px; margin-top: 2px; flex-wrap: wrap; justify-content: center; }
    .spot-pin-badge { padding: 1px 5px; border-radius: 6px; font-size: 8px; font-weight: 800; background: white; box-shadow: 0 1px 4px rgba(0,0,0,0.08); white-space: nowrap; }
    .spot-pin-amb-badge { background: linear-gradient(135deg, #fef3c7, #fde68a); color: #92400e; border: 1px solid #f59e0b; }
    .spot-pin-amb-glow {
        position: absolute; top: -4px; left: 50%; transform: translateX(-50%);
        width: 56px; height: 56px; border-radius: 50%;
        background: radial-gradient(circle, rgba(245,158,11,0.35) 0%, transparent 70%);
        animation: ambPulse 2s ease-in-out infinite;
        pointer-events: none;
    }
    @keyframes ambPulse {
        0%,100% { opacity: 0.6; transform: translateX(-50%) scale(1); }
        50% { opacity: 1; transform: translateX(-50%) scale(1.2); }
    }

    /* ===== New Post Burst Effect ===== */
    .spot-pin-burst-ring {
        position: absolute; top: 50%; left: 50%; transform: translate(-50%,-50%);
        width: 20px; height: 20px; border-radius: 50%;
        border: 3px solid #f59e0b;
        animation: burstRing 1.5s ease-out forwards;
        pointer-events: none;
    }
    @keyframes burstRing {
        0%   { width: 20px; height: 20px; opacity: 1; border-width: 3px; }
        100% { width: 100px; height: 100px; opacity: 0; border-width: 1px; }
    }
    .spot-pin-burst-ring2 {
        position: absolute; top: 50%; left: 50%; transform: translate(-50%,-50%);
        width: 20px; height: 20px; border-radius: 50%;
        border: 2px solid #fb923c;
        animation: burstRing 1.5s 0.3s ease-out forwards;
        pointer-events: none; opacity: 0;
    }
    .spot-pin.new-post {
        animation: newPostBounce 0.8s cubic-bezier(0.34,1.56,0.64,1);
    }
    @keyframes newPostBounce {
        0%   { transform: scale(1); }
        15%  { transform: scale(1.3); }
        30%  { transform: scale(0.9); }
        50%  { transform: scale(1.15); }
        70%  { transform: scale(0.95); }
        100% { transform: scale(1); }
    }
    .spot-pin.new-post .spot-pin-photo {
        animation: photoPop 0.6s 0.1s ease-out;
    }
    @keyframes photoPop {
        0%   { box-shadow: 0 0 0 0 rgba(245,158,11,0.6); }
        50%  { box-shadow: 0 0 0 8px rgba(245,158,11,0.3); }
        100% { box-shadow: 0 0 0 0 rgba(245,158,11,0); }
    }
    .spot-pin-new-label {
        position: absolute; top: -22px; left: 50%; transform: translateX(-50%);
        background: linear-gradient(135deg, #f59e0b, #ea580c);
        color: white; font-size: 9px; font-weight: 800;
        padding: 2px 8px; border-radius: 8px;
        white-space: nowrap; pointer-events: none;
        animation: newLabelFade 4s ease-out forwards;
        box-shadow: 0 2px 8px rgba(245,158,11,0.4);
    }
    @keyframes newLabelFade {
        0%   { opacity: 0; transform: translateX(-50%) translateY(4px); }
        10%  { opacity: 1; transform: translateX(-50%) translateY(0); }
        80%  { opacity: 1; }
        100% { opacity: 0; transform: translateX(-50%) translateY(-6px); }
    }

    @keyframes pinBounce {
        0%,100% { transform: translateY(0); }
        30% { transform: translateY(-16px); }
        60% { transform: translateY(-6px); }
    }
    .spot-pin.bouncing { animation: pinBounce 0.6s ease; }

    .cat-btn { background: white; color: #6b7280; border-color: #e5e7eb; box-shadow: 0 2px 8px rgba(0,0,0,0.04); }
    .cat-btn.active { background: #14532d; color: white; border-color: #14532d; box-shadow: 0 4px 12px rgba(20,83,45,0.3); }

    .dist-btn.active { background: #22c55e; color: white; }

    #sidebar-list.open { transform: translateY(0); }

    .search-item { display: flex; align-items: center; gap: 12px; padding: 12px 16px; cursor: pointer; transition: background 0.15s; }
    .search-item:hover { background: #f0fdf4; }
    .search-item:not(:last-child) { border-bottom: 1px solid #f3f4f6; }
    .search-thumb { width: 40px; height: 40px; border-radius: 12px; overflow: hidden; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }

    .tag-pill { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; background: #f0fdf4; color: #15803d; }
    .info-card { background: #f9fafb; border-radius: 16px; padding: 14px; text-align: center; }
    .lesson-badge { display: inline-flex; align-items: center; gap: 4px; padding: 6px 12px; border-radius: 12px; font-size: 12px; font-weight: 700; }

    .sidebar-card {
        display: flex; gap: 12px; padding: 12px; background: white;
        border: 1px solid #f3f4f6; border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        cursor: pointer; transition: transform 0.2s, box-shadow 0.2s;
    }
    .sidebar-card:hover { transform: scale(1.02); box-shadow: 0 8px 24px rgba(0,0,0,0.08); }
    .sidebar-card:active { transform: scale(0.98); }
    .sidebar-card.compare-selected { border-color: #22c55e; box-shadow: 0 0 0 2px rgba(34,197,94,0.3); }
    .sidebar-card-thumb {
        width: 72px; height: 72px; border-radius: 14px; overflow: hidden; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
    }
    .sidebar-card-thumb img { width: 100%; height: 100%; object-fit: cover; }

    .compare-table { width: 100%; border-collapse: collapse; font-size: 12px; }
    .compare-table th { text-align: left; padding: 8px 6px; font-weight: 800; color: #6b7280; font-size: 11px; background: #f9fafb; }
    .compare-table td { padding: 8px 6px; font-weight: 600; color: #374151; border-bottom: 1px solid #f3f4f6; }
    .compare-table .spot-a { color: #2563eb; }
    .compare-table .spot-b { color: #ea580c; }
</style>

<script>
var favSet = new Set(@json(auth()->check() ? auth()->user()->favoriteSpots->pluck('id')->toArray() : []));
var csrfTokenSpot = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

function toggleFavorite(spotId, btn) {
    fetch('/spots/' + spotId + '/favorite', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfTokenSpot, 'Accept': 'application/json' }
    }).then(function(r){ return r.json(); }).then(function(data){
        if (data.status === 'added') {
            favSet.add(spotId);
            btn.innerHTML = '💛 お気に入り済';
            btn.style.background = '#fef3c7';
            btn.style.color = '#d97706';
            btn.style.borderColor = '#fde68a';
        } else {
            favSet.delete(spotId);
            btn.innerHTML = '🤍 お気に入り';
            btn.style.background = '#f9fafb';
            btn.style.color = '#9ca3af';
            btn.style.borderColor = '#e5e7eb';
        }
    });
}

function initApp() {
    // ==========================================
    // Google Map
    // ==========================================
    var mapEl = document.getElementById('map');
    var map = new google.maps.Map(mapEl, {
        center: { lat: 35.6360, lng: 139.6520 },
        zoom: 14,
        disableDefaultUI: true,
        zoomControl: true,
        zoomControlOptions: { position: google.maps.ControlPosition.RIGHT_CENTER },
        styles: [
            { featureType: 'poi', stylers: [{ visibility: 'off' }] },
            { featureType: 'transit', stylers: [{ visibility: 'off' }] }
        ],
        mapId: 'KIDS_COMPASS'
    });

    var catPalette = {
        'スポーツ少年団': { emoji:'⚽', border:'#f59e0b', bg:'#fef3c7', fg:'#92400e', grad:'linear-gradient(135deg,#fbbf24,#f59e0b)' },
        '個人教室':      { emoji:'🎹', border:'#a855f7', bg:'#f3e8ff', fg:'#6b21a8', grad:'linear-gradient(135deg,#c084fc,#9333ea)' },
        '塾・学習':      { emoji:'📚', border:'#3b82f6', bg:'#dbeafe', fg:'#1e40af', grad:'linear-gradient(135deg,#60a5fa,#2563eb)' },
        '施設':          { emoji:'🏊', border:'#06b6d4', bg:'#cffafe', fg:'#155e75', grad:'linear-gradient(135deg,#22d3ee,#0891b2)' }
    };
    var defPal = { emoji:'📍', border:'#94a3b8', bg:'#f1f5f9', fg:'#475569', grad:'linear-gradient(135deg,#94a3b8,#64748b)' };
    function getPal(cat) { if(!cat) return defPal; for(var k in catPalette){ if(cat.indexOf(k)!==-1) return catPalette[k]; } return defPal; }

    var allSpots = @json($spots);
    var gMarkers = []; // google.maps.marker.AdvancedMarkerElement or overlay
    var markerMap = {};
    var activeCardEl = null;
    var clusterer = null;

    // ==========================================
    // Schools
    // ==========================================
    var schools = [
        { name: '弦巻小学校', lat: 35.63971, lng: 139.65300 },
        { name: '桜小学校', lat: 35.64299, lng: 139.64535 },
        { name: '駒沢小学校', lat: 35.63306, lng: 139.65802 },
        { name: '松丘小学校', lat: 35.63885, lng: 139.64423 },
        { name: '桜丘小学校', lat: 35.64457, lng: 139.63118 },
        { name: '世田谷小学校', lat: 35.64986, lng: 139.64479 },
        { name: '旭小学校', lat: 35.63536, lng: 139.67028 },
        { name: '中丸小学校', lat: 35.63265, lng: 139.67322 },
        { name: '深沢小学校', lat: 35.62726, lng: 139.65158 },
        { name: '用賀小学校', lat: 35.63300, lng: 139.63300 },
    ];
    var schoolSelect = document.getElementById('school-select');
    schools.forEach(function(s) {
        var opt = document.createElement('option');
        opt.value = s.name; opt.textContent = s.name;
        schoolSelect.appendChild(opt);
    });

    var selectedSchool = null;
    var filterDistanceKm = 1;
    var schoolCircle = null;
    var schoolLabel = null;

    // ==========================================
    // Haversine
    // ==========================================
    function haversine(lat1, lng1, lat2, lng2) {
        var R = 6371;
        var dLat = (lat2 - lat1) * Math.PI / 180;
        var dLng = (lng2 - lng1) * Math.PI / 180;
        var a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                Math.sin(dLng/2) * Math.sin(dLng/2);
        return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    }

    function isSpotInRange(spot) {
        if (!selectedSchool) return true;
        var lat = parseFloat(spot.lat), lng = parseFloat(spot.lng);
        if (isNaN(lat) || isNaN(lng)) return false;
        return haversine(selectedSchool.lat, selectedSchool.lng, lat, lng) <= filterDistanceKm;
    }

    // ==========================================
    // School filter controls
    // ==========================================
    window.onSchoolChange = function() {
        var name = schoolSelect.value;
        if (!name) { clearSchoolFilter(); return; }
        selectedSchool = schools.find(function(s){ return s.name === name; });
        document.getElementById('school-clear').classList.remove('hidden');
        drawSchoolCircle();
        reapplyFilters();
    };

    window.setDistance = function(km) {
        filterDistanceKm = km;
        document.querySelectorAll('.dist-btn').forEach(function(b){ b.classList.remove('active'); });
        document.getElementById('dist-'+km+'km').classList.add('active');
        if (selectedSchool) { drawSchoolCircle(); reapplyFilters(); }
    };

    window.clearSchoolFilter = function() {
        selectedSchool = null;
        schoolSelect.value = '';
        document.getElementById('school-clear').classList.add('hidden');
        if (schoolCircle) { schoolCircle.setMap(null); schoolCircle = null; }
        if (schoolLabel) { schoolLabel.setMap(null); schoolLabel = null; }
        reapplyFilters();
    };

    function drawSchoolCircle() {
        if (schoolCircle) schoolCircle.setMap(null);
        if (schoolLabel) schoolLabel.setMap(null);
        if (!selectedSchool) return;

        schoolCircle = new google.maps.Circle({
            map: map,
            center: { lat: selectedSchool.lat, lng: selectedSchool.lng },
            radius: filterDistanceKm * 1000,
            fillColor: '#22c55e',
            fillOpacity: 0.08,
            strokeColor: '#22c55e',
            strokeOpacity: 0.6,
            strokeWeight: 2,
        });

        // School label marker
        var labelDiv = document.createElement('div');
        labelDiv.style.cssText = 'background:#22c55e;color:white;padding:4px 10px;border-radius:10px;font-size:11px;font-weight:800;white-space:nowrap;box-shadow:0 2px 8px rgba(0,0,0,0.2);';
        labelDiv.textContent = '🏫 ' + selectedSchool.name;
        schoolLabel = new google.maps.marker.AdvancedMarkerElement({
            map: map,
            position: { lat: selectedSchool.lat, lng: selectedSchool.lng },
            content: labelDiv,
            zIndex: 10000
        });

        map.panTo({ lat: selectedSchool.lat, lng: selectedSchool.lng });
        map.setZoom(filterDistanceKm === 1 ? 15 : 14);
    }

    function reapplyFilters() {
        var activeCat = document.querySelector('.cat-btn.active');
        var cat = activeCat ? activeCat.dataset.cat : 'すべて';
        renderSpots(cat);
    }

    // ==========================================
    // Photo helper
    // ==========================================
    function photoOf(s) {
        if(!s.image_path) return null;
        if(s.image_path.startsWith('http')) return s.image_path;
        return '/storage/'+s.image_path.replace('public/','');
    }

    // ==========================================
    // Pin HTML builder
    // ==========================================
    function buildPinHtml(spot, pal, photo) {
        var ph = photo ? '<img src="'+photo+'" alt="">' : '<div class="spot-pin-photo-placeholder" style="background:'+pal.grad+';"><span>'+pal.emoji+'</span></div>';
        var hasAmbPost = spot.latest_ambassador_post;
        var ambIndicator = hasAmbPost ? '<div class="spot-pin-amb-glow"></div>' : '';
        var badges = '', items = [];
        if(hasAmbPost) items.push('<span class="spot-pin-badge spot-pin-amb-badge">⭐ 通信あり</span>');
        if(spot.monthly_fee_range) items.push('<span class="spot-pin-badge" style="color:#b45309;">💰'+spot.monthly_fee_range+'</span>');
        if(spot.has_parent_duty) items.push('<span class="spot-pin-badge" style="color:#dc2626;">当番あり</span>');
        else items.push('<span class="spot-pin-badge" style="color:#16a34a;">当番なし</span>');
        if(spot.transfer_available) items.push('<span class="spot-pin-badge" style="color:#2563eb;">振替OK</span>');
        if(spot.osagari_count > 0) items.push('<span class="spot-pin-badge" style="background:#dcfce7;color:#15803d;border:1px solid #86efac;">🎁 お下がり有</span>');
        if(items.length) badges = '<div class="spot-pin-badges">'+items.join('')+'</div>';
        return '<div class="spot-pin" data-id="'+spot.id+'">'+ambIndicator+'<div class="spot-pin-photo" style="border-color:'+(hasAmbPost?'#f59e0b':pal.border)+';">'+ph+'</div><div class="spot-pin-tail" style="background:'+(hasAmbPost?'#f59e0b':pal.border)+';"></div><div class="spot-pin-label">'+spot.title+'</div>'+badges+'</div>';
    }

    function createPinElement(spot, pal, photo, faded) {
        var div = document.createElement('div');
        div.innerHTML = buildPinHtml(spot, pal, photo);
        if (faded) {
            var pin = div.querySelector('.spot-pin');
            if (pin) pin.classList.add('faded');
        }
        return div.firstElementChild;
    }

    // ==========================================
    // Render spots with clustering
    // ==========================================
    function renderSpots(filter) {
        // Clear existing
        gMarkers.forEach(function(m) { m.setMap(null); });
        gMarkers = [];
        markerMap = {};
        if (clusterer) { clusterer.clearMarkers(); clusterer = null; }

        var clusterableMarkers = [];

        allSpots.forEach(function(spot) {
            var lat = parseFloat(spot.lat), lng = parseFloat(spot.lng);
            if(isNaN(lat)||isNaN(lng)) return;
            if(filter && filter!=='すべて' && (!spot.category || spot.category.indexOf(filter)===-1)) return;

            var pal = getPal(spot.category), photo = photoOf(spot);
            var inRange = isSpotInRange(spot);
            var pinEl = createPinElement(spot, pal, photo, !inRange);

            var marker = new google.maps.marker.AdvancedMarkerElement({
                position: { lat: lat, lng: lng },
                content: pinEl,
                zIndex: inRange ? 100 : 1
            });

            pinEl.addEventListener('click', function() {
                openDetail(spot, pal, photo, marker);
            });

            gMarkers.push(marker);
            markerMap[spot.id] = { marker: marker, spot: spot, pal: pal, photo: photo, inRange: inRange, pinEl: pinEl };

            if (inRange) {
                clusterableMarkers.push(marker);
            } else {
                marker.map = map;
            }
        });

        // Clustering for in-range markers
        if (typeof markerClusterer !== 'undefined' && clusterableMarkers.length) {
            clusterer = new markerClusterer.MarkerClusterer({
                map: map,
                markers: clusterableMarkers,
                renderer: {
                    render: function(cluster, stats) {
                        var count = cluster.count;
                        var pos = cluster.position;
                        var svg = '<svg width="44" height="44" xmlns="http://www.w3.org/2000/svg">' +
                            '<circle cx="22" cy="22" r="20" fill="rgba(34,197,94,0.2)" stroke="rgba(34,197,94,0.6)" stroke-width="2"/>' +
                            '<circle cx="22" cy="22" r="14" fill="rgba(34,197,94,0.7)"/>' +
                            '<text x="22" y="27" text-anchor="middle" fill="white" font-size="13" font-weight="800">' + count + '</text>' +
                            '</svg>';
                        var div = document.createElement('div');
                        div.innerHTML = svg;
                        return new google.maps.marker.AdvancedMarkerElement({
                            position: pos,
                            content: div.firstElementChild,
                            zIndex: 1000 + count
                        });
                    }
                }
            });
        } else {
            // No clusterer library - just add all to map
            clusterableMarkers.forEach(function(m) { m.map = map; });
        }

        buildSidebarCards(filter);
    }

    // === Bounce a pin ===
    function bouncePin(spotId) {
        var entry = markerMap[spotId]; if(!entry) return;
        var pin = entry.pinEl; if(!pin) return;
        pin.classList.remove('bouncing');
        void pin.offsetWidth;
        pin.classList.add('bouncing');
        if (entry.marker) entry.marker.zIndex = 9999;
        setTimeout(function(){ pin.classList.remove('bouncing'); }, 700);
    }

    // ==========================================
    // Compare feature
    // ==========================================
    var compareSet = new Set();
    var compareBar = document.getElementById('compare-bar');
    var compareCount = document.getElementById('compare-count');

    window.toggleCompare = function(spotId, event) {
        event.stopPropagation();
        if (compareSet.has(spotId)) {
            compareSet.delete(spotId);
        } else {
            if (compareSet.size >= 2) {
                var first = compareSet.values().next().value;
                compareSet.delete(first);
                var oldCard = document.querySelector('.sidebar-card[data-spot-id="'+first+'"]');
                if (oldCard) oldCard.classList.remove('compare-selected');
                var oldCb = document.getElementById('compare-cb-'+first);
                if (oldCb) oldCb.checked = false;
            }
            compareSet.add(spotId);
        }
        var cardEl = document.querySelector('.sidebar-card[data-spot-id="'+spotId+'"]');
        if (cardEl) cardEl.classList.toggle('compare-selected', compareSet.has(spotId));
        var cb = document.getElementById('compare-cb-'+spotId);
        if (cb) cb.checked = compareSet.has(spotId);
        compareCount.textContent = compareSet.size;
        compareBar.classList.toggle('hidden', compareSet.size === 0);
    };

    window.clearCompare = function() {
        compareSet.forEach(function(id) {
            var cardEl = document.querySelector('.sidebar-card[data-spot-id="'+id+'"]');
            if (cardEl) cardEl.classList.remove('compare-selected');
            var cb = document.getElementById('compare-cb-'+id);
            if (cb) cb.checked = false;
        });
        compareSet.clear();
        compareCount.textContent = '0';
        compareBar.classList.add('hidden');
    };

    // ==========================================
    // Compare view
    // ==========================================
    window.openCompareView = function() {
        if (compareSet.size < 2) return;
        var ids = Array.from(compareSet);
        var spotA = allSpots.find(function(s){ return s.id === ids[0]; });
        var spotB = allSpots.find(function(s){ return s.id === ids[1]; });
        if (!spotA || !spotB) return;

        var axes = ['satisfaction','skill_growth','cost_performance','teacher_passion','parent_burden'];
        var axisLabels = ['満足度','上達度','コスパ','先生の熱意','親の負担\n(★多=楽！)'];

        function calcAvgs(spot) {
            var reviews = spot.reviews || [];
            var sums = [0,0,0,0,0], counts = [0,0,0,0,0];
            reviews.forEach(function(r) {
                axes.forEach(function(a, i) { if(r[a]) { sums[i]+=r[a]; counts[i]++; } });
            });
            return sums.map(function(s,i){ return counts[i] ? Math.round(s/counts[i]*10)/10 : 0; });
        }

        var avgsA = calcAvgs(spotA);
        var avgsB = calcAvgs(spotB);

        var body = '';

        body += '<div style="display:flex;gap:12px;justify-content:center;margin-bottom:12px;">';
        body += '<div style="display:flex;align-items:center;gap:6px;"><div style="width:12px;height:12px;border-radius:50%;background:rgba(37,99,235,0.7);"></div><span style="font-size:11px;font-weight:800;color:#2563eb;">'+spotA.title+'</span></div>';
        body += '<div style="display:flex;align-items:center;gap:6px;"><div style="width:12px;height:12px;border-radius:50%;background:rgba(234,88,12,0.7);"></div><span style="font-size:11px;font-weight:800;color:#ea580c;">'+spotB.title+'</span></div>';
        body += '</div>';

        body += '<div style="background:#f9fafb;border-radius:20px;padding:16px;margin-bottom:16px;text-align:center;">';
        body += '<canvas id="compare-radar" width="280" height="280" style="max-width:280px;margin:0 auto;"></canvas>';
        body += '</div>';

        body += '<div style="background:#fef3c7;border-radius:12px;padding:10px 14px;margin-bottom:16px;display:flex;align-items:center;gap:8px;">';
        body += '<span style="font-size:18px;">⚠️</span>';
        body += '<span style="font-size:11px;font-weight:700;color:#92400e;">「親の負担」は★が多いほど楽！ 送迎・当番・準備の少なさを表します</span>';
        body += '</div>';

        body += '<div style="margin-bottom:16px;">';
        body += '<p style="font-size:13px;font-weight:800;color:#374151;margin-bottom:8px;">📋 詳細比較</p>';
        body += '<div style="background:#f9fafb;border-radius:16px;overflow:hidden;">';
        body += '<table class="compare-table">';
        body += '<thead><tr><th></th><th class="spot-a">'+spotA.title+'</th><th class="spot-b">'+spotB.title+'</th></tr></thead>';
        body += '<tbody>';
        body += '<tr><td style="font-weight:800;color:#6b7280;">💰 月謝</td><td class="spot-a">'+(spotA.monthly_fee_range||'未登録')+'</td><td class="spot-b">'+(spotB.monthly_fee_range||'未登録')+'</td></tr>';
        body += '<tr><td style="font-weight:800;color:#6b7280;">🔄 振替</td><td class="spot-a">'+(spotA.transfer_available?'<span style="color:#16a34a;font-weight:800;">OK</span>':'<span style="color:#dc2626;">不可</span>')+'</td><td class="spot-b">'+(spotB.transfer_available?'<span style="color:#16a34a;font-weight:800;">OK</span>':'<span style="color:#dc2626;">不可</span>')+'</td></tr>';
        body += '<tr><td style="font-weight:800;color:#6b7280;">📋 当番</td><td class="spot-a">'+(spotA.has_parent_duty?'<span style="color:#dc2626;font-weight:800;">あり</span>':'<span style="color:#16a34a;font-weight:800;">なし</span>')+'</td><td class="spot-b">'+(spotB.has_parent_duty?'<span style="color:#dc2626;font-weight:800;">あり</span>':'<span style="color:#16a34a;font-weight:800;">なし</span>')+'</td></tr>';
        body += '<tr><td style="font-weight:800;color:#6b7280;">📝 方針</td><td class="spot-a">'+(spotA.policy_type||'-')+'</td><td class="spot-b">'+(spotB.policy_type||'-')+'</td></tr>';

        var pbA = avgsA[4], pbB = avgsB[4];
        body += '<tr style="background:#fef9c3;"><td style="font-weight:800;color:#92400e;">⭐ 親の負担<br><span style="font-size:9px;color:#b45309;">(★多=楽！)</span></td>';
        body += '<td class="spot-a" style="font-weight:800;">'+(pbA ? pbA.toFixed(1)+' / 5.0' : '-')+'</td>';
        body += '<td class="spot-b" style="font-weight:800;">'+(pbB ? pbB.toFixed(1)+' / 5.0' : '-')+'</td></tr>';

        var ratingNames = ['満足度','上達度','コスパ','先生の熱意'];
        for (var ri = 0; ri < 4; ri++) {
            body += '<tr><td style="font-weight:800;color:#6b7280;">'+ratingNames[ri]+'</td>';
            body += '<td class="spot-a">'+(avgsA[ri] ? avgsA[ri].toFixed(1)+' / 5.0' : '-')+'</td>';
            body += '<td class="spot-b">'+(avgsB[ri] ? avgsB[ri].toFixed(1)+' / 5.0' : '-')+'</td></tr>';
        }
        body += '</tbody></table></div></div>';
        body += '<button onclick="closeCompareView()" style="width:100%;padding:14px;border-radius:16px;background:#111827;color:white;font-weight:700;font-size:13px;border:none;cursor:pointer;">閉じる</button>';

        document.getElementById('compare-body').innerHTML = body;
        document.getElementById('compare-overlay').classList.remove('hidden');

        setTimeout(function() {
            var ctx = document.getElementById('compare-radar');
            if (!ctx) return;
            new Chart(ctx, {
                type: 'radar',
                data: {
                    labels: axisLabels,
                    datasets: [
                        { label: spotA.title, data: avgsA, backgroundColor: 'rgba(37,99,235,0.15)', borderColor: 'rgba(37,99,235,0.8)', borderWidth: 2.5, pointBackgroundColor: 'rgba(37,99,235,1)', pointRadius: 5 },
                        { label: spotB.title, data: avgsB, backgroundColor: 'rgba(234,88,12,0.15)', borderColor: 'rgba(234,88,12,0.8)', borderWidth: 2.5, pointBackgroundColor: 'rgba(234,88,12,1)', pointRadius: 5 }
                    ]
                },
                options: {
                    responsive: false,
                    plugins: { legend: { display: false } },
                    scales: { r: { min: 0, max: 5, ticks: { stepSize: 1, font: { size: 10 }, backdropColor: 'transparent' }, pointLabels: { font: { size: 11, weight: '700' }, color: '#374151' }, grid: { color: 'rgba(0,0,0,0.06)' }, angleLines: { color: 'rgba(0,0,0,0.06)' } } }
                }
            });
        }, 80);
    };

    window.closeCompareView = function() {
        document.getElementById('compare-overlay').classList.add('hidden');
    };

    // ==========================================
    // Sidebar
    // ==========================================
    var sidebarList = document.getElementById('sidebar-list');
    var sidebarCards = document.getElementById('sidebar-cards');
    var listOpen = false;

    function buildSidebarCards(filter) {
        var html = '';
        allSpots.forEach(function(spot) {
            if(filter && filter!=='すべて' && (!spot.category || spot.category.indexOf(filter)===-1)) return;
            var inRange = isSpotInRange(spot);
            if (selectedSchool && !inRange) return;

            var pal = getPal(spot.category), photo = photoOf(spot);
            var thumbHtml = photo
                ? '<div class="sidebar-card-thumb"><img src="'+photo+'"></div>'
                : '<div class="sidebar-card-thumb" style="background:'+pal.grad+';"><span style="font-size:28px;">'+pal.emoji+'</span></div>';

            var badgeHtml = '';
            if(spot.policy_type) {
                var pi = spot.policy_type==='褒めて伸ばす'?'🌱':spot.policy_type==='厳しく鍛える'?'🔥':'⚖️';
                var pc = spot.policy_type==='褒めて伸ばす'?'background:#dcfce7;color:#166534;':spot.policy_type==='厳しく鍛える'?'background:#fee2e2;color:#991b1b;':'background:#dbeafe;color:#1e40af;';
                badgeHtml += '<span style="display:inline-block;padding:1px 6px;border-radius:6px;font-size:9px;font-weight:800;'+pc+'">'+pi+spot.policy_type+'</span> ';
            }
            if(spot.has_parent_duty) badgeHtml += '<span style="display:inline-block;padding:1px 6px;border-radius:6px;font-size:9px;font-weight:800;background:#fee2e2;color:#dc2626;">当番あり</span> ';
            else badgeHtml += '<span style="display:inline-block;padding:1px 6px;border-radius:6px;font-size:9px;font-weight:800;background:#dcfce7;color:#16a34a;">当番なし</span> ';
            if(spot.transfer_available) badgeHtml += '<span style="display:inline-block;padding:1px 6px;border-radius:6px;font-size:9px;font-weight:800;background:#dbeafe;color:#2563eb;">振替OK</span>';

            var isSelected = compareSet.has(spot.id);
            html += '<div class="sidebar-card'+(isSelected?' compare-selected':'')+'" data-spot-id="'+spot.id+'" onclick="onCardClick('+spot.id+')">'+
                '<div style="display:flex;flex-direction:column;align-items:center;justify-content:center;margin-right:4px;">'+
                '<label onclick="event.stopPropagation();" style="cursor:pointer;">'+
                '<input type="checkbox" id="compare-cb-'+spot.id+'" '+(isSelected?'checked':'')+' onchange="toggleCompare('+spot.id+', event)" style="width:16px;height:16px;accent-color:#22c55e;cursor:pointer;">'+
                '</label>'+
                '<span style="font-size:8px;color:#9ca3af;font-weight:700;margin-top:2px;">比較</span>'+
                '</div>'+
                thumbHtml+
                '<div style="flex:1;min-width:0;">'+
                '<p style="font-size:13px;font-weight:800;color:#1f2937;margin-bottom:2px;">'+spot.title+'</p>'+
                '<p style="font-size:10px;color:#9ca3af;font-weight:600;margin-bottom:4px;">'+pal.emoji+' '+(spot.category||'')+
                (spot.monthly_fee_range ? ' · 💰'+spot.monthly_fee_range : '')+'</p>'+
                '<div>'+badgeHtml+'</div>'+
                '</div></div>';
        });
        sidebarCards.innerHTML = html || '<p style="text-align:center;color:#9ca3af;font-size:13px;padding:20px;">該当なし</p>';
    }

    window.toggleListView = function() {
        listOpen = !listOpen;
        sidebarList.classList.toggle('open', listOpen);
    };

    window.onCardClick = function(id) {
        var entry = markerMap[id]; if(!entry) return;
        listOpen = false;
        sidebarList.classList.remove('open');
        map.panTo({ lat: parseFloat(entry.spot.lat), lng: parseFloat(entry.spot.lng) });
        map.setZoom(16);
        setTimeout(function(){
            bouncePin(id);
            openDetail(entry.spot, entry.pal, entry.photo, entry.marker);
        }, 600);
    };

    // ==========================================
    // Detail modal
    // ==========================================
    var overlay = document.getElementById('detail-overlay');
    var backdrop = document.getElementById('detail-backdrop');
    var card = document.getElementById('detail-card');
    var heroBg = document.getElementById('hero-bg');
    var heroBadges = document.getElementById('hero-badges');
    var heroTitle = document.getElementById('hero-title');
    var detailBody = document.getElementById('detail-body');
    var detailOpen = false;

    // Close button event (not inline onclick — more reliable)
    document.getElementById('detail-close-btn').addEventListener('click', function(e) {
        e.stopPropagation();
        closeDetail();
    });

    // Backdrop click
    backdrop.addEventListener('click', function() { closeDetail(); });

    // Swipe-to-dismiss on drag handle
    (function() {
        var handle = document.getElementById('detail-drag-handle');
        var startY = 0, currentY = 0, dragging = false;

        handle.addEventListener('touchstart', function(e) {
            startY = e.touches[0].clientY;
            currentY = startY;
            dragging = true;
            card.style.transition = 'none';
        }, { passive: true });

        document.addEventListener('touchmove', function(e) {
            if (!dragging) return;
            currentY = e.touches[0].clientY;
            var dy = Math.max(0, currentY - startY);
            card.style.transform = 'translateY(' + dy + 'px)';
            backdrop.style.opacity = Math.max(0, 1 - dy / 300);
        }, { passive: true });

        document.addEventListener('touchend', function() {
            if (!dragging) return;
            dragging = false;
            card.style.transition = '';
            backdrop.style.opacity = '';
            var dy = currentY - startY;
            if (dy > 100) {
                closeDetail();
            } else {
                card.style.transform = 'translateY(0)';
            }
        });
    })();

    function openDetail(spot, pal, photo, marker) {
        if(activeCardEl) activeCardEl.classList.remove('active');
        var entry = markerMap[spot.id];
        if(entry && entry.pinEl) { entry.pinEl.classList.add('active'); activeCardEl = entry.pinEl; }

        if(photo) { heroBg.innerHTML='<img src="'+photo+'" style="width:100%;height:100%;object-fit:cover;">'; heroBg.parentElement.style.minHeight='260px'; }
        else { heroBg.innerHTML='<div style="width:100%;height:100%;background:'+pal.grad+';display:flex;align-items:center;justify-content:center;"><span style="font-size:64px;">'+pal.emoji+'</span></div>'; heroBg.parentElement.style.minHeight='220px'; }

        var bH = '<span style="display:inline-flex;align-items:center;gap:4px;padding:4px 10px;border-radius:20px;font-size:11px;font-weight:800;background:rgba(255,255,255,0.2);backdrop-filter:blur(8px);color:white;border:1px solid rgba(255,255,255,0.3);">'+pal.emoji+' '+(spot.category||'')+'</span>';
        if(spot.age_range) bH+='<span style="display:inline-flex;align-items:center;gap:4px;padding:4px 10px;border-radius:20px;font-size:11px;font-weight:800;background:rgba(255,255,255,0.2);backdrop-filter:blur(8px);color:white;border:1px solid rgba(255,255,255,0.3);">🎒 '+spot.age_range+'歳</span>';
        heroBadges.innerHTML=bH;
        heroTitle.textContent=spot.title;

        var tags = spot.category_tags||[], timeAgo = spot.created_at?timeSince(new Date(spot.created_at)):'たった今';
        var body = '';

        var lb = [];
        if(spot.monthly_fee_range) lb.push('<div class="lesson-badge" style="background:#fef3c7;color:#92400e;">💰 '+spot.monthly_fee_range+'</div>');
        if(spot.has_parent_duty) lb.push('<div class="lesson-badge" style="background:#fee2e2;color:#dc2626;">📋 当番あり</div>');
        else lb.push('<div class="lesson-badge" style="background:#dcfce7;color:#16a34a;">📋 当番なし</div>');
        if(spot.policy_type) {
            var pc=spot.policy_type==='褒めて伸ばす'?'background:#dcfce7;color:#166534;':spot.policy_type==='厳しく鍛える'?'background:#fee2e2;color:#991b1b;':'background:#dbeafe;color:#1e40af;';
            var pi=spot.policy_type==='褒めて伸ばす'?'🌱':spot.policy_type==='厳しく鍛える'?'🔥':'⚖️';
            lb.push('<div class="lesson-badge" style="'+pc+'">'+pi+' '+spot.policy_type+'</div>');
        }
        if(spot.transfer_available) lb.push('<div class="lesson-badge" style="background:#dbeafe;color:#1e40af;">🔄 振替OK</div>');
        else lb.push('<div class="lesson-badge" style="background:#f3f4f6;color:#6b7280;">🔄 振替不可</div>');
        if(spot.osagari_count > 0) lb.push('<div class="lesson-badge" style="background:#dcfce7;color:#15803d;border:1px solid #86efac;">🎁 お下がり有</div>');
        body += '<div style="display:flex;flex-wrap:wrap;gap:6px;margin-bottom:16px;">'+lb.join('')+'</div>';

        var infos = [];
        if(spot.age_range) infos.push('<div class="info-card"><p style="font-size:20px;margin-bottom:2px;">🎒</p><p style="font-size:10px;font-weight:700;color:#9ca3af;">対象年齢</p><p style="font-size:13px;font-weight:900;color:#1f2937;">'+spot.age_range+'歳</p></div>');
        if(spot.parent_role) infos.push('<div class="info-card"><p style="font-size:20px;margin-bottom:2px;">👨‍👩‍👧</p><p style="font-size:10px;font-weight:700;color:#9ca3af;">親の役割</p><p style="font-size:11px;font-weight:800;color:#1f2937;">'+spot.parent_role+'</p></div>');
        infos.push('<div class="info-card"><p style="font-size:20px;margin-bottom:2px;">🕐</p><p style="font-size:10px;font-weight:700;color:#9ca3af;">投稿</p><p style="font-size:13px;font-weight:900;color:#1f2937;">'+timeAgo+'</p></div>');
        body += '<div style="display:grid;grid-template-columns:repeat('+Math.min(infos.length,3)+',1fr);gap:8px;margin-bottom:16px;">'+infos.join('')+'</div>';

        if(spot.note) {
            body += '<div style="background:#f9fafb;border-radius:20px;padding:16px;margin-bottom:16px;"><div style="display:flex;align-items:center;gap:8px;margin-bottom:10px;"><div style="width:32px;height:32px;border-radius:50%;background:'+pal.grad+';display:flex;align-items:center;justify-content:center;"><span style="font-size:14px;color:white;">🙋</span></div><div><p style="font-size:12px;font-weight:800;color:#374151;">ご近所さんの口コミ</p></div></div><p style="font-size:13px;color:#4b5563;line-height:1.7;font-weight:500;">'+spot.note+'</p></div>';
        }

        var reviews = spot.reviews||[];
        if(reviews.length) {
            var axes = ['satisfaction','skill_growth','cost_performance','teacher_passion','parent_burden'];
            var axisLabels = ['満足度','上達度','コスパ','先生の熱意','親の負担\n(★多=楽)'];
            var sums = [0,0,0,0,0], counts = [0,0,0,0,0];
            reviews.forEach(function(r){ axes.forEach(function(a,i){ if(r[a]){ sums[i]+=r[a]; counts[i]++; } }); });
            var avgs = sums.map(function(s,i){ return counts[i]?Math.round(s/counts[i]*10)/10:0; });

            body += '<div style="margin-bottom:16px;"><p style="font-size:13px;font-weight:800;color:#374151;margin-bottom:10px;">📊 みんなのレビュー（'+reviews.length+'件）</p>';
            body += '<div style="background:#f9fafb;border-radius:20px;padding:16px;margin-bottom:12px;text-align:center;"><canvas id="radar-chart" width="260" height="260" style="max-width:260px;margin:0 auto;"></canvas></div>';

            reviews.forEach(function(r) {
                body += '<div style="background:#f9fafb;border-radius:16px;padding:14px;margin-bottom:8px;">';
                if(r.vibe_tag) {
                    var vc={'ガチ勢':'background:#fee2e2;color:#dc2626;','エンジョイ勢':'background:#dcfce7;color:#16a34a;','のびのび系':'background:#fef3c7;color:#b45309;','受験特化':'background:#dbeafe;color:#2563eb;'};
                    body += '<span style="display:inline-block;padding:2px 8px;border-radius:8px;font-size:10px;font-weight:800;margin-bottom:6px;'+(vc[r.vibe_tag]||'')+'">'+r.vibe_tag+'</span>';
                }
                var rt=[];
                if(r.satisfaction) rt.push('満足度 '+'★'.repeat(r.satisfaction)+'☆'.repeat(5-r.satisfaction));
                if(r.skill_growth) rt.push('上達度 '+'★'.repeat(r.skill_growth)+'☆'.repeat(5-r.skill_growth));
                if(r.cost_performance) rt.push('コスパ '+'★'.repeat(r.cost_performance)+'☆'.repeat(5-r.cost_performance));
                if(r.teacher_passion) rt.push('先生の熱意 '+'★'.repeat(r.teacher_passion)+'☆'.repeat(5-r.teacher_passion));
                if(r.parent_burden) rt.push('親の負担(★多=楽) '+'★'.repeat(r.parent_burden)+'☆'.repeat(5-r.parent_burden));
                if(rt.length) body += '<div style="font-size:11px;color:#6b7280;font-weight:600;margin-bottom:6px;line-height:1.6;">'+rt.join('<br>')+'</div>';
                if(r.body) body += '<p style="font-size:12px;color:#4b5563;line-height:1.6;">'+r.body+'</p>';
                body += '</div>';
            });
            body += '</div>';

            setTimeout(function(){
                var ctx = document.getElementById('radar-chart');
                if(!ctx) return;
                new Chart(ctx, {
                    type: 'radar',
                    data: { labels: axisLabels, datasets: [{ label: '平均スコア', data: avgs, backgroundColor: 'rgba(34,197,94,0.15)', borderColor: 'rgba(34,197,94,0.8)', borderWidth: 2, pointBackgroundColor: 'rgba(34,197,94,1)', pointRadius: 4 }] },
                    options: { responsive: false, plugins: { legend: { display: false } }, scales: { r: { min: 0, max: 5, ticks: { stepSize: 1, font: { size: 10 }, backdropColor: 'transparent' }, pointLabels: { font: { size: 11, weight: '700' }, color: '#374151' }, grid: { color: 'rgba(0,0,0,0.06)' }, angleLines: { color: 'rgba(0,0,0,0.06)' } } } }
                });
            }, 50);
        }

        if(tags.length) {
            body += '<div style="display:flex;flex-wrap:wrap;gap:6px;margin-bottom:16px;">';
            tags.forEach(function(t){ body += '<span class="tag-pill">#'+t+'</span>'; });
            body += '</div>';
        }

        // Ambassador post section
        if(spot.latest_ambassador_post) {
            var ap = spot.latest_ambassador_post;
            var apPhoto = ap.photo_path && ap.photo_path.startsWith('http') ? ap.photo_path : '/storage/' + ap.photo_path;
            body += '<div style="margin-bottom:16px;border:2px solid #fde68a;border-radius:20px;overflow:hidden;background:linear-gradient(180deg,#fffbeb,#ffffff);">';
            body += '<div style="padding:12px 14px 8px;display:flex;align-items:center;gap:8px;">';
            body += '<div style="width:8px;height:8px;border-radius:50%;background:#f59e0b;animation:ambPulse 2s ease-in-out infinite;"></div>';
            body += '<p style="font-size:12px;font-weight:800;color:#92400e;">⭐ 公式アンバサダー通信</p></div>';
            body += '<img src="'+apPhoto+'" style="width:100%;aspect-ratio:16/9;object-fit:cover;" loading="lazy">';
            body += '<div style="padding:12px 14px 14px;">';
            if(ap.mood_tag) body += '<span style="display:inline-block;padding:2px 8px;border-radius:8px;font-size:10px;font-weight:800;background:#fef3c7;color:#92400e;margin-bottom:6px;">'+ap.mood_tag+'</span>';
            body += '<p style="font-size:13px;color:#4b5563;line-height:1.6;font-weight:500;">'+ap.message+'</p>';
            body += '<a href="/ambassador/'+(spot.ambassador_user_id||'')+'" style="display:inline-flex;align-items:center;gap:4px;margin-top:8px;font-size:11px;font-weight:700;color:#d97706;text-decoration:none;">この先生の声をもっと聞く →</a>';
            body += '</div></div>';
        }

        body += '<div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:8px;">';
        body += '<button onclick="toggleFavorite('+spot.id+', this)" id="fav-btn-'+spot.id+'" style="display:flex;align-items:center;justify-content:center;gap:6px;padding:14px;border-radius:16px;background:'+(favSet.has(spot.id)?'#fef3c7':'#f9fafb')+';color:'+(favSet.has(spot.id)?'#d97706':'#9ca3af')+';font-weight:700;font-size:13px;border:1px solid '+(favSet.has(spot.id)?'#fde68a':'#e5e7eb')+';cursor:pointer;">'+(favSet.has(spot.id)?'💛 お気に入り済':'🤍 お気に入り')+'</button>';
        body += '<button onclick="openReviewForm('+spot.id+')" style="display:flex;align-items:center;justify-content:center;gap:6px;padding:14px;border-radius:16px;background:#fef3c7;color:#92400e;font-weight:700;font-size:13px;border:1px solid #fde68a;cursor:pointer;">📝 口コミ投稿</button>';
        body += '</div>';
        body += '<div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">';
        body += '<button onclick="shareSpot()" style="display:flex;align-items:center;justify-content:center;gap:6px;padding:14px;border-radius:16px;background:#f0fdf4;color:#16a34a;font-weight:700;font-size:13px;border:none;cursor:pointer;">シェア</button>';
        body += '<button onclick="closeDetail()" style="display:flex;align-items:center;justify-content:center;gap:6px;padding:14px;border-radius:16px;background:#111827;color:white;font-weight:700;font-size:13px;border:none;cursor:pointer;">閉じる</button>';
        body += '</div>';

        detailBody.innerHTML = body;

        // Enable pointer events
        overlay.style.pointerEvents = 'auto';
        backdrop.style.pointerEvents = 'auto';
        card.style.pointerEvents = 'auto';

        // Animate in
        backdrop.style.background = 'rgba(0,0,0,0.45)';
        requestAnimationFrame(function(){
            card.style.transform = 'translateY(0)';
        });
        detailOpen = true;

        map.panTo({ lat: parseFloat(spot.lat), lng: parseFloat(spot.lng) });
    }

    window.closeDetail = function() {
        if (!detailOpen) return;
        detailOpen = false;

        card.style.transform = 'translateY(100%)';
        backdrop.style.background = 'rgba(0,0,0,0)';

        if(activeCardEl){ activeCardEl.classList.remove('active'); activeCardEl = null; }

        setTimeout(function(){
            overlay.style.pointerEvents = 'none';
            backdrop.style.pointerEvents = 'none';
            card.style.pointerEvents = 'none';
        }, 450);
    };
    window.shareSpot = function() { var t=heroTitle.textContent; if(navigator.share) navigator.share({title:t,text:t+' - KIDS COMPASS',url:location.href}); };

    // ==========================================
    // Review form
    // ==========================================
    window.openReviewForm = function(spotId) {
        document.getElementById('review-spot-id').value = spotId;
        document.getElementById('review-form').action = '/spots/'+spotId+'/reviews';
        document.getElementById('review-overlay').classList.remove('hidden');
    };
    window.closeReviewForm = function() {
        document.getElementById('review-overlay').classList.add('hidden');
    };

    function timeSince(d) {
        var s=Math.floor((Date.now()-d.getTime())/1000);
        if(s<60) return 'たった今'; var m=Math.floor(s/60); if(m<60) return m+'分前';
        var h=Math.floor(m/60); if(h<24) return h+'時間前'; var dy=Math.floor(h/24);
        if(dy<7) return dy+'日前'; var w=Math.floor(dy/7); if(w<5) return w+'週間前';
        return Math.floor(dy/30)+'ヶ月前';
    }

    window.filterCategory = function(cat) {
        document.querySelectorAll('.cat-btn').forEach(function(b){b.classList.remove('active');});
        var sel=document.querySelector('.cat-btn[data-cat="'+cat+'"]'); if(sel) sel.classList.add('active');
        renderSpots(cat);
    };

    // ==========================================
    // Search
    // ==========================================
    var searchInput=document.getElementById('map-search'), dropdown=document.getElementById('search-dropdown'), clearBtn=document.getElementById('search-clear');
    searchInput.addEventListener('input', function() {
        var q=this.value.toLowerCase().trim();
        clearBtn.classList.toggle('hidden',!q);
        if(!q){dropdown.classList.add('hidden');return;}
        var results=allSpots.filter(function(s){
            var t=(s.category_tags||[]).join(' ').toLowerCase();
            return (s.title&&s.title.toLowerCase().indexOf(q)!==-1)||(s.category&&s.category.toLowerCase().indexOf(q)!==-1)||(s.monthly_fee_range&&s.monthly_fee_range.indexOf(q)!==-1)||t.indexOf(q)!==-1;
        });
        if(!results.length){dropdown.innerHTML='<div style="padding:16px;text-align:center;color:#9ca3af;font-size:13px;">見つかりませんでした</div>';dropdown.classList.remove('hidden');return;}
        var html='';
        results.forEach(function(s){
            var pal=getPal(s.category);
            html+='<div class="search-item" onclick="jumpToSpot('+s.id+')"><div class="search-thumb" style="background:'+pal.bg+';"><span style="font-size:18px;">'+pal.emoji+'</span></div><div style="flex:1;"><p style="font-size:13px;font-weight:700;color:#1f2937;">'+s.title+'</p><p style="font-size:11px;color:#9ca3af;">'+(s.category||'')+(s.monthly_fee_range?' · '+s.monthly_fee_range:'')+'</p></div></div>';
        });
        dropdown.innerHTML=html; dropdown.classList.remove('hidden');
    });
    window.jumpToSpot = function(id) {
        var entry=markerMap[id]; if(!entry) return;
        dropdown.classList.add('hidden'); searchInput.value=''; clearBtn.classList.add('hidden');
        map.panTo({ lat: parseFloat(entry.spot.lat), lng: parseFloat(entry.spot.lng) });
        map.setZoom(17);
        setTimeout(function(){ bouncePin(id); openDetail(entry.spot,entry.pal,entry.photo,entry.marker); },800);
    };
    window.clearSearch = function(){ searchInput.value=''; clearBtn.classList.add('hidden'); dropdown.classList.add('hidden'); renderSpots('すべて'); };
    document.addEventListener('click', function(e){ if(!e.target.closest('#map-search')&&!e.target.closest('#search-dropdown')) dropdown.classList.add('hidden'); });

    renderSpots('すべて');

    // Auto-focus on a spot if ?focus= parameter is present
    var urlParams = new URLSearchParams(window.location.search);
    var focusId = urlParams.get('focus');
    if (focusId) {
        focusId = parseInt(focusId);
        setTimeout(function() {
            var entry = markerMap[focusId];
            if (entry) {
                map.panTo({ lat: parseFloat(entry.spot.lat), lng: parseFloat(entry.spot.lng) });
                map.setZoom(16);
                setTimeout(function() {
                    bouncePin(focusId);
                    openDetail(entry.spot, entry.pal, entry.photo, entry.marker);
                }, 600);
            }
        }, 500);
    }
}

// Wait for Google Maps to load, then init
if (typeof google !== 'undefined' && google.maps) {
    document.addEventListener('DOMContentLoaded', initApp);
} else {
    // Google Maps loads async - poll for it
    var _initInterval = setInterval(function() {
        if (typeof google !== 'undefined' && google.maps && google.maps.marker) {
            clearInterval(_initInterval);
            initApp();
            startAmbassadorPulse();
        }
    }, 100);
}

// ==========================================
// Ambassador Pulse — poll for new posts
// ==========================================
function startAmbassadorPulse() {
    // Track known latest timestamps per spot
    var knownTimestamps = {};
    allSpots.forEach(function(s) {
        if (s.latest_ambassador_post && s.latest_ambassador_post.created_at) {
            knownTimestamps[s.id] = s.latest_ambassador_post.created_at;
        }
    });

    function triggerNewPostEffect(spotId) {
        var entry = markerMap[spotId];
        if (!entry) return;
        var pinEl = entry.pinEl;
        if (!pinEl) return;

        // Add burst rings
        var ring1 = document.createElement('div');
        ring1.className = 'spot-pin-burst-ring';
        pinEl.appendChild(ring1);
        var ring2 = document.createElement('div');
        ring2.className = 'spot-pin-burst-ring2';
        pinEl.appendChild(ring2);

        // Add "NEW" label
        var label = document.createElement('div');
        label.className = 'spot-pin-new-label';
        label.textContent = 'NEW 通信';
        pinEl.appendChild(label);

        // Bounce the whole pin
        pinEl.classList.add('new-post');

        // If pin didn't have ambassador styling, add it now
        var photo = pinEl.querySelector('.spot-pin-photo');
        if (photo) photo.style.borderColor = '#f59e0b';
        var tail = pinEl.querySelector('.spot-pin-tail');
        if (tail) tail.style.background = '#f59e0b';

        // Add glow if not present
        if (!pinEl.querySelector('.spot-pin-amb-glow')) {
            var glow = document.createElement('div');
            glow.className = 'spot-pin-amb-glow';
            pinEl.insertBefore(glow, pinEl.firstChild);
        }

        // Pan map to show the updated pin
        var spot = entry.spot;
        map.panTo({ lat: parseFloat(spot.lat), lng: parseFloat(spot.lng) });

        // Cleanup animations after they finish
        setTimeout(function() {
            pinEl.classList.remove('new-post');
            if (ring1.parentNode) ring1.remove();
            if (ring2.parentNode) ring2.remove();
        }, 2000);
        setTimeout(function() {
            if (label.parentNode) label.remove();
        }, 4500);
    }

    function checkForNewPosts() {
        fetch('/api/ambassador-pulse')
            .then(function(r) { return r.json(); })
            .then(function(data) {
                Object.keys(data).forEach(function(spotId) {
                    var ts = data[spotId];
                    if (!knownTimestamps[spotId] || ts > knownTimestamps[spotId]) {
                        knownTimestamps[spotId] = ts;
                        triggerNewPostEffect(parseInt(spotId));
                    }
                });
            })
            .catch(function() { /* silently ignore network errors */ });
    }

    // Poll every 30 seconds
    setInterval(checkForNewPosts, 30000);
}
</script>
@endsection
