@extends('layouts.app')

@section('content')
<div class="bg-white min-h-screen pb-20">
    {{-- Header --}}
    <div class="flex items-center justify-between px-5 py-3 border-b border-gray-100/60 sticky top-[41px] bg-white/95 backdrop-blur-xl z-[2000]">
        <a href="{{ route('spots.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </a>
        <h1 class="text-base font-black text-gray-900">スポットを登録</h1>
        <div class="w-6"></div>
    </div>

    <form action="{{ route('spots.store') }}" method="POST" enctype="multipart/form-data" class="p-5 space-y-5">
        @csrf
        <input type="hidden" name="google_place_id" id="google_place_id" value="">

        {{-- Search Mode Toggle --}}
        <div>
            <label class="text-xs font-bold text-gray-500 mb-2 block">場所を探す</label>
            <div class="flex bg-gray-100 rounded-xl p-1 mb-3">
                <button type="button" id="mode-google" onclick="setSearchMode('google')"
                    class="search-mode-btn active flex-1 py-2 rounded-lg text-xs font-bold transition-all flex items-center justify-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    Googleマップから検索
                </button>
                <button type="button" id="mode-manual" onclick="setSearchMode('manual')"
                    class="search-mode-btn flex-1 py-2 rounded-lg text-xs font-bold transition-all flex items-center justify-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 1 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    住所を直接入力
                </button>
            </div>

            {{-- Google Places Search --}}
            <div id="google-search-panel">
                <div class="flex gap-2">
                    <div class="flex-1 relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        <input type="text" id="places-input"
                            class="w-full bg-gray-50 rounded-xl pl-9 pr-3 py-3 text-sm font-medium outline-none border border-gray-100 focus:border-brand-400 focus:ring-2 focus:ring-brand-100 transition-all"
                            placeholder="施設名・教室名で検索...">
                    </div>
                    <button type="button" id="places-search-btn"
                        class="bg-brand-500 hover:bg-brand-600 text-white font-bold text-sm px-4 rounded-xl active:scale-95 transition-all whitespace-nowrap">
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
                            class="w-full bg-gray-50 rounded-xl pl-9 pr-3 py-3 text-sm font-medium outline-none border border-gray-100 focus:border-brand-400 focus:ring-2 focus:ring-brand-100 transition-all"
                            placeholder="住所を入力（例：世田谷区弦巻1-42-15）">
                    </div>
                    <button type="button" id="address-search-btn"
                        class="bg-gray-700 hover:bg-gray-800 text-white font-bold text-sm px-4 rounded-xl active:scale-95 transition-all whitespace-nowrap">
                        検索
                    </button>
                </div>
                <div id="address-results" class="mt-1.5 hidden bg-white border border-gray-100 rounded-xl shadow-lg overflow-hidden relative z-50"></div>
                <p class="mt-1.5 text-[10px] text-gray-400 font-medium">Googleマップに載っていない場所はこちらから登録できます</p>
            </div>
        </div>

        {{-- Mini Map --}}
        <div>
            <div id="mini-map" class="w-full rounded-2xl overflow-hidden border border-gray-100 shadow-sm" style="height:200px;"></div>
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

        {{-- Title --}}
        <div>
            <label class="text-xs font-bold text-gray-500 mb-2 block">場所の名前</label>
            <input type="text" name="title" id="title-input"
                class="w-full bg-gray-50 rounded-xl px-4 py-3 text-sm font-medium outline-none border border-gray-100 focus:border-brand-400 focus:ring-2 focus:ring-brand-100 transition-all"
                placeholder="例：駒沢公園 児童遊園" required>
        </div>

        {{-- Category Cards --}}
        <div>
            <label class="text-xs font-bold text-gray-500 mb-2 block">カテゴリ</label>
            <div class="grid grid-cols-3 gap-2">
                @php
                    $cats = [
                        ['val'=>'スポーツ少年団','emoji'=>'⚽','label'=>'スポーツ少年団','ring'=>'amber'],
                        ['val'=>'個人教室','emoji'=>'🎹','label'=>'個人教室','ring'=>'purple'],
                        ['val'=>'塾・学習','emoji'=>'📚','label'=>'塾・学習','ring'=>'blue'],
                        ['val'=>'公園・遊び場','emoji'=>'🌳','label'=>'公園・遊び場','ring'=>'green'],
                        ['val'=>'施設','emoji'=>'🏊','label'=>'施設','ring'=>'cyan'],
                        ['val'=>'その他','emoji'=>'📍','label'=>'その他','ring'=>'gray'],
                    ];
                @endphp
                @foreach($cats as $i => $cat)
                <label>
                    <input type="radio" name="category" value="{{ $cat['val'] }}" class="hidden peer" {{ $i===0?'checked':'' }}
                        onchange="toggleLessonFields()">
                    <div class="peer-checked:ring-2 peer-checked:ring-{{ $cat['ring'] }}-400 peer-checked:bg-{{ $cat['ring'] }}-50 bg-gray-50 border border-gray-100 rounded-2xl p-3 text-center cursor-pointer transition-all active:scale-95 hover:shadow-sm">
                        <span class="text-xl block mb-1">{{ $cat['emoji'] }}</span>
                        <span class="text-[10px] font-bold text-gray-600">{{ $cat['label'] }}</span>
                    </div>
                </label>
                @endforeach
            </div>
        </div>

        {{-- 習い事メタデータ --}}
        <div id="lesson-fields" class="space-y-4 bg-amber-50/50 rounded-2xl p-4 border border-amber-100/60">
            <p class="text-xs font-bold text-amber-700 flex items-center gap-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                習い事の詳細（親が本当に知りたい情報）
            </p>
            <div>
                <label class="text-xs font-bold text-gray-500 mb-1.5 block">💰 月謝の目安</label>
                <input type="text" name="monthly_fee_range" placeholder="例: 5,000〜8,000円"
                    class="w-full bg-white rounded-xl px-4 py-3 text-sm font-medium outline-none border border-gray-100 focus:border-amber-400 focus:ring-2 focus:ring-amber-100 transition-all">
            </div>
            <div>
                <label class="text-xs font-bold text-gray-500 mb-1.5 block">📋 当番・親の出番</label>
                <div class="flex gap-2">
                    <label class="flex-1">
                        <input type="radio" name="has_parent_duty" value="0" checked class="hidden peer">
                        <div class="peer-checked:bg-brand-500 peer-checked:text-white peer-checked:border-brand-500 bg-white text-gray-600 border border-gray-200 rounded-xl py-2.5 text-center text-sm font-bold cursor-pointer transition-all active:scale-95">当番なし</div>
                    </label>
                    <label class="flex-1">
                        <input type="radio" name="has_parent_duty" value="1" class="hidden peer">
                        <div class="peer-checked:bg-amber-500 peer-checked:text-white peer-checked:border-amber-500 bg-white text-gray-600 border border-gray-200 rounded-xl py-2.5 text-center text-sm font-bold cursor-pointer transition-all active:scale-95">当番あり</div>
                    </label>
                </div>
            </div>
            <div>
                <label class="text-xs font-bold text-gray-500 mb-1.5 block">🎯 指導方針</label>
                <div class="flex gap-2">
                    <label class="flex-1">
                        <input type="radio" name="policy_type" value="褒めて伸ばす" class="hidden peer">
                        <div class="peer-checked:bg-brand-500 peer-checked:text-white peer-checked:border-brand-500 bg-white text-gray-600 border border-gray-200 rounded-xl py-2.5 text-center text-xs font-bold cursor-pointer transition-all active:scale-95">🌱 褒めて伸ばす</div>
                    </label>
                    <label class="flex-1">
                        <input type="radio" name="policy_type" value="バランス型" checked class="hidden peer">
                        <div class="peer-checked:bg-blue-500 peer-checked:text-white peer-checked:border-blue-500 bg-white text-gray-600 border border-gray-200 rounded-xl py-2.5 text-center text-xs font-bold cursor-pointer transition-all active:scale-95">⚖️ バランス型</div>
                    </label>
                    <label class="flex-1">
                        <input type="radio" name="policy_type" value="厳しく鍛える" class="hidden peer">
                        <div class="peer-checked:bg-red-500 peer-checked:text-white peer-checked:border-red-500 bg-white text-gray-600 border border-gray-200 rounded-xl py-2.5 text-center text-xs font-bold cursor-pointer transition-all active:scale-95">🔥 厳しく鍛える</div>
                    </label>
                </div>
            </div>
            <div>
                <label class="text-xs font-bold text-gray-500 mb-1.5 block">🔄 振替</label>
                <div class="flex gap-2">
                    <label class="flex-1">
                        <input type="radio" name="transfer_available" value="1" class="hidden peer">
                        <div class="peer-checked:bg-brand-500 peer-checked:text-white peer-checked:border-brand-500 bg-white text-gray-600 border border-gray-200 rounded-xl py-2.5 text-center text-sm font-bold cursor-pointer transition-all active:scale-95">振替OK</div>
                    </label>
                    <label class="flex-1">
                        <input type="radio" name="transfer_available" value="0" checked class="hidden peer">
                        <div class="peer-checked:bg-gray-700 peer-checked:text-white peer-checked:border-gray-700 bg-white text-gray-600 border border-gray-200 rounded-xl py-2.5 text-center text-sm font-bold cursor-pointer transition-all active:scale-95">振替不可</div>
                    </label>
                </div>
            </div>
            <div>
                <label class="text-xs font-bold text-gray-500 mb-1.5 block">🎒 対象年齢</label>
                <input type="text" name="age_range" placeholder="例: 5-12"
                    class="w-full bg-white rounded-xl px-4 py-3 text-sm font-medium outline-none border border-gray-100 focus:border-amber-400 focus:ring-2 focus:ring-amber-100 transition-all">
            </div>
        </div>

        {{-- Photo --}}
        <div>
            <label class="text-xs font-bold text-gray-500 mb-2 block">写真（任意）</label>
            <div class="relative w-full bg-gray-50 border border-dashed border-gray-200 rounded-2xl flex flex-col items-center justify-center overflow-hidden transition-all hover:border-brand-300" style="aspect-ratio:16/9;">
                <img id="image-preview" class="hidden w-full h-full object-cover absolute inset-0">
                <div id="placeholder" class="text-center py-6 relative z-10">
                    <svg class="mx-auto text-gray-300 mb-2" xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                    <p class="text-xs text-gray-400 font-medium">タップして写真を選択</p>
                </div>
                <input type="file" name="image" id="image-input" class="absolute inset-0 opacity-0 cursor-pointer z-20" accept="image/*">
            </div>
        </div>

        {{-- Note --}}
        <div>
            <label class="text-xs font-bold text-gray-500 mb-2 block">詳しい内容（任意）</label>
            <textarea name="note" rows="3"
                class="w-full bg-gray-50 rounded-xl px-4 py-3 text-sm font-medium outline-none border border-gray-100 focus:border-brand-400 focus:ring-2 focus:ring-brand-100 transition-all resize-none"
                placeholder="伝えたいことを自由に書いてください"></textarea>
        </div>

        {{-- Duplicate Warning --}}
        <div id="duplicate-warning" class="hidden">
            <div class="p-4 bg-amber-50 border-2 border-amber-200 rounded-2xl">
                <div class="flex items-start gap-2.5">
                    <span class="text-xl mt-0.5">⚠️</span>
                    <div class="flex-1">
                        <p class="text-sm font-bold text-amber-800">この教室はすでに登録されています</p>
                        <p id="dup-spot-name" class="text-xs text-amber-600 mt-1"></p>
                        <p class="text-[11px] text-amber-500 mt-2">このまま投稿すると、既存スポットに情報が追加されます。</p>
                        <a id="dup-spot-link" href="#" class="inline-flex items-center gap-1 mt-2 text-xs font-bold text-brand-600">
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
        <button type="submit"
            class="w-full bg-brand-500 hover:bg-brand-600 text-white font-bold py-4 rounded-2xl shadow-[0_8px_24px_rgba(34,197,94,0.3)] active:scale-[0.98] transition-all text-sm">
            この場所をシェアする
        </button>
    </form>
