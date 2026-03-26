@extends('layouts.app')

@section('content')
<div class="min-h-screen pb-24" style="background: var(--color-cream);">

    {{-- Header --}}
    <div class="sticky top-[41px] z-40 backdrop-blur-xl" style="background: rgba(250,247,242,0.95); border-bottom: 0.5px solid var(--color-border);">
        <div class="px-4 py-3 flex items-center justify-between">
            <a href="{{ route('ambassador.index') }}" class="p-1" style="color: var(--color-ink-soft);">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
            </a>
            <h1 class="text-sm font-serif" style="color: var(--color-ink);">通信を投稿</h1>
            <div class="w-8"></div>
        </div>
    </div>

    <form action="{{ route('ambassador.store') }}" method="POST" enctype="multipart/form-data" id="post-form">
        @csrf

        {{-- Photo Upload (Big tap target) --}}
        <div class="px-4 pt-5">
            <label for="photo-input" id="photo-area" class="block relative rounded-2xl overflow-hidden bg-gray-50 border-2 border-dashed border-gray-200 cursor-pointer active:scale-[0.99] transition-all" style="aspect-ratio: 4/3;">
                <div id="photo-placeholder" class="absolute inset-0 flex flex-col items-center justify-center gap-3">
                    <div class="w-14 h-14 bg-brand-50 rounded-2xl flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" stroke="#22c55e" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                    </div>
                    <div class="text-center">
                        <p class="text-sm font-bold text-gray-600">写真をタップして選択</p>
                        <p class="text-[11px] text-gray-400 mt-0.5">教室の様子やレッスン風景を1枚</p>
                    </div>
                </div>
                <img id="photo-preview" src="" alt="" class="hidden absolute inset-0 w-full h-full object-cover">
                <input type="file" name="photo" id="photo-input" accept="image/*" capture="environment" class="sr-only" required>
            </label>
            @error('photo')
                <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
            @enderror
        </div>

        {{-- Spot Selection --}}
        <div class="px-4 pt-5">
            <label class="text-xs font-bold text-gray-500 mb-2 block">どの教室の通信？</label>
            <div class="space-y-2">
                @foreach($spots as $spot)
                <label class="spot-radio flex items-center gap-3 p-3 rounded-xl border-2 border-gray-100 cursor-pointer transition-all active:scale-[0.98]">
                    <input type="radio" name="spot_id" value="{{ $spot->id }}" class="sr-only" {{ $loop->first ? 'checked' : '' }} required>
                    @if($spot->image_path)
                        <img src="{{ $spot->image_path }}" alt="" class="w-10 h-10 rounded-lg object-cover">
                    @else
                        <div class="w-10 h-10 rounded-lg bg-brand-50 flex items-center justify-center flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="#16a34a" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 1 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-gray-800 truncate">{{ $spot->title }}</p>
                        <p class="text-[10px] text-gray-400">{{ $spot->category }}</p>
                    </div>
                    <div class="spot-radio-check w-5 h-5 rounded-full border-2 border-gray-200 flex items-center justify-center flex-shrink-0 transition-all">
                        <div class="w-2.5 h-2.5 rounded-full bg-brand-500 scale-0 transition-transform"></div>
                    </div>
                </label>
                @endforeach
            </div>
            @error('spot_id')
                <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
            @enderror
        </div>

        {{-- Mood Tag --}}
        <div class="px-4 pt-5">
            <label class="text-xs font-bold text-gray-500 mb-2 block">タグ（任意）</label>
            <div class="flex flex-wrap gap-2">
                @php $moods = ['レッスン風景', '生徒の成長', '教室の雰囲気', 'お知らせ', '先生の想い']; @endphp
                @foreach($moods as $mood)
                <label class="mood-tag cursor-pointer">
                    <input type="radio" name="mood_tag" value="{{ $mood }}" class="sr-only">
                    <span class="inline-block px-3.5 py-2 rounded-xl text-xs font-bold border-2 border-gray-100 text-gray-500 transition-all active:scale-95">{{ $mood }}</span>
                </label>
                @endforeach
            </div>
        </div>

        {{-- Osagari --}}
        <div class="px-4 pt-5">
            <label class="flex items-center gap-3 p-3.5 rounded-xl border-2 border-gray-100 cursor-pointer transition-all active:scale-[0.98]" id="osagari-toggle">
                <input type="checkbox" name="has_osagari" value="1" id="osagari-check" class="w-5 h-5 rounded-md accent-brand-500 cursor-pointer" onchange="toggleOsagari()">
                <div class="flex-1">
                    <p class="text-sm font-bold text-gray-800">🎁 お下がりあり</p>
                    <p class="text-[10px] text-gray-400 mt-0.5">使わなくなった用品を次の生徒へ</p>
                </div>
            </label>
            <div id="osagari-fields" class="hidden mt-3 space-y-3 pl-2 border-l-2 border-brand-200 ml-2">
                <div>
                    <label class="text-[11px] font-bold text-gray-500 block mb-1">アイテム名 <span class="text-red-400">*</span></label>
                    <input type="text" name="osagari_item" id="osagari-item" placeholder="例：水着、バレエシューズ、算数テキスト" class="input-field text-sm" value="{{ old('osagari_item') }}">
                    @error('osagari_item')
                        <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="text-[11px] font-bold text-gray-500 block mb-1">サイズ・状態</label>
                    <input type="text" name="osagari_size" id="osagari-size" placeholder="例：120cm、Sサイズ、美品" class="input-field text-sm" value="{{ old('osagari_size') }}">
                </div>
            </div>
        </div>

        {{-- Message --}}
        <div class="px-4 pt-5">
            <label class="text-xs font-bold text-gray-500 mb-2 block">ひとこと <span class="text-gray-300">（200文字以内）</span></label>
            <textarea name="message" id="message-input" rows="3" maxlength="200" required placeholder="今日のレッスンの様子を一言で…"
                class="w-full bg-gray-50 rounded-xl border border-gray-200 px-4 py-3 text-sm font-medium text-gray-800 placeholder-gray-300 outline-none focus:border-brand-400 focus:ring-2 focus:ring-brand-100 transition-all resize-none">{{ old('message') }}</textarea>
            <div class="flex justify-end mt-1">
                <span id="char-count" class="text-[10px] font-bold text-gray-300">0/200</span>
            </div>
            @error('message')
                <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
            @enderror
        </div>

        {{-- Submit --}}
        <div class="px-4 pt-6 pb-8">
            <button type="submit" id="submit-btn"
                class="w-full bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-bold text-sm py-4 rounded-2xl shadow-[0_8px_24px_rgba(245,158,11,0.3)] active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                通信を発信する
            </button>
        </div>
    </form>
