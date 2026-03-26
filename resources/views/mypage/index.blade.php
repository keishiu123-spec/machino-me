@extends('layouts.app')

@section('content')

@guest
<div class="min-h-screen pb-24" style="background: var(--color-cream);">
    <div class="px-6 pt-10 pb-6 text-center fade-up">
        <div class="w-20 h-20 rounded-3xl flex items-center justify-center mx-auto mb-6" style="background: var(--color-coral);">
            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        </div>
        <h2 class="text-xl font-serif mb-2" style="color: var(--color-ink);">マイページ</h2>
        <p class="text-sm mb-6 leading-relaxed" style="color: var(--color-ink-mid);">スポット登録・体験レポートは<br>ログインなしでもご利用いただけます</p>
    </div>

    <div class="px-5 space-y-4 max-w-sm mx-auto">
        {{-- ゲストでもできること --}}
        <div class="card p-4">
            <p class="text-xs font-bold mb-3" style="color: var(--color-ink-soft);">ログインなしでできること</p>
            <div class="space-y-2.5">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" style="background: var(--color-coral-light);">
                        <span class="text-sm">📍</span>
                    </div>
                    <span class="text-sm" style="color: var(--color-ink);">スポットを登録する</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" style="background: var(--color-gold-light);">
                        <span class="text-sm">📝</span>
                    </div>
                    <span class="text-sm" style="color: var(--color-ink);">体験レポートを書く</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" style="background: var(--color-sage-light);">
                        <span class="text-sm">🗺</span>
                    </div>
                    <span class="text-sm" style="color: var(--color-ink);">マップですべてのスポットを見る</span>
                </div>
            </div>
        </div>

        {{-- ログインするともっと便利 --}}
        <div class="card p-4">
            <p class="text-xs font-bold mb-3" style="color: var(--color-gold);">LINEログインするともっと便利</p>
            <div class="space-y-2.5 mb-4">
                <div class="flex items-center gap-3">
                    <span class="text-sm">💛</span>
                    <span class="text-sm" style="color: var(--color-ink-mid);">お気に入り教室を保存</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-sm">🏫</span>
                    <span class="text-sm" style="color: var(--color-ink-mid);">マイスクール設定で徒歩分数を表示</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-sm">📋</span>
                    <span class="text-sm" style="color: var(--color-ink-mid);">自分の投稿を管理</span>
                </div>
            </div>
            <a href="{{ route('auth.line') }}"
               class="flex items-center justify-center gap-2 w-full bg-[#06C755] hover:bg-[#05b64d] text-white font-medium py-3.5 active:scale-[0.98] transition-all text-sm" style="border-radius: var(--radius-pill);">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="white"><path d="M19.365 9.863c.349 0 .63.285.63.631 0 .345-.281.63-.63.63H17.61v1.125h1.755c.349 0 .63.283.63.63 0 .344-.281.629-.63.629h-2.386c-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.63-.63h2.386c.346 0 .627.285.627.63 0 .349-.281.63-.63.63H17.61v1.125h1.755zm-3.855 3.016c0 .27-.174.51-.432.596-.064.021-.133.031-.199.031-.211 0-.391-.09-.51-.25l-2.443-3.317v2.94c0 .344-.279.629-.631.629-.346 0-.626-.285-.626-.629V8.108c0-.27.173-.51.43-.595.06-.023.136-.033.194-.033.195 0 .375.104.495.254l2.462 3.33V8.108c0-.345.282-.63.63-.63.345 0 .63.285.63.63v4.771zm-5.741 0c0 .344-.282.629-.631.629-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.63-.63.346 0 .628.285.628.63v4.771zm-2.466.629H4.917c-.345 0-.63-.285-.63-.629V8.108c0-.345.285-.63.63-.63.348 0 .63.285.63.63v4.141h1.756c.348 0 .629.283.629.63 0 .344-.282.629-.629.629M24 10.314C24 4.943 18.615.572 12 .572S0 4.943 0 10.314c0 4.811 4.27 8.842 10.035 9.608.391.082.923.258 1.058.59.12.301.079.766.038 1.08l-.164 1.02c-.045.301-.24 1.186 1.049.645 1.291-.539 6.916-4.078 9.436-6.975C23.176 14.393 24 12.458 24 10.314"/></svg>
                LINEでログイン
            </a>
            <p class="text-[11px] mt-3 text-center" style="color: var(--color-ink-soft);">ログインは無料です</p>
        </div>

        {{-- スポット登録ボタン --}}
        <a href="{{ route('spots.create') }}"
           class="flex items-center justify-center gap-2 w-full text-white font-medium py-4 active:scale-[0.98] transition-all text-sm" style="background: var(--color-coral); border-radius: var(--radius-pill);">
            まずはスポットを登録してみる
        </a>
    </div>
