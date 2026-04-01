@extends('layouts.app')

@section('content')
<div class="relative" style="height: calc(100dvh - 58px);">

    {{-- Map（画面全体に固定表示、ヘッダーの下） --}}
    <div id="map" style="position:absolute;top:0;left:0;right:0;bottom:0;"></div>

    {{-- GGA demo: hidden --}}
    {{-- Search Bar
    <div class="absolute top-3 left-0 right-0 z-[1100] px-4">
        <div class="backdrop-blur-xl overflow-visible" style="background: rgba(255,255,255,0.92); border-radius: var(--radius-card); border: 0.5px solid var(--color-border);">
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
    --}}

    {{-- Category Filter (floating inside map) --}}
    <div class="absolute top-3 left-0 right-0 z-[1100] px-4">
        <div class="flex gap-2 overflow-x-auto no-scrollbar py-1">
            <button onclick="filterCategory('すべて')" class="cat-btn active flex-shrink-0 transition-all" style="border-radius:20px;padding:5px 12px;font-size:11px;font-weight:700;box-shadow:0 2px 8px rgba(0,0,0,0.12);border:none;" data-cat="すべて">すべて</button>
            <button onclick="filterCategory('スポーツ少年団')" class="cat-btn flex-shrink-0 transition-all" style="border-radius:20px;padding:5px 12px;font-size:11px;font-weight:700;box-shadow:0 2px 8px rgba(0,0,0,0.12);border:none;" data-cat="スポーツ少年団">⚽ 少年団</button>
            <button onclick="filterCategory('個人教室')" class="cat-btn flex-shrink-0 transition-all" style="border-radius:20px;padding:5px 12px;font-size:11px;font-weight:700;box-shadow:0 2px 8px rgba(0,0,0,0.12);border:none;" data-cat="個人教室">🎵 個人教室</button>
            <button onclick="filterCategory('塾・学習')" class="cat-btn flex-shrink-0 transition-all" style="border-radius:20px;padding:5px 12px;font-size:11px;font-weight:700;box-shadow:0 2px 8px rgba(0,0,0,0.12);border:none;" data-cat="塾・学習">📖 塾・学習</button>
            <button onclick="filterCategory('水泳・体操')" class="cat-btn flex-shrink-0 transition-all" style="border-radius:20px;padding:5px 12px;font-size:11px;font-weight:700;box-shadow:0 2px 8px rgba(0,0,0,0.12);border:none;" data-cat="水泳・体操">🏊 水泳</button>
            <button onclick="filterCategory('武道')" class="cat-btn flex-shrink-0 transition-all" style="border-radius:20px;padding:5px 12px;font-size:11px;font-weight:700;box-shadow:0 2px 8px rgba(0,0,0,0.12);border:none;" data-cat="武道">🥋 武道</button>
            <button onclick="filterCategory('英語・語学')" class="cat-btn flex-shrink-0 transition-all" style="border-radius:20px;padding:5px 12px;font-size:11px;font-weight:700;box-shadow:0 2px 8px rgba(0,0,0,0.12);border:none;" data-cat="英語・語学">🌍 英語</button>
            <button onclick="filterCategory('公園')" class="cat-btn flex-shrink-0 transition-all" style="border-radius:20px;padding:5px 12px;font-size:11px;font-weight:700;box-shadow:0 2px 8px rgba(0,0,0,0.12);border:none;" data-cat="公園">🌳 公園</button>
        </div>
    </div>

    {{-- GGA demo: hidden --}}
    {{-- School Filter
    <div class="absolute top-[50px] left-0 right-0 z-[1001] px-4">
        <div class="backdrop-blur-xl px-3 py-2 flex items-center gap-2" style="background: rgba(255,255,255,0.92); border-radius: var(--radius-input); border: 0.5px solid var(--color-border);">
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
    --}}

    {{-- v2: Toggle List View
    <button id="toggle-list-btn" onclick="toggleListView()"
        class="absolute top-[156px] right-4 z-[1002] w-10 h-10 flex items-center justify-center active:scale-90 transition-all" style="background: var(--color-white); border-radius: var(--radius-input); border: 0.5px solid var(--color-border); color: var(--color-ink-mid);">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
    </button>
    --}}

    {{-- v2: Sidebar Card List（教室リストポップアップ）
    <div id="sidebar-list" class="fixed inset-x-0 bottom-[64px] z-[2500] mx-auto transition-transform duration-500 ease-out" style="max-width:430px;max-height:50vh;transform:translateY(calc(100% + 64px));">
        <div class="overflow-hidden flex flex-col" style="max-height:50vh; background: var(--color-white); border-radius: 24px 24px 0 0; border-top: 0.5px solid var(--color-border);">
            <div class="flex items-center justify-between px-5 pt-4 pb-2">
                <p class="text-sm font-serif" style="color: var(--color-ink);">教室リスト</p>
                <button onclick="toggleListView()" class="text-gray-400 hover:text-gray-600 p-1"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="14" y1="4" x2="4" y2="14"/><line x1="4" y1="4" x2="14" y2="14"/></svg></button>
            </div>
            <div id="sidebar-cards" class="overflow-y-auto overscroll-contain px-4 pb-4 space-y-3"></div>
        </div>
    </div>
    --}}

    {{-- v2: Compare Floating Bar（比較モード）
    <div id="compare-bar" class="fixed bottom-[80px] left-4 right-4 z-[2600] max-w-md mx-auto hidden">
        <div class="text-white px-4 py-3 flex items-center justify-between" style="background: var(--color-ink); border-radius: var(--radius-card); border: 0.5px solid var(--color-border);">
            <div class="flex items-center gap-2">
                <span class="text-sm font-medium" id="compare-count">0</span>
                <span class="text-xs opacity-80">件選択中</span>
            </div>
            <div class="flex gap-2">
                <button onclick="openCompareView()" class="px-4 py-1.5 text-xs font-medium active:scale-95 transition-all" style="background: var(--color-coral); color: white; border-radius: var(--radius-pill);">比較する</button>
                <button onclick="clearCompare()" class="px-3 py-1.5 text-xs font-medium active:scale-95 transition-all" style="background: rgba(255,255,255,0.15); border-radius: var(--radius-pill);">クリア</button>
            </div>
        </div>
    </div>

    {{-- Compare Modal --}}
    <div id="compare-overlay" class="fixed inset-0 z-[7000] hidden">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeCompareView()"></div>
        <div class="absolute inset-x-0 bottom-0 max-w-md mx-auto">
            <div class="max-h-[90vh] overflow-hidden flex flex-col" style="background: var(--color-white); border-radius: 28px 28px 0 0; border-top: 0.5px solid var(--color-border);">
                <div class="flex items-center justify-between px-5 pt-5 pb-3">
                    <h3 class="text-base font-serif" style="color: var(--color-ink);">📊 教室を比較</h3>
                    <button onclick="closeCompareView()" class="text-gray-400 hover:text-gray-600"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="14" y1="5" x2="5" y2="14"/><line x1="5" y1="5" x2="14" y2="14"/></svg></button>
                </div>
                <div id="compare-body" class="flex-1 overflow-y-auto overscroll-contain px-5 pb-8"></div>
            </div>
        </div>
    </div>
    --}}

    {{-- Detail Modal --}}
    <div id="detail-overlay" class="fixed inset-0 z-[5000]" style="pointer-events:none;visibility:hidden;">
        <div id="detail-backdrop" class="absolute inset-0 bg-black/0 transition-all duration-400" style="pointer-events:none;" onclick="closeDetail()"></div>
        <div id="detail-card" class="absolute inset-x-0 bottom-0 z-10" style="max-width:430px;margin:0 auto;transform:translateY(100%);transition:transform 0.45s cubic-bezier(0.32,0.72,0,1);pointer-events:none;">
            <div id="detail-inner" class="flex flex-col" style="height:90vh;max-height:90vh;background:var(--color-white);border-radius:28px 28px 0 0;border-top:0.5px solid var(--color-border);">
                {{-- Drag handle --}}
                <div id="detail-drag-handle" class="flex justify-center pt-3 pb-1 cursor-grab active:cursor-grabbing flex-shrink-0">
                    <div class="w-10 h-1 bg-gray-300 rounded-full"></div>
                </div>
                <div id="detail-hero" class="relative w-full overflow-hidden flex-shrink-0" style="min-height:180px;">
                    <div id="hero-bg" class="absolute inset-0"></div>
                    <button id="detail-close-btn" class="absolute top-3 right-3 w-10 h-10 bg-white/80 backdrop-blur-sm rounded-full flex items-center justify-center hover:bg-white transition-all active:scale-90 z-10" style="color: var(--color-ink);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="14" y1="5" x2="5" y2="14"/><line x1="5" y1="5" x2="14" y2="14"/></svg>
                    </button>
                    <div class="absolute bottom-0 left-0 right-0 p-5 z-10">
                        <div id="hero-badges" class="flex flex-wrap gap-1.5 mb-2"></div>
                        <h2 id="hero-title" class="text-2xl font-black leading-tight"></h2>
                    </div>
                </div>
                <div id="detail-body" class="flex-1 overflow-y-auto overscroll-contain px-5 pt-5" style="padding-bottom:calc(env(safe-area-inset-bottom, 0px) + 16px);-webkit-overflow-scrolling:touch;"></div>
            </div>
        </div>
    </div>

    {{-- Review Modal — MVP: 2項目タップ式口コミ --}}
    <div id="review-overlay" class="fixed inset-0 z-[6000] hidden">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeReviewForm()"></div>
        <div class="absolute inset-x-0 bottom-0 max-w-md mx-auto">
            <div style="height:90vh;max-height:90vh;overflow-y:auto;overscroll-behavior:contain;-webkit-overflow-scrolling:touch;background:var(--color-white);border-radius:28px 28px 0 0;border-top:0.5px solid var(--color-border);">
                <div style="padding:24px 24px calc(env(safe-area-inset-bottom, 0px) + 16px);">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-serif" style="color: var(--color-ink);">口コミを投稿</h3>
                        <button onclick="closeReviewForm()" class="text-gray-400 hover:text-gray-600"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="14" y1="5" x2="5" y2="14"/><line x1="5" y1="5" x2="14" y2="14"/></svg></button>
                    </div>
                    <form id="review-form" method="POST" class="space-y-6">
                        @csrf
                        <input type="hidden" name="spot_id" id="review-spot-id">

                        {{-- 項目1: 雰囲気 --}}
                        <div>
                            <label class="text-sm font-bold mb-3 block" style="color: var(--color-ink);">雰囲気は？ <span class="text-red-500 text-xs">*必須</span></label>
                            <div class="flex flex-wrap gap-3">
                                @foreach(['ガチ勢向き','バランス','のびのび'] as $vibe)
                                <label>
                                    <input type="radio" name="vibe_tag" value="{{ $vibe }}" class="hidden peer">
                                    <div class="peer-checked:bg-[#E8704A] peer-checked:text-white peer-checked:border-[#E8704A] bg-white border-2 border-gray-200 text-gray-500 rounded-[20px] py-3 px-5 text-sm font-bold cursor-pointer transition-all active:scale-95">{{ $vibe }}</div>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- 項目2: 親の関わり度 --}}
                        <div>
                            <label class="text-sm font-bold mb-3 block" style="color: var(--color-ink);">親の関わり度は？ <span class="text-red-500 text-xs">*必須</span></label>
                            <div class="flex flex-wrap gap-3">
                                @foreach(['ほぼなし','月数回','毎週ある'] as $pi)
                                <label>
                                    <input type="radio" name="parent_involvement" value="{{ $pi }}" class="hidden peer">
                                    <div class="peer-checked:bg-[#E8704A] peer-checked:text-white peer-checked:border-[#E8704A] bg-white border-2 border-gray-200 text-gray-500 rounded-[20px] py-3 px-5 text-sm font-bold cursor-pointer transition-all active:scale-95">{{ $pi }}</div>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- 項目3: 一言コメント --}}
                        <div>
                            <label class="text-sm font-bold mb-3 block" style="color: var(--color-ink);">一言コメント <span class="text-gray-400 text-xs font-normal">（任意・100文字まで）</span></label>
                            <textarea name="body" rows="2" maxlength="100" placeholder="先生がとても優しくて子どもが毎回楽しそうに通っています"
                                class="w-full bg-gray-50 rounded-2xl px-4 py-3.5 text-sm outline-none border-2 border-gray-200 transition-all resize-none focus:border-[#E8704A]" style="color: var(--color-ink);"></textarea>
                        </div>

                        <button type="submit"
                            class="w-full text-white font-bold py-4 active:scale-[0.98] transition-all text-base" style="background: #E8704A; border-radius: 20px;">
                            口コミを送信
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    #map { position: absolute; top: 0; left: 0; right: 0; bottom: 0; }
    .gm-style iframe + div { border: none !important; }

    .spot-pin {
        display: flex; flex-direction: column; align-items: center;
        cursor: pointer;
        transition: transform 0.3s cubic-bezier(0.34,1.56,0.64,1), opacity 0.4s;
    }
    .spot-pin:hover, .spot-pin.active {
        transform: scale(1.15) translateY(-4px);
        z-index: 9999 !important;
    }
    .spot-pin.faded { opacity: 0.15; pointer-events: none; }
    .spot-pin-circle {
        width: 48px; height: 48px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 24px; border: 2px solid white;
    }
    .spot-pin-label {
        margin-top: 2px; padding: 2px 7px; border-radius: 6px;
        font-size: 9px; font-weight: 800; color: #1e293b;
        background: white;
        text-align: center; white-space: nowrap;
        max-width: 90px; overflow: hidden; text-overflow: ellipsis;
    }
    @keyframes pinBounce {
        0%,100% { transform: translateY(0); }
        30% { transform: translateY(-16px); }
        60% { transform: translateY(-6px); }
    }
    .spot-pin.bouncing { animation: pinBounce 0.6s ease; }

    .cat-btn { background: #fff; color: var(--color-ink-mid); cursor: pointer; }
    .cat-btn.active { background: #1a1a1a; color: #fff; }

    .dist-btn.active { background: var(--color-coral); color: white; }

    #sidebar-list.open { transform: translateY(0) !important; }

    .search-item { display: flex; align-items: center; gap: 12px; padding: 12px 16px; cursor: pointer; transition: background 0.15s; }
    .search-item:hover { background: var(--color-coral-light); }
    .search-item:not(:last-child) { border-bottom: 0.5px solid var(--color-border); }
    .search-thumb { width: 40px; height: 40px; border-radius: 12px; overflow: hidden; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }

    .tag-pill { display: inline-block; padding: 4px 12px; border-radius: var(--radius-pill); font-size: 11px; font-weight: 500; background: var(--color-coral-light); color: var(--color-coral); }
    .info-card { background: var(--color-cream); border-radius: var(--radius-card); padding: 14px; text-align: center; }
    .lesson-badge { display: inline-flex; align-items: center; gap: 4px; padding: 6px 12px; border-radius: 12px; font-size: 12px; font-weight: 700; }

    .sidebar-card {
        display: flex; gap: 12px; padding: 12px; background: var(--color-white);
        border: 0.5px solid var(--color-border); border-radius: var(--radius-card);
        box-shadow: none;
        cursor: pointer; transition: transform 0.2s;
    }
    .sidebar-card:hover { transform: scale(1.02); }
    .sidebar-card:active { transform: scale(0.98); }
    .sidebar-card.compare-selected { border-color: var(--color-coral); box-shadow: 0 0 0 1px var(--color-coral); }
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
    }).then(function(r){
        if (r.status === 401 || r.redirected) {
            alert('お気に入り機能はLINEログイン後にご利用いただけます');
            return null;
        }
        return r.json();
    }).then(function(data){
        if (!data) return;
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
        center: { lat: 35.6420, lng: 139.6510 },
        zoom: 14,
        disableDefaultUI: true,
        zoomControl: true,
        zoomControlOptions: { position: google.maps.ControlPosition.RIGHT_CENTER },
        styles: [
            { featureType: 'poi', stylers: [{ visibility: 'off' }] },
            { featureType: 'transit', stylers: [{ visibility: 'off' }] }
        ],
        mapId: 'MIKKE_MAP'
    });

    var catPalette = {
        'スポーツ少年団': { emoji:'⚽', bg:'#FFF0E0', fg:'#E8704A' },
        '個人教室':      { emoji:'🎵', bg:'#EEF0FF', fg:'#5B5BD6' },
        '塾・学習':      { emoji:'📖', bg:'#FFF8E0', fg:'#C9973A' },
        '水泳・体操':    { emoji:'🏊', bg:'#E0F4FF', fg:'#3A9CC9' },
        'バレエ・ダンス': { emoji:'🩰', bg:'#FFE0F4', fg:'#C93A8A' },
        '武道':          { emoji:'🥋', bg:'#F0F0F0', fg:'#444444' },
        '英語・語学':    { emoji:'🌍', bg:'#E0FFE8', fg:'#3AC95B' },
        '公園・遊び場':  { emoji:'🌳', bg:'#E8FFE0', fg:'#5BC93A' },
        '施設':          { emoji:'🌳', bg:'#E8FFE0', fg:'#5BC93A' }
    };
    var defPal = { emoji:'✨', bg:'#F5F0FF', fg:'#7B5BD6' };
    function getPal(cat) { if(!cat) return defPal; for(var k in catPalette){ if(cat.indexOf(k)!==-1) return catPalette[k]; } return defPal; }

    var allSpots = @json($spots);
    var mySchool = @json($mySchool);
    var allSchools = @json($schools ?? []);
    var gMarkers = []; // google.maps.marker.AdvancedMarkerElement or overlay
    var markerMap = {};
    var activeCardEl = null;
    var clusterer = null;

    // 徒歩分数計算（80m/分）
    function walkingMinutes(lat1, lng1, lat2, lng2) {
        var distKm = haversine(lat1, lng1, lat2, lng2);
        return Math.round(distKm * 1000 / 80);
    }

    function findNearestSchool(spotLat, spotLng) {
        var nearest = null, nearestDist = Infinity;
        allSchools.forEach(function(s) {
            if (!s.lat || !s.lng) return;
            var d = haversine(parseFloat(s.lat), parseFloat(s.lng), spotLat, spotLng);
            if (d < nearestDist) { nearestDist = d; nearest = s; }
        });
        return nearest;
    }

    function getWalkingBadge(spot) {
        var school = mySchool;
        if ((!school || !school.lat) && allSchools.length > 0) {
            school = findNearestSchool(parseFloat(spot.lat), parseFloat(spot.lng));
        }
        if (!school || !school.lat || !school.lng) return '';
        var mins = walkingMinutes(school.lat, school.lng, parseFloat(spot.lat), parseFloat(spot.lng));
        var color = mins <= 10 ? '#16a34a' : mins <= 20 ? '#d97706' : '#dc2626';
        var bg = mins <= 10 ? '#dcfce7' : mins <= 20 ? '#fef3c7' : '#fee2e2';
        var label = (mySchool && mySchool.id === school.id) ? mySchool.name : school.name + '(最寄り)';
        return '<span style="display:inline-flex;align-items:center;gap:3px;padding:3px 8px;border-radius:8px;font-size:10px;font-weight:800;background:'+bg+';color:'+color+';">🏫 '+label+'から徒歩'+mins+'分</span>';
    }

    // ==========================================
    // Schools
    // ==========================================
    var schools = allSchools.length > 0 ? allSchools : [
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
    if (schoolSelect) {
        schools.forEach(function(s) {
            var opt = document.createElement('option');
            opt.value = s.name; opt.textContent = s.name;
            schoolSelect.appendChild(opt);
        });
    }

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
        if (!schoolSelect) return;
        var name = schoolSelect.value;
        if (!name) { clearSchoolFilter(); return; }
        selectedSchool = schools.find(function(s){ return s.name === name; });
        var clr = document.getElementById('school-clear'); if(clr) clr.classList.remove('hidden');
        drawSchoolCircle();
        reapplyFilters();
    };

    window.setDistance = function(km) {
        filterDistanceKm = km;
        document.querySelectorAll('.dist-btn').forEach(function(b){ b.classList.remove('active'); });
        var btn = document.getElementById('dist-'+km+'km'); if(btn) btn.classList.add('active');
        if (selectedSchool) { drawSchoolCircle(); reapplyFilters(); }
    };

    window.clearSchoolFilter = function() {
        selectedSchool = null;
        if(schoolSelect) schoolSelect.value = '';
        var clr = document.getElementById('school-clear'); if(clr) clr.classList.add('hidden');
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
    function buildPinHtml(spot, pal) {
        return '<div class="spot-pin" data-id="'+spot.id+'">'+
            '<div class="spot-pin-circle" style="background:'+pal.bg+';border-color:white;">'+pal.emoji+'</div>'+
            '<div class="spot-pin-label" title="'+spot.title+'">'+spot.title+'</div>'+
            '</div>';
    }

    function createPinElement(spot, pal, faded) {
        var div = document.createElement('div');
        div.innerHTML = buildPinHtml(spot, pal);
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

            var pal = getPal(spot.category);
            var inRange = isSpotInRange(spot);
            var pinEl = createPinElement(spot, pal, !inRange);

            var marker = new google.maps.marker.AdvancedMarkerElement({
                position: { lat: lat, lng: lng },
                content: pinEl,
                zIndex: inRange ? 100 : 1
            });

            pinEl.addEventListener('click', function() {
                openDetail(spot, pal, marker);
            });

            gMarkers.push(marker);
            markerMap[spot.id] = { marker: marker, spot: spot, pal: pal, inRange: inRange, pinEl: pinEl };

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

        /* v2: buildSidebarCards(filter); */
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

            var pal = getPal(spot.category);
            var thumbHtml = '<div class="sidebar-card-thumb" style="background:'+pal.bg+';"><span style="font-size:28px;">'+pal.emoji+'</span></div>';

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
            openDetail(entry.spot, entry.pal, entry.marker);
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

    function openDetail(spot, pal, marker) {
        if(activeCardEl) activeCardEl.classList.remove('active');
        var entry = markerMap[spot.id];
        if(entry && entry.pinEl) { entry.pinEl.classList.add('active'); activeCardEl = entry.pinEl; }

        // Color block + emoji header (no photos)
        heroBg.innerHTML='<div style="width:100%;height:100%;background:'+pal.bg+';display:flex;align-items:center;justify-content:center;"><span style="font-size:48px;">'+pal.emoji+'</span></div>';
        heroBg.parentElement.style.minHeight='180px';

        var bH = '<span style="display:inline-flex;align-items:center;gap:4px;padding:4px 10px;border-radius:20px;font-size:12px;font-weight:800;background:'+pal.bg+';color:'+pal.fg+';border:1px solid '+pal.fg+'33;">'+pal.emoji+' '+(spot.category||'')+'</span>';
        heroBadges.innerHTML=bH;
        heroTitle.textContent=spot.title;
        heroTitle.style.color=pal.fg;

        var body = '';

        // 月謝
        body += '<div style="background:#f9fafb;border-radius:16px;padding:14px;margin-bottom:16px;display:flex;align-items:center;gap:10px;">';
        body += '<span style="font-size:20px;">💰</span>';
        body += '<div><p style="font-size:11px;font-weight:700;color:#9ca3af;">月謝</p>';
        body += '<p style="font-size:14px;font-weight:800;color:#374151;">'+(spot.monthly_fee_range || '¥ ——　知っている方は教えてください')+'</p>';
        body += '</div></div>';

        // 口コミ一覧
        var reviews = spot.reviews||[];
        var vibeLabels = {'ガチ勢向き':'🔥 ガチ勢向き','バランス':'⚖️ バランス','のびのび':'🌱 のびのび','ガチ勢':'🔥 ガチ勢向き','エンジョイ勢':'⚖️ バランス','のびのび系':'🌱 のびのび'};
        var parentLabels = {'ほぼなし':'😊 ほぼなし','月数回':'🤝 月数回','毎週ある':'💪 毎週ある'};

        if(reviews.length) {
            body += '<div style="margin-bottom:16px;"><p style="font-size:14px;font-weight:800;color:#374151;margin-bottom:10px;">口コミ（'+reviews.length+'件）</p>';
            reviews.forEach(function(r) {
                body += '<div style="background:#f9fafb;border-radius:16px;padding:14px;margin-bottom:8px;">';
                var rtags = [];
                if(r.vibe_tag) {
                    var vl = vibeLabels[r.vibe_tag] || r.vibe_tag;
                    rtags.push('<span style="display:inline-block;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:700;background:#E8704A;color:white;">'+vl+'</span>');
                }
                if(r.parent_involvement) {
                    var pl = parentLabels[r.parent_involvement] || r.parent_involvement;
                    rtags.push('<span style="display:inline-block;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:700;background:#f3f4f6;color:#374151;">'+pl+'</span>');
                }
                if(rtags.length) body += '<div style="display:flex;flex-wrap:wrap;gap:6px;margin-bottom:8px;">'+rtags.join('')+'</div>';
                if(r.body) body += '<p style="font-size:14px;color:#4b5563;line-height:1.7;">「'+r.body+'」</p>';
                body += '</div>';
            });
            body += '</div>';
        } else {
            body += '<div style="background:#f9fafb;border-radius:16px;padding:20px;margin-bottom:16px;text-align:center;">';
            body += '<p style="font-size:14px;color:#9ca3af;">まだ口コミがありません</p>';
            body += '</div>';
        }

        // 口コミを投稿ボタン（目立つコーラル）
        body += '<button onclick="openReviewForm('+spot.id+')" style="display:flex;align-items:center;justify-content:center;gap:8px;width:100%;padding:16px;border-radius:20px;background:#E8704A;color:white;font-weight:800;font-size:15px;border:none;cursor:pointer;margin-bottom:12px;">📝 口コミを投稿</button>';

        // 外部リンク
        if(spot.link_url) {
            body += '<a href="'+spot.link_url+'" target="_blank" rel="noopener" style="display:flex;align-items:center;gap:8px;padding:14px 16px;border-radius:16px;background:#f0f9ff;color:#2563eb;font-size:14px;font-weight:700;text-decoration:none;margin-bottom:12px;border:1px solid #bfdbfe;">🔗 外部サイトを見る</a>';
        }

        body += '<button onclick="closeDetail()" style="display:flex;align-items:center;justify-content:center;gap:6px;width:100%;padding:14px;border-radius:16px;background:#111827;color:white;font-weight:700;font-size:14px;border:none;cursor:pointer;">閉じる</button>';

        detailBody.innerHTML = body;

        // Show & enable pointer events
        overlay.style.visibility = 'visible';
        overlay.style.pointerEvents = 'auto';
        backdrop.style.pointerEvents = 'auto';
        card.style.pointerEvents = 'auto';

        // Animate in
        backdrop.style.background = 'rgba(0,0,0,0.45)';
        requestAnimationFrame(function(){
            card.style.transform = 'translateY(0)';
        });
        detailOpen = true;

        // Hide FAB while detail is open
        var fab = document.getElementById('fab-add-spot');
        if (fab) fab.style.opacity = '0';
        if (fab) fab.style.pointerEvents = 'none';

        // Hide all markers while detail is open
        gMarkers.forEach(function(m){ if(m.content) m.content.style.opacity='0'; });

        map.panTo({ lat: parseFloat(spot.lat), lng: parseFloat(spot.lng) });

        // Fix map rendering after bottom sheet animation
        setTimeout(function() {
            if (window.map || map) {
                google.maps.event.trigger(map, 'resize');
            }
        }, 150);
    }

    window.closeDetail = function() {
        if (!detailOpen) return;
        detailOpen = false;

        card.style.transform = 'translateY(100%)';
        backdrop.style.background = 'rgba(0,0,0,0)';

        if(activeCardEl){ activeCardEl.classList.remove('active'); activeCardEl = null; }

        // Show FAB again
        var fab = document.getElementById('fab-add-spot');
        if (fab) { fab.style.opacity = '1'; fab.style.pointerEvents = ''; }

        // Show all markers again
        gMarkers.forEach(function(m){ if(m.content) m.content.style.opacity='1'; });

        setTimeout(function(){
            overlay.style.pointerEvents = 'none';
            overlay.style.visibility = 'hidden';
            backdrop.style.pointerEvents = 'none';
            card.style.pointerEvents = 'none';
        }, 450);
    };
    window.shareSpot = function() { var t=heroTitle.textContent; if(navigator.share) navigator.share({title:t,text:t+' - みっけ（MIKKE）',url:location.href}); };

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
    if (searchInput) {
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
        document.addEventListener('click', function(e){ if(!e.target.closest('#map-search')&&!e.target.closest('#search-dropdown')) dropdown.classList.add('hidden'); });
    }
    window.jumpToSpot = function(id) {
        var entry=markerMap[id]; if(!entry) return;
        if(dropdown) dropdown.classList.add('hidden');
        if(searchInput) searchInput.value='';
        if(clearBtn) clearBtn.classList.add('hidden');
        map.panTo({ lat: parseFloat(entry.spot.lat), lng: parseFloat(entry.spot.lng) });
        map.setZoom(17);
        setTimeout(function(){ bouncePin(id); openDetail(entry.spot,entry.pal,entry.marker); },800);
    };
    window.clearSearch = function(){ if(searchInput) searchInput.value=''; if(clearBtn) clearBtn.classList.add('hidden'); if(dropdown) dropdown.classList.add('hidden'); renderSpots('すべて'); };

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
                    openDetail(entry.spot, entry.pal, entry.marker);
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
            /* v2: startAmbassadorPulse(); */
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
