@extends('layouts.app')

@section('content')
<div class="min-h-screen pb-24" style="background: var(--color-cream);">

    {{-- Header --}}
    <header class="sticky top-[41px] z-40" style="background: var(--color-cream); border-bottom: 0.5px solid var(--color-border);">
        <div class="px-4 pt-3 pb-2.5">
            <div class="flex items-center justify-between mb-2.5">
                <div>
                    <h1 class="text-lg font-serif" style="color: var(--color-ink);">アンバサダー通信</h1>
                    <p class="text-[11px] text-gray-400 font-medium mt-0.5">教室の先生から届くリアルな情報</p>
                </div>
                <div class="flex items-center gap-1.5 bg-amber-50 px-2.5 py-1 rounded-full border border-amber-200/50">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="#f59e0b" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    <span class="text-[10px] font-bold text-amber-700">公式</span>
                </div>
            </div>

            {{-- Search --}}
            <div class="relative mb-2.5">
                <input type="text" id="amb-search" placeholder="教室名・カテゴリで検索…"
                    class="w-full bg-gray-100 rounded-lg px-3 py-2 pl-8 text-[13px] font-medium outline-none border border-transparent focus:border-amber-400 focus:bg-white transition-all placeholder-gray-400"
                    oninput="searchPosts(this.value)">
                <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            </div>

            {{-- Ambassador quick select (horizontal scroll) --}}
            <div class="flex gap-2.5 overflow-x-auto no-scrollbar pb-0.5">
                <button onclick="filterAmb('all')" class="amb-chip active" data-amb="all">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-amber-100 to-orange-100 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="#d97706" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg>
                    </div>
                    <span class="text-[10px] font-bold text-gray-600 mt-0.5 leading-tight">すべて</span>
                </button>
                @foreach($ambassadors as $amb)
                <button onclick="filterAmb({{ $amb->id }})" class="amb-chip" data-amb="{{ $amb->id }}">
                    @if($amb->avatar_url)
                        <img src="{{ $amb->avatar_url }}" alt="" class="w-8 h-8 rounded-full object-cover ring-2 ring-transparent">
                    @else
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center ring-2 ring-transparent">
                            <span class="text-white text-xs font-black">{{ mb_substr($amb->name, 0, 1) }}</span>
                        </div>
                    @endif
                    <span class="text-[10px] font-bold text-gray-500 mt-0.5 leading-tight truncate max-w-[56px]">{{ $amb->organization_name ? mb_substr($amb->organization_name, 0, 5) : mb_substr($amb->name, 0, 4) }}</span>
                </button>
                @endforeach
            </div>
        </div>
    </header>

    {{-- Post Timeline (business-first) --}}
    <div class="pt-2" id="amb-feed">
        @forelse($allPosts as $post)
        <article class="post-item border-b fade-up" style="background: var(--color-white); border-color: var(--color-border);"
                 style="animation-delay:{{ $loop->index * 0.04 }}s"
                 data-amb="{{ $post->user_id }}">

            {{-- Business header bar --}}
            <div class="px-4 pt-3 pb-2">
                <a href="{{ route('ambassador.show', $post->user) }}" class="flex items-center gap-2.5 group">
                    {{-- Ambassador avatar --}}
                    @if($post->user->avatar_url)
                        <img src="{{ $post->user->avatar_url }}" alt="" class="w-10 h-10 rounded-full object-cover ring-2 ring-amber-100 shadow-sm">
                    @else
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center ring-2 ring-amber-100 shadow-sm">
                            <span class="text-white text-sm font-black">{{ mb_substr($post->user->name, 0, 1) }}</span>
                        </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-1.5">
                            <span class="text-[13px] font-extrabold text-gray-900 truncate group-hover:text-amber-700 transition-colors">
                                {{ $post->user->organization_name ?? $post->user->name }}
                            </span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="#f59e0b" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                        </div>
                        <div class="flex items-center gap-1.5">
                            {{-- Spot/business name --}}
                            @if($post->spot)
                            <span class="text-[11px] font-bold text-amber-600">📍 {{ $post->spot->title }}</span>
                            <span class="text-[10px] text-gray-300">·</span>
                            @endif
                            <span class="text-[11px] text-gray-400">{{ $post->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="#d1d5db" stroke-width="2" stroke-linecap="round" class="flex-shrink-0"><circle cx="12" cy="12" r="1"/><circle cx="19" cy="12" r="1"/><circle cx="5" cy="12" r="1"/></svg>
                </a>
            </div>

            {{-- Photo --}}
            <div class="relative bg-gray-100" style="aspect-ratio:4/3;">
                <img src="{{ str_starts_with($post->photo_path, 'http') ? $post->photo_path : asset('storage/' . $post->photo_path) }}"
                     alt="" class="w-full h-full object-cover" loading="lazy">

                {{-- Badges overlay --}}
                <div class="absolute top-3 left-3 flex gap-1.5">
                    @if($post->mood_tag)
                    <span class="px-2 py-1 rounded-lg text-[11px] font-bold bg-white/90 backdrop-blur-sm text-gray-700 shadow-sm">{{ $post->mood_tag }}</span>
                    @endif
                    @if($post->has_osagari)
                    <span class="px-2 py-1 rounded-lg text-[11px] font-medium backdrop-blur-sm text-white" style="background: rgba(232,112,74,0.9);">🎁 お下がり有</span>
                    @endif
                </div>

                {{-- Spot category badge --}}
                @if($post->spot && $post->spot->category)
                <div class="absolute top-3 right-3">
                    <span class="px-2 py-1 rounded-lg text-[10px] font-bold bg-black/50 backdrop-blur-sm text-white">{{ $post->spot->category }}</span>
                </div>
                @endif
            </div>

            {{-- Content --}}
            <div class="px-4 pt-3 pb-3">
                {{-- Message --}}
                <p class="text-[14px] text-gray-800 leading-relaxed font-medium mb-2">
                    <span class="font-extrabold text-gray-900">{{ $post->user->organization_name ?? $post->user->name }}</span>
                    {{ $post->message }}
                </p>

                {{-- Osagari card --}}
                @if($post->has_osagari)
                <div class="flex items-center gap-2.5 rounded-xl px-3 py-2.5 mb-2.5" style="background: var(--color-coral-light); border: 0.5px solid var(--color-border);">
                    <span class="text-xl">🎁</span>
                    <div class="flex-1 min-w-0">
                        <p class="text-[12px] font-medium" style="color: var(--color-coral);">お下がりあります：{{ $post->osagari_item }}</p>
                        @if($post->osagari_size)
                        <p class="text-[11px] text-brand-600">サイズ：{{ $post->osagari_size }}</p>
                        @endif
                    </div>
                    <a href="{{ route('ambassador.trial', ['ambassador' => $post->user, 'osagari_post' => $post->id]) }}"
                       class="text-white text-[11px] font-medium px-3 py-1.5 active:scale-95 transition-all flex-shrink-0" style="background: var(--color-coral); border-radius: var(--radius-pill);">
                        申込む
                    </a>
                </div>
                @endif

                {{-- Spot info card (prominent) --}}
                @if($post->spot)
                <a href="{{ route('spots.index', ['focus' => $post->spot->id]) }}"
                   class="flex items-center gap-3 bg-gray-50 rounded-xl px-3 py-2.5 mb-2.5 border border-gray-100 group hover:bg-amber-50/50 hover:border-amber-100 transition-all">
                    @if($post->spot->image_path)
                        <img src="{{ $post->spot->image_path }}" alt="" class="w-10 h-10 rounded-lg object-cover flex-shrink-0">
                    @else
                        <div class="w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="#d97706" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 1 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <p class="text-[13px] font-bold text-gray-900 truncate group-hover:text-amber-700 transition-colors">{{ $post->spot->title }}</p>
                        <div class="flex items-center gap-1.5 mt-0.5">
                            <span class="text-[10px] text-gray-400">{{ $post->spot->category }}</span>
                            @if($post->spot->monthly_fee_range)
                            <span class="text-[10px] text-gray-300">·</span>
                            <span class="text-[10px] text-gray-400">{{ $post->spot->monthly_fee_range }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center gap-1 text-[10px] font-bold text-amber-600 flex-shrink-0">
                        <span>マップ</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="9 18 15 12 9 6"/></svg>
                    </div>
                </a>
                @endif

                {{-- Action row --}}
                <div class="flex items-center justify-between pt-1">
                    <div class="flex items-center gap-4">
                        {{-- Q&A link --}}
                        <a href="{{ route('ambassador.show', $post->user) }}#qa-section" class="flex items-center gap-1 text-gray-400 hover:text-amber-600 transition-colors active:scale-95">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                            <span class="text-[11px] font-bold">質問する</span>
                        </a>
                        {{-- Trial link --}}
                        <a href="{{ route('ambassador.trial', $post->user) }}" class="flex items-center gap-1 text-gray-400 hover:text-brand-600 transition-colors active:scale-95">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            <span class="text-[11px] font-bold">体験申込</span>
                        </a>
                    </div>
                    {{-- Share/Bookmark --}}
                    <button class="text-gray-300 hover:text-gray-500 transition-colors active:scale-90" onclick="sharePost(this)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/><polyline points="16 6 12 2 8 6"/><line x1="12" y1="2" x2="12" y2="15"/></svg>
                    </button>
                </div>
            </div>
        </article>
        @empty
        <div class="text-center py-20 fade-up bg-white">
            <div class="w-14 h-14 bg-gray-50 rounded-xl flex items-center justify-center mx-auto mb-3 border border-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="#d1d5db" stroke-width="1.5"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
            </div>
            <p class="text-sm font-bold text-gray-400">まだ投稿がありません</p>
            <p class="text-[11px] text-gray-300 mt-1">アンバサダーからの情報をお待ちください</p>
        </div>
        @endforelse
    </div>
</div>

<style>
    .amb-chip {
        display: flex; flex-direction: column; align-items: center; gap: 2px;
        flex-shrink: 0; cursor: pointer; padding: 2px; transition: all 0.15s;
    }
    .amb-chip.active img,
    .amb-chip.active > div:first-child {
        ring-color: #f59e0b !important;
        box-shadow: 0 0 0 2px #f59e0b;
    }
    .amb-chip.active span:last-child { color: #92400e; font-weight: 800; }
    .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
</style>

<script>
function searchPosts(query) {
    query = query.toLowerCase().trim();
    document.querySelectorAll('.post-item').forEach(function(item) {
        if (!query) { item.style.display = ''; return; }
        item.style.display = item.textContent.toLowerCase().indexOf(query) !== -1 ? '' : 'none';
    });
}

function filterAmb(ambId) {
    document.querySelectorAll('.amb-chip').forEach(function(c) {
        var match = ambId === 'all' ? c.dataset.amb === 'all' : c.dataset.amb == ambId;
        c.classList.toggle('active', match);
    });
    document.querySelectorAll('.post-item').forEach(function(item) {
        if (ambId === 'all') { item.style.display = ''; }
        else { item.style.display = item.dataset.amb == ambId ? '' : 'none'; }
    });
}

function sharePost(btn) {
    var article = btn.closest('article');
    var text = article.querySelector('p.text-\\[14px\\]')?.textContent?.trim() || '';
    if (navigator.share) {
        navigator.share({ title: 'みっけ', text: text, url: location.href });
    } else {
        btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="#22c55e" stroke-width="2" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>';
        setTimeout(function(){ btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/><polyline points="16 6 12 2 8 6"/><line x1="12" y1="2" x2="12" y2="15"/></svg>'; }, 1500);
    }
}
</script>
@endsection