</div>
@endguest

@auth
<div class="min-h-screen pb-24" style="background: var(--color-cream);">

    {{-- Profile Header --}}
    <div class="px-5 pt-5 pb-4" style="background: var(--color-white); border-bottom: 0.5px solid var(--color-border);">
        <div class="flex items-center gap-3.5">
            <div class="w-14 h-14 rounded-2xl overflow-hidden flex-shrink-0">
                @if($user->display_avatar)
                    <img src="{{ $user->display_avatar }}" alt="" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center" style="background: var(--color-coral);">
                        <span class="text-white text-lg font-medium">{{ mb_substr($user->name, 0, 1) }}</span>
                    </div>
                @endif
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-1.5">
                    <h2 class="text-base font-serif truncate" style="color: var(--color-ink);">{{ $user->name }}</h2>
                    @if($user->isAmbassador())
                        <span class="badge badge-amber text-[8px]" style="padding:1px 6px;">アンバサダー</span>
                    @endif
                </div>
                <p class="text-[11px]" style="color: var(--color-ink-soft);">{{ $user->bio ?? 'まだ自己紹介がありません' }}</p>
            </div>
        </div>

        {{-- Stats --}}
        <div class="flex gap-2 mt-3.5">
            <div class="flex-1 rounded-xl py-2.5 text-center" style="background: var(--color-coral-light);">
                <p class="text-lg font-medium" style="color: var(--color-coral);">{{ $spotCount }}</p>
                <p class="text-[9px] font-medium" style="color: var(--color-coral-mid);">投稿</p>
            </div>
            <div class="flex-1 rounded-xl py-2.5 text-center" style="background: var(--color-gold-light);">
                <p class="text-lg font-medium" style="color: var(--color-gold);">{{ $questionCount }}</p>
                <p class="text-[9px] font-medium" style="color: var(--color-gold);">質問</p>
            </div>
            <div class="flex-1 rounded-xl py-2.5 text-center" style="background: var(--color-sage-light);">
                <p class="text-lg font-medium" style="color: var(--color-sage);">{{ $commentCount }}</p>
                <p class="text-[9px] font-medium" style="color: var(--color-sage);">回答</p>
            </div>
        </div>
    </div>

    <div class="px-4 pt-4 space-y-4">

        {{-- ===== My School Section ===== --}}
        <div class="card p-4">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: var(--color-sage-light);">
                    <span class="text-sm">🏫</span>
                </div>
                <h3 class="text-sm font-serif" style="color: var(--color-ink);">マイ・スクール</h3>
                @if($user->mySchool)
                    <span class="badge badge-green ml-auto">設定済み</span>
                @endif
            </div>

            @if($user->mySchool)
                <div class="flex items-center gap-2.5 rounded-xl px-3 py-2.5 mb-3" style="background: var(--color-sage-light); border: 0.5px solid var(--color-border);">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" style="background: var(--color-white);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="#5D8F7C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-medium" style="color: var(--color-ink);">{{ $user->mySchool->name }}</p>
                        <p class="text-[10px] truncate" style="color: var(--color-ink-soft);">{{ $user->mySchool->address }}</p>
                    </div>
                </div>
            @endif

            <form action="{{ route('mypage.updateSchool') }}" method="POST" class="flex gap-2">
                @csrf
                <select name="school_id" class="input-field flex-1 text-sm">
                    <option value="">学校を選択…</option>
                    @foreach($schools as $school)
                        <option value="{{ $school->id }}" {{ $user->my_school_id == $school->id ? 'selected' : '' }}>
                            {{ $school->name }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="text-white text-xs px-4 font-medium active:scale-95 transition-all" style="background: var(--color-coral); border-radius: var(--radius-pill);">
                    設定
                </button>
            </form>
            <p class="text-[10px] mt-2 px-0.5" style="color: var(--color-ink-soft);">校門から各教室への徒歩分数が自動表示されます</p>
        </div>

        {{-- ===== Favorites Section ===== --}}
        <div class="card p-4">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: var(--color-gold-light);">
                    <span class="text-sm">💛</span>
                </div>
                <h3 class="text-sm font-serif" style="color: var(--color-ink);">お気に入り教室</h3>
                <span class="badge badge-gray ml-auto">{{ $favorites->count() }}件</span>
            </div>

            @if($favorites->count())
            <div class="space-y-2.5">
                @foreach($favorites as $spot)
                <div class="flex gap-3 rounded-xl p-3 active:scale-[0.99] transition-transform" style="background: var(--color-cream);">
                    {{-- Thumbnail --}}
                    <div class="w-16 h-16 rounded-xl overflow-hidden flex-shrink-0" style="background: var(--color-cream-mid);">
                        @if($spot->image_path)
                            <img src="{{ str_starts_with($spot->image_path, 'http') ? $spot->image_path : asset('storage/' . $spot->image_path) }}"
                                 alt="" class="w-full h-full object-cover" loading="lazy">
                        @endif
                    </div>

                    {{-- Info --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-1">
                            <h4 class="text-sm font-medium truncate" style="color: var(--color-ink);">{{ $spot->title }}</h4>
                            <form action="{{ route('spots.favorite', $spot) }}" method="POST" class="flex-shrink-0">
                                @csrf
                                <button type="submit" class="active:scale-90 transition-all p-1" style="color: var(--color-gold);" title="お気に入り解除">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                                </button>
                            </form>
                        </div>
                        <p class="text-[10px]" style="color: var(--color-ink-soft);">{{ $spot->category }}</p>

                        {{-- Badges --}}
                        <div class="flex flex-wrap gap-1 mt-1.5">
                            @if($user->mySchool && isset($spot->walk_minutes))
                                <span class="badge badge-green">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="5" r="2"/><path d="M10 22V12l-3-3m6 0l-1 3m1-3h3l3 3"/></svg>
                                    {{ $spot->walk_minutes }}分
                                </span>
                            @endif
                            @if(!$spot->has_parent_duty)
                                <span class="badge badge-purple">当番なし</span>
                            @endif
                            @if($spot->transfer_available)
                                <span class="badge badge-blue">振替OK</span>
                            @endif
                            @if($spot->monthly_fee_range)
                                <span class="badge badge-amber">{{ $spot->monthly_fee_range }}</span>
                            @endif
                        </div>

                        {{-- Rating --}}
                        @if($spot->reviews->count())
                        @php $avg = round($spot->reviews->avg('satisfaction'), 1); @endphp
                        <div class="flex items-center gap-1 mt-1.5">
                            <div class="flex">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg style="color: {{ $i <= round($avg) ? 'var(--color-gold)' : 'var(--color-cream-mid)' }};" xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="currentColor" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                @endfor
                            </div>
                            <span class="text-[10px] font-medium" style="color: var(--color-ink-mid);">{{ $avg }}</span>
                            <span class="text-[10px]" style="color: var(--color-ink-soft);">({{ $spot->reviews->count() }})</span>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="rounded-xl p-6 text-center" style="background: var(--color-cream);">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-2" style="background: var(--color-white); border: 0.5px solid var(--color-border);">
                    <span class="text-xl">💛</span>
                </div>
                <p class="text-sm font-medium" style="color: var(--color-ink-soft);">まだお気に入りがありません</p>
                <p class="text-[10px] mt-1" style="color: var(--color-ink-soft);">マップから教室をお気に入りに追加しよう</p>
                <a href="{{ route('spots.index') }}" class="inline-block mt-3 text-white text-xs font-medium px-4 py-2 active:scale-95 transition-all" style="background: var(--color-coral); border-radius: var(--radius-pill);">
                    教室をさがす
                </a>
            </div>
            @endif
        </div>

        {{-- ===== My Spots Section ===== --}}
        @if($mySpots->count())
        <div class="card p-4">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: var(--color-coral-light);">
                    <span class="text-sm">📍</span>
                </div>
                <h3 class="text-sm font-serif" style="color: var(--color-ink);">自分の投稿</h3>
                <span class="badge badge-gray ml-auto">{{ $spotCount }}件</span>
            </div>
            <div class="space-y-2">
                @foreach($mySpots as $mySpot)
                <a href="{{ route('spots.index', ['focus' => $mySpot->id]) }}" class="flex items-center gap-3 rounded-xl p-2.5 active:scale-[0.99] transition-transform" style="background: var(--color-cream);">
                    <div class="w-10 h-10 rounded-lg overflow-hidden flex-shrink-0" style="background: var(--color-cream-mid);">
                        @if($mySpot->image_path)
                            <img src="{{ str_starts_with($mySpot->image_path, 'http') ? $mySpot->image_path : asset('storage/' . $mySpot->image_path) }}" alt="" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-sm">📍</div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[13px] font-medium truncate" style="color: var(--color-ink);">{{ $mySpot->title }}</p>
                        <p class="text-[10px]" style="color: var(--color-ink-soft);">{{ $mySpot->category }} · {{ $mySpot->created_at->diffForHumans() }}</p>
                    </div>
                    <svg style="color: var(--color-ink-soft);" xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        {{-- v2: Ambassador Section (ambassadorのみ表示)
        @if($user->isAmbassador())
        <div class="card p-4">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: var(--color-gold-light);">
                    <span class="text-sm">⭐</span>
                </div>
                <h3 class="text-sm font-serif" style="color: var(--color-ink);">アンバサダー管理</h3>
            </div>
            <div class="space-y-2">
                <a href="{{ route('ambassador.dashboard') }}">ダッシュボード</a>
                <a href="{{ route('ambassador.create') }}">通信を投稿</a>
            </div>
        </div>
        @endif
        --}}

        {{-- v2: Nearby Ambassador Voices
        @if($user->mySchool)
        <div class="card p-4">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: var(--color-sage-light);">
                    <span class="text-sm">📡</span>
                </div>
                <h3 class="text-sm font-serif" style="color: var(--color-ink);">ご近所ボイス</h3>
                <span class="text-[10px] ml-auto" style="color: var(--color-ink-soft);">{{ $user->mySchool->name }}周辺</span>
            </div>

            @if($nearbyPosts->count())
            <div class="space-y-2.5">
                @foreach($nearbyPosts as $post)
                <div class="flex gap-3 rounded-xl p-2.5" style="background: var(--color-cream); border: 0.5px solid var(--color-border);">
                    <div class="rounded-lg overflow-hidden flex-shrink-0" style="width:52px;height:52px;background: var(--color-gold-light);">
                        <img src="{{ str_starts_with($post->photo_path, 'http') ? $post->photo_path : asset('storage/' . $post->photo_path) }}"
                             alt="" class="w-full h-full object-cover" loading="lazy">
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-1.5 mb-0.5">
                            <span class="text-[11px] font-medium truncate" style="color: var(--color-ink);">{{ $post->user->organization_name ?? $post->user->name }}</span>
                            <span class="badge badge-amber text-[8px] flex-shrink-0" style="padding:1px 5px;">公式</span>
                        </div>
                        <p class="text-[11px] leading-relaxed line-clamp-2" style="color: var(--color-ink-mid);">{{ $post->message }}</p>
                        <div class="flex items-center gap-2 mt-1">
                            @if($post->spot)
                                <span class="text-[10px] font-medium" style="color: var(--color-coral);">{{ $post->spot->title }}</span>
                                @if(isset($post->distance_meters))
                                    <span class="text-[10px]" style="color: var(--color-ink-soft);">|</span>
                                    <span class="badge badge-green" style="padding:1px 5px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="9" height="9" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="5" r="2"/><path d="M10 22V12l-3-3m6 0l-1 3m1-3h3l3 3"/></svg>
                                        {{ (int) ceil($post->distance_meters / 80) }}分
                                    </span>
                                @endif
                            @endif
                            <span class="text-[10px] ml-auto" style="color: var(--color-ink-soft);">{{ $post->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <a href="{{ route('ambassador.index') }}" class="flex items-center justify-center gap-1 text-xs font-medium mt-3 py-2 active:scale-95 transition-transform" style="color: var(--color-coral);">
                すべてのアンバサダーを見る
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            </a>
            @else
            <div class="rounded-xl p-5 text-center" style="background: var(--color-cream);">
                <p class="text-sm font-medium" style="color: var(--color-ink-soft);">1.5km圏内の投稿はまだありません</p>
                <p class="text-[10px] mt-1" style="color: var(--color-ink-soft);">範囲を広げると新しい情報が見つかるかも</p>
            </div>
            @endif
        </div>
        @endif
        --}}

        {{-- ===== Quick Actions ===== --}}
        <div class="card overflow-hidden">
            <a href="{{ route('spots.create') }}" class="flex items-center gap-3 px-4 py-3.5 transition-colors">
                <div class="w-9 h-9 rounded-lg flex items-center justify-center" style="background: var(--color-coral-light);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="#E8704A" stroke-width="2" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                </div>
                <span class="text-sm font-medium flex-1" style="color: var(--color-ink);">スポットを登録</span>
                <svg style="color: var(--color-ink-soft);" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            </a>
            {{-- v2: みんなに質問リンク
            <a href="{{ route('questions.create') }}" class="flex items-center gap-3 px-4 py-3.5 transition-colors">
                <div class="w-9 h-9 rounded-lg flex items-center justify-center" style="background: var(--color-gold-light);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="#C9973A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                </div>
                <span class="text-sm font-medium flex-1" style="color: var(--color-ink);">みんなに質問</span>
                <svg style="color: var(--color-ink-soft);" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            </a>
            --}}
        </div>

        {{-- App Info --}}
        <div class="text-center py-4">
            <span class="text-[10px] font-serif opacity-50" style="color: var(--color-ink-soft);">みっけ</span>
            <p class="text-[10px] mt-0.5" style="color: var(--color-ink-soft); opacity: 0.5;">子どもの「好き」は、もうこの街に存在している。</p>
        </div>

    </div>
</div>

<style>
.line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
</style>
@endauth
@endsection
