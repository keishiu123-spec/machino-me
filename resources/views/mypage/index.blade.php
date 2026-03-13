@extends('layouts.app')

@section('content')
<div class="bg-surface-50 min-h-screen pb-24">

    {{-- Profile Header --}}
    <div class="bg-white px-5 pt-5 pb-4 border-b border-gray-100/40">
        <div class="flex items-center gap-3.5">
            <div class="w-14 h-14 rounded-2xl overflow-hidden flex-shrink-0 shadow-sm">
                @if($user->avatar_url)
                    <img src="{{ $user->avatar_url }}" alt="" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full bg-gradient-to-br from-brand-300 to-brand-500 flex items-center justify-center">
                        <span class="text-white text-lg font-black">{{ mb_substr($user->name, 0, 1) }}</span>
                    </div>
                @endif
            </div>
            <div class="flex-1 min-w-0">
                <h2 class="text-base font-black text-gray-900 truncate">{{ $user->name }}</h2>
                <p class="text-[11px] text-gray-400 font-medium">{{ $user->nickname ?? 'メンバー' }}</p>
            </div>
        </div>

        {{-- Stats --}}
        <div class="flex gap-2 mt-3.5">
            <div class="flex-1 bg-brand-50/70 rounded-xl py-2.5 text-center">
                <p class="text-lg font-black text-brand-600">{{ $spotCount }}</p>
                <p class="text-[9px] font-bold text-brand-500/60">投稿</p>
            </div>
            <div class="flex-1 bg-amber-50/70 rounded-xl py-2.5 text-center">
                <p class="text-lg font-black text-amber-600">{{ $questionCount }}</p>
                <p class="text-[9px] font-bold text-amber-500/60">質問</p>
            </div>
            <div class="flex-1 bg-blue-50/70 rounded-xl py-2.5 text-center">
                <p class="text-lg font-black text-blue-600">{{ $commentCount }}</p>
                <p class="text-[9px] font-bold text-blue-500/60">回答</p>
            </div>
        </div>
    </div>

    <div class="px-4 pt-4 space-y-4">

        {{-- ===== My School Section ===== --}}
        <div class="card p-4">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-8 h-8 bg-gradient-to-br from-brand-100 to-emerald-100 rounded-lg flex items-center justify-center">
                    <span class="text-sm">🏫</span>
                </div>
                <h3 class="text-sm font-black text-gray-900">マイ・スクール</h3>
                @if($user->mySchool)
                    <span class="badge badge-green ml-auto">設定済み</span>
                @endif
            </div>

            @if($user->mySchool)
                <div class="flex items-center gap-2.5 bg-brand-50/60 rounded-xl px-3 py-2.5 mb-3 border border-brand-100/40">
                    <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center shadow-sm flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="#16a34a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-bold text-gray-900">{{ $user->mySchool->name }}</p>
                        <p class="text-[10px] text-gray-400 truncate">{{ $user->mySchool->address }}</p>
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
                <button type="submit" class="bg-gradient-to-r from-brand-500 to-brand-600 text-white font-bold text-xs px-4 rounded-xl active:scale-95 transition-all shadow-sm">
                    設定
                </button>
            </form>
            <p class="text-[10px] text-gray-300 mt-2 px-0.5">校門から各教室への徒歩分数が自動表示されます</p>
        </div>

        {{-- ===== Favorites Section ===== --}}
        <div class="card p-4">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-8 h-8 bg-gradient-to-br from-amber-100 to-orange-100 rounded-lg flex items-center justify-center">
                    <span class="text-sm">💛</span>
                </div>
                <h3 class="text-sm font-black text-gray-900">お気に入り教室</h3>
                <span class="badge badge-gray ml-auto">{{ $favorites->count() }}件</span>
            </div>

            @if($favorites->count())
            <div class="space-y-2.5">
                @foreach($favorites as $spot)
                <div class="flex gap-3 bg-surface-50 rounded-xl p-3 active:scale-[0.99] transition-transform">
                    {{-- Thumbnail --}}
                    <div class="w-16 h-16 rounded-xl overflow-hidden flex-shrink-0 bg-gray-100 shadow-sm">
                        @if($spot->image_path)
                            <img src="{{ str_starts_with($spot->image_path, 'http') ? $spot->image_path : asset('storage/' . $spot->image_path) }}"
                                 alt="" class="w-full h-full object-cover" loading="lazy">
                        @endif
                    </div>

                    {{-- Info --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-1">
                            <h4 class="text-sm font-bold text-gray-900 truncate">{{ $spot->title }}</h4>
                            <form action="{{ route('spots.favorite', $spot) }}" method="POST" class="flex-shrink-0">
                                @csrf
                                <button type="submit" class="text-amber-400 hover:text-amber-500 active:scale-90 transition-all p-1" title="お気に入り解除">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                                </button>
                            </form>
                        </div>
                        <p class="text-[10px] text-gray-400 font-medium">{{ $spot->category }}</p>

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
                                    <svg class="{{ $i <= round($avg) ? 'text-amber-400' : 'text-gray-200' }}" xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="currentColor" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                @endfor
                            </div>
                            <span class="text-[10px] font-bold text-gray-500">{{ $avg }}</span>
                            <span class="text-[10px] text-gray-300">({{ $spot->reviews->count() }})</span>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="bg-surface-50 rounded-xl p-6 text-center">
                <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center mx-auto mb-2 shadow-sm">
                    <span class="text-xl">💛</span>
                </div>
                <p class="text-sm font-bold text-gray-400">まだお気に入りがありません</p>
                <p class="text-[10px] text-gray-300 mt-1">マップから教室をお気に入りに追加しよう</p>
                <a href="{{ route('spots.index') }}" class="inline-block mt-3 bg-gradient-to-r from-brand-500 to-brand-600 text-white text-xs font-bold px-4 py-2 rounded-xl active:scale-95 transition-all shadow-sm">
                    教室をさがす
                </a>
            </div>
            @endif
        </div>

        {{-- ===== Nearby Ambassador Voices ===== --}}
        @if($user->mySchool)
        <div class="card p-4">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-8 h-8 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-lg flex items-center justify-center">
                    <span class="text-sm">📡</span>
                </div>
                <h3 class="text-sm font-black text-gray-900">ご近所ボイス</h3>
                <span class="text-[10px] text-gray-400 font-medium ml-auto">{{ $user->mySchool->name }}周辺</span>
            </div>

            @if($nearbyPosts->count())
            <div class="space-y-2.5">
                @foreach($nearbyPosts as $post)
                <div class="flex gap-3 bg-surface-50 rounded-xl p-2.5 border border-amber-100/40">
                    <div class="w-13 h-13 rounded-lg overflow-hidden flex-shrink-0 bg-amber-50" style="width:52px;height:52px;">
                        <img src="{{ str_starts_with($post->photo_path, 'http') ? $post->photo_path : asset('storage/' . $post->photo_path) }}"
                             alt="" class="w-full h-full object-cover" loading="lazy">
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-1.5 mb-0.5">
                            <span class="text-[11px] font-bold text-gray-900 truncate">{{ $post->user->organization_name ?? $post->user->name }}</span>
                            <span class="badge badge-amber text-[8px] flex-shrink-0" style="padding:1px 5px;">公式</span>
                        </div>
                        <p class="text-[11px] text-gray-500 leading-relaxed line-clamp-2">{{ $post->message }}</p>
                        <div class="flex items-center gap-2 mt-1">
                            @if($post->spot)
                                <span class="text-[10px] text-brand-600 font-bold">{{ $post->spot->title }}</span>
                                @if(isset($post->distance_meters))
                                    <span class="text-[10px] text-gray-300">|</span>
                                    <span class="badge badge-green" style="padding:1px 5px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="9" height="9" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="5" r="2"/><path d="M10 22V12l-3-3m6 0l-1 3m1-3h3l3 3"/></svg>
                                        {{ (int) ceil($post->distance_meters / 80) }}分
                                    </span>
                                @endif
                            @endif
                            <span class="text-[10px] text-gray-300 ml-auto">{{ $post->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <a href="{{ route('ambassador.index') }}" class="flex items-center justify-center gap-1 text-xs font-bold text-amber-600 mt-3 py-2 active:scale-95 transition-transform">
                すべてのアンバサダーを見る
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            </a>
            @else
            <div class="bg-surface-50 rounded-xl p-5 text-center">
                <p class="text-sm font-bold text-gray-400">1.5km圏内の投稿はまだありません</p>
                <p class="text-[10px] text-gray-300 mt-1">範囲を広げると新しい情報が見つかるかも</p>
            </div>
            @endif
        </div>
        @endif

        {{-- ===== Quick Actions ===== --}}
        <div class="card overflow-hidden">
            <a href="{{ route('spots.create') }}" class="flex items-center gap-3 px-4 py-3.5 border-b border-gray-50 active:bg-gray-50 transition-colors">
                <div class="w-9 h-9 bg-brand-50 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="#16a34a" stroke-width="2" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                </div>
                <span class="text-sm font-bold text-gray-700 flex-1">スポットを登録</span>
                <svg class="text-gray-300" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            </a>
            <a href="{{ route('questions.create') }}" class="flex items-center gap-3 px-4 py-3.5 active:bg-gray-50 transition-colors">
                <div class="w-9 h-9 bg-amber-50 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="#d97706" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                </div>
                <span class="text-sm font-bold text-gray-700 flex-1">みんなに質問</span>
                <svg class="text-gray-300" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            </a>
        </div>

        {{-- App Info --}}
        <div class="text-center py-4">
            <div class="flex items-center justify-center gap-1.5 mb-1 opacity-50">
                <div class="w-4 h-4 bg-brand-500 rounded flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="9" height="9" fill="none" stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 11l3-3 2 2 4-4 3 3"/><circle cx="8" cy="4" r="1.5"/></svg>
                </div>
                <span class="text-[10px] font-black text-gray-400">KIDS COMPASS</span>
            </div>
            <p class="text-[10px] text-gray-300">地域の子育て情報をみんなでシェア</p>
        </div>

    </div>
</div>

<style>
.line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
</style>
@endsection
