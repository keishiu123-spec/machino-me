@extends('layouts.app')

@section('content')
<div class="min-h-screen pb-20" style="background: var(--color-cream);">
    {{-- Header --}}
    <div class="flex items-center justify-between px-5 py-3 sticky top-[65px] backdrop-blur-xl z-[2000]" style="background: rgba(250,247,242,0.95); border-bottom: 0.5px solid var(--color-border);">
        <a href="{{ route('spots.index') }}" class="hover:opacity-70 transition-colors" style="color: var(--color-ink-soft);">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </a>
        <h1 class="text-base font-serif" style="color: var(--color-ink);">スポットを登録</h1>
        <div class="w-6"></div>
    </div>

    {{-- Lead copy --}}
    <div class="px-5 pt-4 pb-2">
        <p class="text-[11px] text-center font-medium" style="color: var(--color-ink-soft);">ネットに載っていない習い事を地図に追加しよう（30秒で完了）</p>
    </div>

    <form id="spot-form" action="{{ route('spots.store') }}" method="POST" enctype="multipart/form-data" class="px-5 pb-5 space-y-5">
        @csrf
        <input type="hidden" name="google_place_id" id="google_place_id" value="">

        {{-- ===== 1. 場所を探す（必須） ===== --}}
        <div class="fade-up">
            <label class="text-xs font-bold mb-2 block" style="color: var(--color-ink);">
                <span class="inline-flex items-center justify-center w-5 h-5 rounded-full text-[10px] text-white mr-1" style="background: var(--color-coral);">1</span>
                場所を探す
            </label>

            {{-- Search Mode Toggle --}}
            <div class="flex bg-gray-100 rounded-xl p-1 mb-3">
                <button type="button" id="mode-google" onclick="setSearchMode('google')"
                    class="search-mode-btn active flex-1 py-2 rounded-lg text-xs font-bold transition-all flex items-center justify-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    Googleマップから
                </button>
                <button type="button" id="mode-manual" onclick="setSearchMode('manual')"
                    class="search-mode-btn flex-1 py-2 rounded-lg text-xs font-bold transition-all flex items-center justify-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 1 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    住所を入力
                </button>
            </div>

            {{-- Google Places Search --}}
            <div id="google-search-panel">
                <div class="flex gap-2">
                    <div class="flex-1 relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        <input type="text" id="places-input"
                            class="w-full rounded-xl pl-9 pr-3 py-3 text-sm outline-none transition-all" style="background: var(--color-white); border: 0.5px solid var(--color-border); color: var(--color-ink);"
                            placeholder="教室名・施設名で検索...">
                    </div>
                    <button type="button" id="places-search-btn"
                        class="text-white font-medium text-sm px-4 active:scale-95 transition-all whitespace-nowrap" style="background: var(--color-coral); border-radius: var(--radius-input);">
                        検索
                    </button>
                </div>
                <div id="places-results" class="mt-1.5 hidden bg-white border border-gray-100 rounded-xl shadow-lg overflow-hidden relative z-50 max-h-[240px] overflow-y-auto"></div>
            </div>

            {{-- Manual Address Search --}}
            <div id="manual-search-panel" class="hidden">
                <div class="flex gap-2">
                    <div class="flex-1 relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 1 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        <input type="text" id="address-input"
                            class="w-full rounded-xl pl-9 pr-3 py-3 text-sm outline-none transition-all" style="background: var(--color-white); border: 0.5px solid var(--color-border); color: var(--color-ink);"
                            placeholder="住所を入力（例：世田谷区弦巻1-42-15）">
                    </div>
                    <button type="button" id="address-search-btn"
                        class="text-white font-medium text-sm px-4 active:scale-95 transition-all whitespace-nowrap" style="background: var(--color-ink); border-radius: var(--radius-input);">
                        検索
                    </button>
                </div>
                <div id="address-results" class="mt-1.5 hidden bg-white border border-gray-100 rounded-xl shadow-lg overflow-hidden relative z-50"></div>
                <p class="mt-1.5 text-[10px] text-gray-400 font-medium">Googleマップに載っていない場所はこちらから</p>
            </div>

            {{-- Mini Map --}}
            <div class="mt-3">
                <div id="mini-map" class="w-full rounded-2xl overflow-hidden border border-gray-100 shadow-sm" style="height:180px;"></div>
                <div id="current-address" class="mt-2 text-center">
                    <p class="text-[11px] text-gray-400 font-medium">ピンをドラッグ or タップで位置を調整</p>
                </div>
                <div id="place-id-badge" class="hidden mt-1 text-center">
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-blue-50 text-[10px] font-bold text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        Google Maps 認証済み
                    </span>
                </div>
                <input type="hidden" name="lat" id="lat" value="35.6465">
                <input type="hidden" name="lng" id="lng" value="139.6533">
            </div>
        </div>

        {{-- ===== 2. スポット名（必須） ===== --}}
        <div class="fade-up" style="animation-delay:0.05s">
            <label class="text-xs font-bold mb-2 block" style="color: var(--color-ink);">
                <span class="inline-flex items-center justify-center w-5 h-5 rounded-full text-[10px] text-white mr-1" style="background: var(--color-coral);">2</span>
                スポット名
            </label>
            <input type="text" name="title" id="title-input"
                class="w-full rounded-xl px-4 py-3 text-sm outline-none transition-all"
                style="background: var(--color-white); border: 0.5px solid var(--color-border); color: var(--color-ink);"
                placeholder="例：弦巻サッカークラブ、ピアノ教室 山田先生" required>
        </div>

        {{-- ===== 3. カテゴリ（必須） ===== --}}
        <div class="fade-up" style="animation-delay:0.1s">
            <label class="text-xs font-bold mb-2 block" style="color: var(--color-ink);">
                <span class="inline-flex items-center justify-center w-5 h-5 rounded-full text-[10px] text-white mr-1" style="background: var(--color-coral);">3</span>
                カテゴリ
            </label>
            <div class="grid grid-cols-3 gap-3">
                @php
                    $cats = [
                        ['val'=>'スポーツ少年団','emoji'=>'⚽','label'=>'スポーツ少年団','bg'=>'#FFF0E0','fg'=>'#E8704A'],
                        ['val'=>'個人教室','emoji'=>'🎵','label'=>'個人教室（音楽）','bg'=>'#EEF0FF','fg'=>'#5B5BD6'],
                        ['val'=>'塾・学習','emoji'=>'📖','label'=>'塾・学習','bg'=>'#FFF8E0','fg'=>'#C9973A'],
                        ['val'=>'水泳・体操','emoji'=>'🏊','label'=>'水泳・体操','bg'=>'#E0F4FF','fg'=>'#3A9CC9'],
                        ['val'=>'バレエ・ダンス','emoji'=>'🩰','label'=>'バレエ・ダンス','bg'=>'#FFE0F4','fg'=>'#C93A8A'],
                        ['val'=>'武道','emoji'=>'🥋','label'=>'武道・空手','bg'=>'#F0F0F0','fg'=>'#444444'],
                        ['val'=>'英語・語学','emoji'=>'🌍','label'=>'英語・語学','bg'=>'#E0FFE8','fg'=>'#3AC95B'],
                        ['val'=>'公園・遊び場','emoji'=>'🌳','label'=>'公園・遊び場','bg'=>'#E8FFE0','fg'=>'#5BC93A'],
                        ['val'=>'その他','emoji'=>'✨','label'=>'その他','bg'=>'#F5F0FF','fg'=>'#7B5BD6'],
                    ];
                @endphp
                @foreach($cats as $i => $cat)
                <label>
                    <input type="radio" name="category" value="{{ $cat['val'] }}" class="hidden peer" {{ $i===0?'checked':'' }}>
                    <div class="peer-checked:ring-2 peer-checked:ring-[#E8704A] aspect-square flex flex-col items-center justify-center rounded-2xl cursor-pointer transition-all active:scale-95" style="background: {{ $cat['bg'] }};">
                        <span class="text-2xl block mb-1">{{ $cat['emoji'] }}</span>
                        <span class="text-[10px] font-bold block" style="color: {{ $cat['fg'] }};">{{ $cat['label'] }}</span>
                    </div>
                </label>
                @endforeach
            </div>
        </div>

        {{-- ===== 4. 雰囲気 & 親の関わり（任意・ワンタップ） ===== --}}
        <div class="fade-up" style="animation-delay:0.15s">
            <label class="text-xs font-bold mb-3 block" style="color: var(--color-ink);">
                <span class="inline-flex items-center justify-center w-5 h-5 rounded-full text-[10px] text-white mr-1" style="background: var(--color-coral);">4</span>
                知ってたら教えて！<span class="text-[10px] font-normal ml-1" style="color: var(--color-ink-soft);">（任意）</span>
            </label>

            {{-- 雰囲気 --}}
            <div class="mb-4">
                <p class="text-[11px] font-bold mb-2 flex items-center gap-1.5" style="color: var(--color-ink-mid);">
                    <span class="text-base">✨</span> 雰囲気は？
                </p>
                <div class="grid grid-cols-3 gap-2">
                    <label>
                        <input type="radio" name="policy_type" value="ガチ勢" class="hidden peer">
                        <div class="peer-checked:border-[#dc2626] peer-checked:bg-[#fee2e2] bg-white border rounded-2xl py-3 text-center cursor-pointer transition-all active:scale-95" style="border-color: var(--color-border);">
                            <span class="text-2xl block mb-1">🔥</span>
                            <span class="text-[11px] font-bold block" style="color: var(--color-ink);">ガチ勢</span>
                            <span class="text-[9px] block mt-0.5" style="color: var(--color-ink-soft);">本気で上を目指す</span>
                        </div>
                    </label>
                    <label>
                        <input type="radio" name="policy_type" value="バランス" class="hidden peer">
                        <div class="peer-checked:border-[#2563eb] peer-checked:bg-[#dbeafe] bg-white border rounded-2xl py-3 text-center cursor-pointer transition-all active:scale-95" style="border-color: var(--color-border);">
                            <span class="text-2xl block mb-1">⚖️</span>
                            <span class="text-[11px] font-bold block" style="color: var(--color-ink);">バランス</span>
                            <span class="text-[9px] block mt-0.5" style="color: var(--color-ink-soft);">楽しさも成長も</span>
                        </div>
                    </label>
                    <label>
                        <input type="radio" name="policy_type" value="のびのび" class="hidden peer">
                        <div class="peer-checked:border-[#16a34a] peer-checked:bg-[#dcfce7] bg-white border rounded-2xl py-3 text-center cursor-pointer transition-all active:scale-95" style="border-color: var(--color-border);">
                            <span class="text-2xl block mb-1">🌱</span>
                            <span class="text-[11px] font-bold block" style="color: var(--color-ink);">のびのび</span>
                            <span class="text-[9px] block mt-0.5" style="color: var(--color-ink-soft);">楽しさ最優先</span>
                        </div>
                    </label>
                </div>
            </div>

            {{-- 親の関わり度 --}}
            <div>
                <p class="text-[11px] font-bold mb-2 flex items-center gap-1.5" style="color: var(--color-ink-mid);">
                    <span class="text-base">👋</span> 親の関わりは？
                </p>
                <div class="grid grid-cols-3 gap-2">
                    <label>
                        <input type="radio" name="parent_role" value="ほぼなし" class="hidden peer">
                        <div class="peer-checked:border-[#16a34a] peer-checked:bg-[#dcfce7] bg-white border rounded-2xl py-3 text-center cursor-pointer transition-all active:scale-95" style="border-color: var(--color-border);">
                            <span class="text-2xl block mb-1">😊</span>
                            <span class="text-[11px] font-bold block" style="color: var(--color-ink);">ほぼなし</span>
                            <span class="text-[9px] block mt-0.5" style="color: var(--color-ink-soft);">送迎だけでOK</span>
                        </div>
                    </label>
                    <label>
                        <input type="radio" name="parent_role" value="月数回" class="hidden peer">
                        <div class="peer-checked:border-[#d97706] peer-checked:bg-[#fef3c7] bg-white border rounded-2xl py-3 text-center cursor-pointer transition-all active:scale-95" style="border-color: var(--color-border);">
                            <span class="text-2xl block mb-1">🤝</span>
                            <span class="text-[11px] font-bold block" style="color: var(--color-ink);">月数回</span>
                            <span class="text-[9px] block mt-0.5" style="color: var(--color-ink-soft);">当番や手伝い少し</span>
                        </div>
                    </label>
                    <label>
                        <input type="radio" name="parent_role" value="毎週ある" class="hidden peer">
                        <div class="peer-checked:border-[#dc2626] peer-checked:bg-[#fee2e2] bg-white border rounded-2xl py-3 text-center cursor-pointer transition-all active:scale-95" style="border-color: var(--color-border);">
                            <span class="text-2xl block mb-1">💪</span>
                            <span class="text-[11px] font-bold block" style="color: var(--color-ink);">毎週ある</span>
                            <span class="text-[9px] block mt-0.5" style="color: var(--color-ink-soft);">当番・配車など</span>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        {{-- ===== 任意項目（折りたたみ） ===== --}}
        <details class="fade-up" style="animation-delay:0.2s">
            <summary class="text-xs font-medium cursor-pointer py-2 flex items-center gap-1.5" style="color: var(--color-ink-soft);">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                追加情報を入力する（任意）
            </summary>
            <div class="space-y-4 pt-2">
                {{-- Photo --}}
                <div>
                    <label class="text-xs font-medium mb-1.5 block" style="color: var(--color-ink-mid);">写真</label>
                    <div class="relative w-full border border-dashed rounded-xl overflow-hidden" style="background: var(--color-white); border-color: var(--color-border);">
                        <img id="image-preview" class="hidden w-full h-full object-cover absolute inset-0">
                        <div id="placeholder" class="text-center py-5 relative z-10">
                            <svg class="mx-auto text-gray-300 mb-1" xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                            <p class="text-[11px] text-gray-400">タップして写真を選択</p>
                        </div>
                        <input type="file" name="image" id="image-input" class="absolute inset-0 opacity-0 cursor-pointer z-20" accept="image/*">
                    </div>
                </div>

                {{-- Link --}}
                <div>
                    <label class="text-xs font-medium mb-1.5 block" style="color: var(--color-ink-mid);">外部リンク</label>
                    <input type="url" name="link_url" placeholder="https://..."
                        class="w-full rounded-xl px-4 py-3 text-sm outline-none transition-all" style="background: var(--color-white); border: 0.5px solid var(--color-border); color: var(--color-ink);">
                </div>

                {{-- Note --}}
                <div>
                    <label class="text-xs font-medium mb-1.5 block" style="color: var(--color-ink-mid);">メモ・補足</label>
                    <textarea name="note" rows="2"
                        class="w-full rounded-xl px-4 py-3 text-sm outline-none transition-all resize-none"
                        style="background: var(--color-white); border: 0.5px solid var(--color-border); color: var(--color-ink);"
                        placeholder="曜日・時間・先生の名前など、知っていることがあれば"></textarea>
                </div>
            </div>
        </details>

        {{-- Duplicate Warning --}}
        <div id="duplicate-warning" class="hidden">
            <div class="p-4 bg-amber-50 border-2 border-amber-200 rounded-2xl">
                <div class="flex items-start gap-2.5">
                    <span class="text-xl mt-0.5">⚠️</span>
                    <div class="flex-1">
                        <p class="text-sm font-bold text-amber-800">この教室はすでに登録されています</p>
                        <p id="dup-spot-name" class="text-xs text-amber-600 mt-1"></p>
                        <p class="text-[11px] text-amber-500 mt-2">このまま投稿すると、既存スポットに情報が追加されます。</p>
                        <a id="dup-spot-link" href="#" class="inline-flex items-center gap-1 mt-2 text-xs font-bold" style="color: var(--color-coral);">
                            マップで確認する →
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Errors --}}
        @if ($errors->any())
        <div class="p-3.5 bg-red-50 border border-red-100 rounded-2xl">
            <ul class="text-xs text-red-600 font-medium space-y-1">
                @foreach ($errors->all() as $error)
                    <li class="flex items-start gap-1.5">
                        <svg class="flex-shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                        {{ $error }}
                    </li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Submit --}}
        <button type="submit" id="submit-btn"
            class="w-full text-white font-bold py-4 active:scale-[0.98] transition-all text-sm relative overflow-hidden fade-up" style="background: var(--color-coral); border-radius: var(--radius-pill); box-shadow: none; animation-delay:0.2s">
            <span id="submit-text">📍 この場所をシェアする</span>
            <span id="submit-loading" class="hidden flex items-center justify-center gap-2">
                <svg class="animate-spin" width="18" height="18" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="white" stroke-width="3" opacity="0.3"/><path d="M12 2a10 10 0 0 1 10 10" stroke="white" stroke-width="3" stroke-linecap="round"/></svg>
                登録中...
            </span>
        </button>
    </form>