</div>

<style>
    .spot-radio:has(input:checked) {
        border-color: #22c55e;
        background: #f0fdf4;
    }
    .spot-radio:has(input:checked) .spot-radio-check {
        border-color: #22c55e;
    }
    .spot-radio:has(input:checked) .spot-radio-check > div {
        transform: scale(1);
    }
    .mood-tag:has(input:checked) span {
        border-color: #f59e0b;
        background: #fef3c7;
        color: #92400e;
    }
    #photo-area:has(#photo-preview:not(.hidden)) {
        border-style: solid;
        border-color: #22c55e;
    }
</style>

<script>
// Photo preview
document.getElementById('photo-input').addEventListener('change', function(e) {
    var file = e.target.files[0];
    if (!file) return;
    var reader = new FileReader();
    reader.onload = function(ev) {
        var preview = document.getElementById('photo-preview');
        preview.src = ev.target.result;
        preview.classList.remove('hidden');
        document.getElementById('photo-placeholder').classList.add('hidden');
    };
    reader.readAsDataURL(file);
});

// Character counter
document.getElementById('message-input').addEventListener('input', function() {
    var count = this.value.length;
    var el = document.getElementById('char-count');
    el.textContent = count + '/200';
    el.className = count > 180 ? 'text-[10px] font-bold text-red-400' : 'text-[10px] font-bold text-gray-300';
});

// Osagari toggle
function toggleOsagari() {
    var checked = document.getElementById('osagari-check').checked;
    var fields = document.getElementById('osagari-fields');
    var toggle = document.getElementById('osagari-toggle');
    fields.classList.toggle('hidden', !checked);
    toggle.style.borderColor = checked ? '#22c55e' : '';
    toggle.style.background = checked ? '#f0fdf4' : '';
    if (checked) document.getElementById('osagari-item').focus();
}

// Prevent double submit
document.getElementById('post-form').addEventListener('submit', function() {
    var btn = document.getElementById('submit-btn');
    btn.disabled = true;
    btn.innerHTML = '<svg class="animate-spin" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" class="opacity-25"/><path d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" fill="currentColor" class="opacity-75"/></svg> 送信中…';
});
</script>
@endsection
