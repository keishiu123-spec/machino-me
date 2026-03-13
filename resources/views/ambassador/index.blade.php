@extends('layouts.app')

@section('content')
<div class="bg-surface-50 min-h-screen pb-24">

    {{-- Header --}}
    <div class="sticky top-[41px] z-40 bg-white/95 backdrop-blur-xl border-b border-gray-100/60">
        <div class="px-5 pt-4 pb-3">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <h1 class="text-lg font-black text-gray-900 tracking-tight">アンバサダー通信</h1>
                    <p class="text-[11px] text-gray-400 font-medium mt-0.5">月額公式パートナーが届ける教室のリアル</p>
                </div>
                <div class="flex items-center gap-1.5 bg-gradient-to-r from-amber-50 to-orange-50 px-3 py-1.5 rounded-full border border-amber-100/50">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="#f59e0b" stroke="#f59e0b" stroke-width="0.5" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    <span class="text-[10px] font-bold text-amber-700">公式</span>
                </div>
            </div>

            {{-- Search --}}
            <div class="relative">
                <input type="text" id="amb-search" placeholder="アンバサダー・教室名で検索…" class="input-field pl-9"
                    oninput="searchAmbassadors(this.value)">
                <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-300" xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            </div>
        </div>
    </div>

    {{-- Ambassador Cards --}}
    <div class="px-4 pt-4 space-y-4" id="amb-feed">
        @forelse($ambassadors as $amb)
        @php
            $latestPost = $amb->ambassadorPosts->first();
            $spots = $amb->managedSpots;
        @endphp
        <div class="card card-elevated overflow-hidden fade-up" style="animation-delay: {{ $loop->index * 0.08 }}s;">

            {{-- Cover: latest post photo or gradient --}}
            <div class="relative h-40 bg-gradient-to-br from-amber-100 to-orange-50 overflow-hidden">
                @if($latestPost)
                    <img src="{{ str_starts_with($latestPost->photo_path, 'http') ? $latestPost->photo_path : asset('storage/' . $latestPost->photo_path) }}"
                         alt="" class="w-full h-full object-cover" loading="lazy">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-black/10 to-transparent"></div>
                    @if($latestPost->mood_tag)
                    <div class="absolute top-3 left-3">
                        <span class="badge badge-amber backdrop-blur-sm bg-white/90 text-gray-700">{{ $latestPost->mood_tag }}</span>
                    </div>
                    @endif
                    <div class="absolute bottom-3 left-4 right-4">
                        <p class="text-white text-xs font-medium leading-relaxed line-clamp-2 drop-shadow-md">{{ $latestPost->message }}</p>
                    </div>
                @else
                    <div class="absolute inset-0 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="none" stroke="#f59e0b" stroke-width="1.2" opacity="0.3"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    </div>
                @endif
            </div>

            {{-- Profile Section --}}
            <div class="px-4 -mt-7 relative z-10">
                <div class="flex items-end gap-3">
                    <div class="relative flex-shrink-0">
                        @if($amb->avatar_url)
                            <img src="{{ $amb->avatar_url }}" alt="" class="w-14 h-14 rounded-2xl object-cover ring-[3px] ring-white shadow-md">
                        @else
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center ring-[3px] ring-white shadow-md">
                                <span class="text-white text-lg font-black">{{ mb_substr($amb->name, 0, 1) }}</span>
                            </div>
                        @endif
                        <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-amber-400 rounded-full flex items-center justify-center border-2 border-white">
                            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="white" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0 pb-1">
                        <div class="flex items-center gap-1.5">
                            <h2 class="text-base font-black text-gray-900 truncate">{{ $amb->organization_name ?? $amb->name }}</h2>
                            <span class="badge badge-amber text-[9px] flex-shrink-0">公式</span>
                        </div>
                        <p class="text-[11px] text-gray-400 font-medium truncate">{{ $amb->name }}</p>
                    </div>
                </div>
            </div>

            {{-- Bio --}}
            @if($amb->bio)
            <div class="px-4 pt-3">
                <p class="text-[12px] text-gray-500 leading-relaxed">{{ $amb->bio }}</p>
            </div>
            @endif

            {{-- Managed Spots --}}
            @if($spots->count())
            <div class="px-4 pt-3">
                <div class="flex gap-2 overflow-x-auto no-scrollbar">
                    @foreach($spots as $spot)
                    <a href="{{ route('spots.index', ['focus' => $spot->id]) }}" class="flex items-center gap-2 bg-brand-50/70 rounded-xl px-2.5 py-2 flex-shrink-0 border border-brand-100/40 active:scale-95 transition-transform">
                        @if($spot->image_path)
                            <img src="{{ $spot->image_path }}" alt="" class="w-7 h-7 rounded-lg object-cover">
                        @else
                            <div class="w-7 h-7 rounded-lg bg-brand-100 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" stroke="#16a34a" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 1 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            </div>
                        @endif
                        <span class="text-[11px] font-bold text-brand-800">{{ $spot->title }}</span>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Stats & CTA --}}
            <div class="px-4 pt-4 pb-4 flex items-center gap-3">
                <div class="flex items-center gap-4 flex-1">
                    <div class="text-center">
                        <p class="text-lg font-black text-gray-900">{{ $amb->ambassador_posts_count }}</p>
                        <p class="text-[9px] font-bold text-gray-400">投稿数</p>
                    </div>
                    @if($latestPost)
                    <div class="text-center">
                        <p class="text-xs font-bold text-gray-500">{{ $latestPost->created_at->diffForHumans() }}</p>
                        <p class="text-[9px] font-bold text-gray-400">最終更新</p>
                    </div>
                    @endif
                </div>
                <a href="{{ route('ambassador.show', $amb) }}"
                   class="bg-gradient-to-r from-amber-500 to-orange-500 text-white text-xs font-bold px-5 py-3 rounded-xl shadow-[0_4px_16px_rgba(245,158,11,0.25)] active:scale-95 transition-all flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                    声を聞く
                </a>
            </div>
        </div>
        @empty
        <div class="text-center py-20 fade-up">
            <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" stroke="#d1d5db" stroke-width="1.5"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
            </div>
            <p class="text-sm font-bold text-gray-400">まだアンバサダーがいません</p>
            <p class="text-[11px] text-gray-300 mt-1">公式パートナーの登録をお待ちください</p>
        </div>
        @endforelse
    </div>
</div>

<style>
.line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
</style>

<script>
function searchAmbassadors(query) {
    query = query.toLowerCase().trim();
    document.querySelectorAll('#amb-feed > div').forEach(function(card) {
        if (!query) { card.style.display = ''; return; }
        var text = card.textContent.toLowerCase();
        card.style.display = text.indexOf(query) !== -1 ? '' : 'none';
    });
}
</script>
@endsection