</div>

<style>
    .search-mode-btn { background: transparent; color: var(--color-ink-soft); }
    .search-mode-btn.active { background: var(--color-white); color: var(--color-ink); box-shadow: none; border: 0.5px solid var(--color-border); }
</style>

<script>
// Duplicate check against existing spots
var existingSpots = @json($existingSpots);
function checkDuplicate(placeId, title, lat, lng) {
    var dup = null;
    if (placeId) {
        dup = existingSpots.find(function(s) { return s.google_place_id === placeId; });
    }
    if (!dup && title) {
        dup = existingSpots.find(function(s) {
            return s.title === title && Math.abs(s.lat - lat) < 0.002 && Math.abs(s.lng - lng) < 0.002;
        });
    }
    var warning = document.getElementById('duplicate-warning');
    if (dup) {
        document.getElementById('dup-spot-name').textContent = '「' + dup.title + '」として登録済みです';
        document.getElementById('dup-spot-link').href = '{{ route("spots.index") }}?focus=' + dup.id;
        warning.classList.remove('hidden');
    } else {
        warning.classList.add('hidden');
    }
}

var currentSearchMode = 'google';
window.setSearchMode = function(mode) {
    currentSearchMode = mode;
    document.getElementById('mode-google').classList.toggle('active', mode === 'google');
    document.getElementById('mode-manual').classList.toggle('active', mode === 'manual');
    document.getElementById('google-search-panel').classList.toggle('hidden', mode !== 'google');
    document.getElementById('manual-search-panel').classList.toggle('hidden', mode !== 'manual');
};