</div>

<style>
    .search-mode-btn { background: transparent; color: #6b7280; }
    .search-mode-btn.active { background: white; color: #166534; box-shadow: 0 1px 4px rgba(0,0,0,0.08); }
</style>

<script>
function toggleLessonFields() {
    var checked = document.querySelector('input[name="category"]:checked');
    var lessonFields = document.getElementById('lesson-fields');
    if (!checked || !lessonFields) return;
    var lessonCats = ['スポーツ少年団', '個人教室', '塾・学習', '施設'];
    lessonFields.style.display = lessonCats.indexOf(checked.value) !== -1 ? 'block' : 'none';
}

// Duplicate check against existing spots
var existingSpots = @json($existingSpots);
function checkDuplicate(placeId, title, lat, lng) {
    var dup = null;
    // 1. Check by google_place_id
    if (placeId) {
        dup = existingSpots.find(function(s) { return s.google_place_id === placeId; });
    }
    // 2. Check by title + proximity (200m)
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
    toggleLessonFields();
    var latInput = document.getElementById('lat');
    var lngInput = document.getElementById('lng');
    var placeIdInput = document.getElementById('google_place_id');
    var placeIdBadge = document.getElementById('place-id-badge');
    var currentAddress = document.getElementById('current-address');

    // ==========================================
    // Google Maps + marker
    // ==========================================
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
            zoom: 16,
            disableDefaultUI: true,
            zoomControl: true,
            gestureHandling: 'greedy'
        });

        marker = new google.maps.marker.AdvancedMarkerElement({
            map: miniMap,
            position: { lat: 35.6465, lng: 139.6533 },
            gmpDraggable: true
        });

        geocoder = new google.maps.Geocoder();
        placesService = new google.maps.places.PlacesService(miniMap);

        marker.addListener('dragend', function() {
            var pos = marker.position;
            placeIdInput.value = '';
            placeIdBadge.classList.add('hidden');
            onMarkerMove(pos.lat, pos.lng);
        });

        miniMap.addListener('click', function(e) {
            marker.position = e.latLng;
            placeIdInput.value = '';
            placeIdBadge.classList.add('hidden');
            onMarkerMove(e.latLng.lat(), e.latLng.lng());
        });

        // Init: try geolocation
        navigator.geolocation.getCurrentPosition(function(pos) {
            var lat = pos.coords.latitude, lng = pos.coords.longitude;
            miniMap.setCenter({ lat: lat, lng: lng });
            miniMap.setZoom(16);
            marker.position = { lat: lat, lng: lng };
            onMarkerMove(lat, lng);
        }, function() { reverseGeocode(35.6465, 139.6533); });

        // ==========================================
        // Google Places text search
        // ==========================================
        var placesInput = document.getElementById('places-input');
        var placesBtn = document.getElementById('places-search-btn');
        var placesResults = document.getElementById('places-results');

        function searchPlaces(query) {
            if (!query.trim()) return;
            placesBtn.textContent = '...';
            placesBtn.disabled = true;
            placesResults.classList.add('hidden');

            var request = {
                query: query + ' 世田谷区',
                location: miniMap.getCenter(),
                radius: 5000,
            };

            placesService.textSearch(request, function(results, status) {
                placesBtn.textContent = '検索';
                placesBtn.disabled = false;

                if (status !== google.maps.places.PlacesServiceStatus.OK || !results.length) {
                    placesResults.innerHTML = '<div style="padding:14px;text-align:center;color:#9ca3af;font-size:12px;">見つかりませんでした。「住所を直接入力」をお試しください。</div>';
                    placesResults.classList.remove('hidden');
                    return;
                }

                var html = '';
                results.slice(0, 5).forEach(function(place, idx) {
                    var name = place.name || '';
                    var addr = place.formatted_address || '';
                    var rating = place.rating ? ' ★' + place.rating : '';
                    html += '<button type="button" data-place-idx="'+idx+'" class="place-result-item w-full text-left px-4 py-3 text-sm hover:bg-green-50 border-b border-gray-50 last:border-0 transition-colors">' +
                        '<div style="display:flex;align-items:center;gap:8px;">' +
                        '<div style="width:36px;height:36px;border-radius:10px;background:#f0fdf4;display:flex;align-items:center;justify-content:center;flex-shrink:0;"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="#16a34a" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 1 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></div>' +
                        '<div style="flex:1;min-width:0;">' +
                        '<p style="font-size:13px;font-weight:700;color:#1f2937;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">'+name+'<span style="font-size:10px;color:#f59e0b;margin-left:4px;">'+rating+'</span></p>' +
                        '<p style="font-size:10px;color:#9ca3af;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">'+addr+'</p>' +
                        '</div></div></button>';
                });
                placesResults.innerHTML = html;
                placesResults.classList.remove('hidden');

                // Attach click handlers
                placesResults.querySelectorAll('.place-result-item').forEach(function(btn) {
                    btn.addEventListener('click', function() {
                        var idx = parseInt(this.dataset.placeIdx);
                        var place = results[idx];
                        selectPlace(place);
                    });
                });
            });
        }

        function selectPlace(place) {
            var loc = place.geometry.location;
            var lat = loc.lat(), lng = loc.lng();

            marker.position = { lat: lat, lng: lng };
            miniMap.setCenter({ lat: lat, lng: lng });
            miniMap.setZoom(17);

            latInput.value = lat;
            lngInput.value = lng;
            placeIdInput.value = place.place_id || '';
            placesResults.classList.add('hidden');
            placesInput.value = '';

            // Show verification badge
            if (place.place_id) {
                placeIdBadge.classList.remove('hidden');
            }

            // Set address display
            currentAddress.innerHTML = '<p class="text-[11px] text-brand-600 font-bold flex items-center justify-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 1 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>' + (place.formatted_address || '') + '</p>';

            // Auto-fill title
            var ti = document.getElementById('title-input');
            if (!ti.value.trim() && place.name) ti.value = place.name;

            // Check for duplicates
            checkDuplicate(place.place_id, place.name, lat, lng);
        }

        placesBtn.addEventListener('click', function() { searchPlaces(placesInput.value); });
        placesInput.addEventListener('keydown', function(e) { if (e.key === 'Enter') { e.preventDefault(); searchPlaces(placesInput.value); } });

        // ==========================================
        // Manual address search (Geocoding API)
        // ==========================================
        var addressInput = document.getElementById('address-input');
        var addressBtn = document.getElementById('address-search-btn');
        var addressResults = document.getElementById('address-results');

        function searchAddress(q) {
            if (!q.trim()) return;
            addressBtn.textContent = '...';
            addressBtn.disabled = true;
            addressResults.classList.add('hidden');

            geocoder.geocode({ address: q, region: 'jp' }, function(results, status) {
                addressBtn.textContent = '検索';
                addressBtn.disabled = false;

                if (status !== 'OK' || !results.length) {
                    addressResults.innerHTML = '<div style="padding:14px;text-align:center;color:#9ca3af;font-size:12px;">見つかりませんでした</div>';
                    addressResults.classList.remove('hidden');
                    return;
                }

                var html = '';
                results.slice(0, 5).forEach(function(r, idx) {
                    var addr = r.formatted_address || '';
                    html += '<button type="button" data-geo-idx="'+idx+'" class="geo-result-item w-full text-left px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 border-b border-gray-50 last:border-0 flex items-center gap-2 transition-colors">' +
                        '<svg class="flex-shrink-0 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 1 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>' +
                        addr + '</button>';
                });
                addressResults.innerHTML = html;
                addressResults.classList.remove('hidden');

                addressResults.querySelectorAll('.geo-result-item').forEach(function(btn) {
                    btn.addEventListener('click', function() {
                        var idx = parseInt(this.dataset.geoIdx);
                        var r = results[idx];
                        var loc = r.geometry.location;
                        marker.position = { lat: loc.lat(), lng: loc.lng() };
                        miniMap.setCenter({ lat: loc.lat(), lng: loc.lng() });
                        miniMap.setZoom(17);
                        latInput.value = loc.lat();
                        lngInput.value = loc.lng();
                        placeIdInput.value = '';
                        placeIdBadge.classList.add('hidden');
                        addressResults.classList.add('hidden');
                        addressInput.value = '';
                        currentAddress.innerHTML = '<p class="text-[11px] text-brand-600 font-bold flex items-center justify-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 1 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>' + r.formatted_address + '</p>';
                    });
                });
            });
        }

        addressBtn.addEventListener('click', function() { searchAddress(addressInput.value); });
        addressInput.addEventListener('keydown', function(e) { if (e.key === 'Enter') { e.preventDefault(); searchAddress(addressInput.value); } });
    });

    // ==========================================
    // Helpers
    // ==========================================
    function onMarkerMove(lat, lng) {
        latInput.value = lat;
        lngInput.value = lng;
        reverseGeocode(lat, lng);
    }

    function reverseGeocode(lat, lng) {
        currentAddress.innerHTML = '<p class="text-[11px] text-gray-400 font-medium">住所を取得中...</p>';
        if (typeof geocoder !== 'undefined' && geocoder) {
            geocoder.geocode({ location: { lat: lat, lng: lng } }, function(results, status) {
                if (status === 'OK' && results[0]) {
                    currentAddress.innerHTML = '<p class="text-[11px] text-brand-600 font-bold flex items-center justify-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 1 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>' + results[0].formatted_address + '</p>';
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
                p.src = ev.target.result;
                p.classList.remove('hidden');
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
});
</script>
@endsection
