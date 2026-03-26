@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto min-h-screen pb-20" style="background: var(--color-cream);">
    {{-- Header with search --}}
    <header class="px-5 py-4 backdrop-blur-xl sticky top-0 z-50" style="background: rgba(250,247,242,0.95); border-bottom: 0.5px solid var(--color-border);">
        <h1 class="text-lg font-serif mb-3" style="color: var(--color-ink);">さがす</h1>
        <form action="{{ route('search.index') }}" method="GET">
            <div class="relative">
                <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="text" name="query" value="{{ $query ?? '' }}" placeholder="スポット・質問・通信を検索..."
                    class="w-full rounded-xl pl-10 pr-4 py-3 text-sm outline-none transition-all" style="background: var(--color-white); border: 0.5px solid var(--color-border); color: var(--color-ink);">
            </div>
        </form>
    </header>

    @if(isset($query) && $query)
    <div class="px-5 pt-4">
        <p class="text-xs font-bold text-gray-400">「<span class="text-gray-600">{{ $query }}</span>」の検索結果</p>
    </div>
    @endif

    @if(isset($query) && $query && $spots->isEmpty() && $questions->isEmpty() && $ambassadorPosts->isEmpty())
        <div class="py-24 text-center">
            <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" stroke="#d1d5db" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            </div>
            <p class="text-sm text-gray-400 font-medium">見つかりませんでした</p>
            <p class="text-xs text-gray-300 mt-1">別のキーワードで試してみてください</p>
        </div>
    @else
        <div class="divide-y divide-gray-50">
            {{-- Spots --}}
            @if(isset($spots) && $spots->count())
            <div class="p-5">
                <h2 class="text-xs font-bold text-gray-400 mb-3 flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 1 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    スポット（{{ $spots->count() }}件）
                </h2>
                <div class="space-y-2.5">
                    @foreach($spots as $spot)
                    <div class="bg-gray-50 rounded-2xl p-3.5 active:bg-gray-100 transition-colors">
                        <div class="flex items-center gap-3">
                            @if($spot->image_path)
                            <img src="{{ $spot->image_path }}" class="w-12 h-12 rounded-xl object-cover flex-shrink-0">
                            @else
                            <div class="w-12 h-12 bg-brand-50 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="#16a34a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 1 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <h3 class="font-bold text-sm text-gray-900 truncate">{{ $spot->title }}</h3>
                                @if($spot->category)
                                <span class="text-[10px] font-bold text-brand-600">{{ $spot->category }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Questions --}}
            @if(isset($questions) && $questions->count())
            <div class="p-5">
                <h2 class="text-xs font-bold text-gray-400 mb-3 flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    質問（{{ $questions->count() }}件）
                </h2>
                <div class="space-y-2.5">
                    @foreach($questions as $question)
                    <div class="bg-gray-50 rounded-2xl p-3.5 active:bg-gray-100 transition-colors">
                        <h3 class="font-bold text-sm text-gray-900 mb-1">{{ $question->title }}</h3>
                        @if($question->note)
                        <p class="text-xs text-gray-500 line-clamp-2">{{ $question->note }}</p>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Ambassador Posts --}}
            @if(isset($ambassadorPosts) && $ambassadorPosts->count())
            <div class="p-5">
                <h2 class="text-xs font-bold text-gray-400 mb-3 flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="#d97706" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    アンバサダー通信（{{ $ambassadorPosts->count() }}件）
                </h2>
                <div class="space-y-2.5">
                    @foreach($ambassadorPosts as $post)
                    <a href="{{ route('ambassador.index') }}" class="block">
                        <div class="bg-amber-50/50 rounded-2xl p-3.5 active:bg-amber-50 transition-colors flex items-center gap-3">
                            <img src="{{ str_starts_with($post->photo_path, 'http') ? $post->photo_path : asset('storage/' . $post->photo_path) }}"
                                 class="w-12 h-12 rounded-xl object-cover flex-shrink-0">
                            <div class="flex-1 min-w-0">
                                <h3 class="font-bold text-sm text-gray-900 line-clamp-1">{{ $post->message }}</h3>
                                <span class="text-[10px] text-amber-600 font-bold">{{ $post->spot->title ?? '' }} · {{ $post->user->organization_name ?? '' }}</span>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    @endif

    {{-- Quick links when no search --}}
    @if(!isset($query) || !$query)
    <div class="p-5 space-y-4">
        <h2 class="text-xs font-bold text-gray-400">クイックアクセス</h2>
        <div class="grid grid-cols-2 gap-2.5">
            <a href="{{ route('spots.index') }}" class="bg-brand-50 rounded-2xl p-4 text-center active:scale-95 transition-transform">
                <svg class="mx-auto mb-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="#16a34a" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 1 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                <p class="text-xs font-bold text-brand-700">マップを見る</p>
            </a>
            <a href="{{ route('questions.index') }}" class="bg-amber-50 rounded-2xl p-4 text-center active:scale-95 transition-transform">
                <svg class="mx-auto mb-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="#d97706" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                <p class="text-xs font-bold text-amber-700">質問箱を見る</p>
            </a>
            <a href="{{ route('ambassador.index') }}" class="bg-orange-50 rounded-2xl p-4 text-center active:scale-95 transition-transform">
                <svg class="mx-auto mb-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="#ea580c" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                <p class="text-xs font-bold text-orange-700">通信を見る</p>
            </a>
            <a href="{{ route('spots.create') }}" class="bg-blue-50 rounded-2xl p-4 text-center active:scale-95 transition-transform">
                <svg class="mx-auto mb-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="#2563eb" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                <p class="text-xs font-bold text-blue-700">スポット登録</p>
            </a>
        </div>
    </div>
    @endif
</div>
@endsection