document.addEventListener('DOMContentLoaded', function() {
    var latInput = document.getElementById('lat');
    var lngInput = document.getElementById('lng');
    var placeIdInput = document.getElementById('google_place_id');
    var placeIdBadge = document.getElementById('place-id-badge');
    var currentAddress = document.getElementById('current-address');

    function waitForGoogleMaps(cb) {
        if (typeof google !== 'undefined' && google.maps && google.maps.marker) { cb(); return; }
        var iv = setInterval(function() {
            if (typeof google !== 'undefined' && google.maps && google.maps.marker) { clearInterval(iv); cb(); }
        }, 100);
    }

    var miniMap, marker, geocoder, placesService;

    waitForGoogleMaps(function() {
        miniMap = new google.maps.Map(document.getElementById('mini-map'), {
            center: { lat: 35.6465, lng: 139.6533 },
            zoom: 16, disableDefaultUI: true, zoomControl: true, gestureHandling: 'greedy',
            mapId: 'MIKKE_CREATE_MAP'
        });
        marker = new google.maps.marker.AdvancedMarkerElement({
            map: miniMap, position: { lat: 35.6465, lng: 139.6533 }, gmpDraggable: true
        });
        geocoder = new google.maps.Geocoder();
        placesService = new google.maps.places.PlacesService(miniMap);

        marker.addListener('dragend', function() {
            var pos = marker.position;
            placeIdInput.value = ''; placeIdBadge.classList.add('hidden');
            onMarkerMove(pos.lat, pos.lng);
        });
        miniMap.addListener('click', function(e) {
            marker.position = e.latLng;
            placeIdInput.value = ''; placeIdBadge.classList.add('hidden');
            onMarkerMove(e.latLng.lat(), e.latLng.lng());
        });

        navigator.geolocation.getCurrentPosition(function(pos) {
            var lat = pos.coords.latitude, lng = pos.coords.longitude;
            miniMap.setCenter({ lat: lat, lng: lng }); miniMap.setZoom(16);
            marker.position = { lat: lat, lng: lng };
            onMarkerMove(lat, lng);
        }, function() { reverseGeocode(35.6465, 139.6533); });

        // Google Places text search
        var placesInput = document.getElementById('places-input');
        var placesBtn = document.getElementById('places-search-btn');
        var placesResults = document.getElementById('places-results');

        function searchPlaces(query) {
            if (!query.trim()) return;
            placesBtn.textContent = '...'; placesBtn.disabled = true;
            placesResults.classList.add('hidden');
            placesService.textSearch({ query: query + ' 世田谷区', location: miniMap.getCenter(), radius: 5000 }, function(results, status) {
                placesBtn.textContent = '検索'; placesBtn.disabled = false;
                if (status !== google.maps.places.PlacesServiceStatus.OK || !results.length) {
                    placesResults.innerHTML = '<div style="padding:14px;text-align:center;color:#9ca3af;font-size:12px;">見つかりませんでした。「住所を入力」をお試しください。</div>';
                    placesResults.classList.remove('hidden'); return;
                }
                var html = '';
                results.slice(0, 5).forEach(function(place, idx) {
                    html += '<button type="button" data-place-idx="'+idx+'" class="place-result-item w-full text-left px-4 py-3 text-sm hover:bg-green-50 border-b border-gray-50 last:border-0 transition-colors"><div style="display:flex;align-items:center;gap:8px;"><div style="width:32px;height:32px;border-radius:8px;background:#f0fdf4;display:flex;align-items:center;justify-content:center;flex-shrink:0;"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="#16a34a" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 1 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></div><div style="flex:1;min-width:0;"><p style="font-size:13px;font-weight:700;color:#1f2937;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">'+(place.name||'')+'</p><p style="font-size:10px;color:#9ca3af;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">'+(place.formatted_address||'')+'</p></div></div></button>';
                });
                placesResults.innerHTML = html; placesResults.classList.remove('hidden');
                placesResults.querySelectorAll('.place-result-item').forEach(function(btn) {
                    btn.addEventListener('click', function() { selectPlace(results[parseInt(this.dataset.placeIdx)]); });
                });
            });
        }

        function selectPlace(place) {
            var loc = place.geometry.location;
            var lat = loc.lat(), lng = loc.lng();
            marker.position = { lat: lat, lng: lng };
            miniMap.setCenter({ lat: lat, lng: lng }); miniMap.setZoom(17);
            latInput.value = lat; lngInput.value = lng;
            placeIdInput.value = place.place_id || '';
            placesResults.classList.add('hidden'); placesInput.value = '';
            if (place.place_id) placeIdBadge.classList.remove('hidden');
            currentAddress.innerHTML = '<p class="text-[11px] font-bold flex items-center justify-center gap-1" style="color:var(--color-sage);"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 1 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>' + (place.formatted_address || '') + '</p>';
            var ti = document.getElementById('title-input');
            if (!ti.value.trim() && place.name) ti.value = place.name;
            checkDuplicate(place.place_id, place.name, lat, lng);
        }

        placesBtn.addEventListener('click', function() { searchPlaces(placesInput.value); });
        placesInput.addEventListener('keydown', function(e) { if (e.key === 'Enter') { e.preventDefault(); searchPlaces(placesInput.value); } });

        // Manual address search
        var addressInput = document.getElementById('address-input');
        var addressBtn = document.getElementById('address-search-btn');
        var addressResults = document.getElementById('address-results');

        function searchAddress(q) {
            if (!q.trim()) return;
            addressBtn.textContent = '...'; addressBtn.disabled = true;
            addressResults.classList.add('hidden');
            geocoder.geocode({ address: q, region: 'jp' }, function(results, status) {
                addressBtn.textContent = '検索'; addressBtn.disabled = false;
                if (status !== 'OK' || !results.length) {
                    addressResults.innerHTML = '<div style="padding:14px;text-align:center;color:#9ca3af;font-size:12px;">見つかりませんでした</div>';
                    addressResults.classList.remove('hidden'); return;
                }
                var html = '';
                results.slice(0, 5).forEach(function(r, idx) {
                    html += '<button type="button" data-geo-idx="'+idx+'" class="geo-result-item w-full text-left px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 border-b border-gray-50 last:border-0 flex items-center gap-2 transition-colors"><svg class="flex-shrink-0 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 1 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>' + (r.formatted_address||'') + '</button>';
                });
                addressResults.innerHTML = html; addressResults.classList.remove('hidden');
                addressResults.querySelectorAll('.geo-result-item').forEach(function(btn) {
                    btn.addEventListener('click', function() {
                        var r = results[parseInt(this.dataset.geoIdx)];
                        var loc = r.geometry.location;
                        marker.position = { lat: loc.lat(), lng: loc.lng() };
                        miniMap.setCenter({ lat: loc.lat(), lng: loc.lng() }); miniMap.setZoom(17);
                        latInput.value = loc.lat(); lngInput.value = loc.lng();
                        placeIdInput.value = ''; placeIdBadge.classList.add('hidden');
                        addressResults.classList.add('hidden'); addressInput.value = '';
                        currentAddress.innerHTML = '<p class="text-[11px] font-bold flex items-center justify-center gap-1" style="color:var(--color-sage);"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 1 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>' + r.formatted_address + '</p>';
                    });
                });
            });
        }

        addressBtn.addEventListener('click', function() { searchAddress(addressInput.value); });
        addressInput.addEventListener('keydown', function(e) { if (e.key === 'Enter') { e.preventDefault(); searchAddress(addressInput.value); } });
    });

    function onMarkerMove(lat, lng) {
        latInput.value = lat; lngInput.value = lng;
        reverseGeocode(lat, lng);
    }

    function reverseGeocode(lat, lng) {
        currentAddress.innerHTML = '<p class="text-[11px] text-gray-400 font-medium">住所を取得中...</p>';
        if (typeof geocoder !== 'undefined' && geocoder) {
            geocoder.geocode({ location: { lat: lat, lng: lng } }, function(results, status) {
                if (status === 'OK' && results[0]) {
                    currentAddress.innerHTML = '<p class="text-[11px] font-bold flex items-center justify-center gap-1" style="color:var(--color-sage);"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 1 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>' + results[0].formatted_address + '</p>';
                } else {
                    currentAddress.innerHTML = '<p class="text-[11px] text-gray-400 font-medium">住所を取得できませんでした</p>';
                }
            });
        }
    }

    // Image preview
    document.getElementById('image-input').addEventListener('change', function(e) {
        var file = e.target.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function(ev) {
                var p = document.getElementById('image-preview');
                p.src = ev.target.result; p.classList.remove('hidden');
                document.getElementById('placeholder').classList.add('hidden');
            };
            reader.readAsDataURL(file);
        }
    });

    // Close dropdowns on outside click
    document.addEventListener('click', function(e) {
        if (!e.target.closest('#places-input') && !e.target.closest('#places-results') && !e.target.closest('#places-search-btn'))
            document.getElementById('places-results').classList.add('hidden');
        if (!e.target.closest('#address-input') && !e.target.closest('#address-results') && !e.target.closest('#address-search-btn'))
            document.getElementById('address-results').classList.add('hidden');
    });

    // Form submit animation
    document.getElementById('spot-form').addEventListener('submit', function() {
        document.getElementById('submit-text').classList.add('hidden');
        document.getElementById('submit-loading').classList.remove('hidden');
        document.getElementById('submit-btn').disabled = true;
    });
});
</script>
@endsection
