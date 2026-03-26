@extends('layouts.app')

@section('content')
<div class="min-h-screen pb-24" style="background: var(--color-cream);">

    {{-- Header --}}
    <div style="background: var(--color-white); border-bottom: 0.5px solid var(--color-border);">
        <div class="flex items-center gap-3 px-4 py-3">
            <a href="{{ route('ambassador.show', $ambassador) }}" class="w-9 h-9 rounded-full flex items-center justify-center active:scale-90 transition-all" style="background: var(--color-cream);">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="#26304F" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
            </a>
            <div>
                <h1 class="text-sm font-serif" style="color: var(--color-ink);">体験を申し込む</h1>
                <p class="text-[10px] text-gray-400 font-medium">{{ $ambassador->organization_name ?? $ambassador->name }}</p>
            </div>
        </div>
    </div>

    <form action="{{ route('ambassador.trial.store', $ambassador) }}" method="POST" class="px-4 pt-4 space-y-4">
        @csrf

        {{-- Ambassador Info Card --}}
        <div class="card p-4 flex items-center gap-3">
            @if($ambassador->avatar_url)
                <img src="{{ $ambassador->avatar_url }}" alt="" class="w-12 h-12 rounded-xl object-cover">
            @else
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center">
                    <span class="text-white text-lg font-black">{{ mb_substr($ambassador->name, 0, 1) }}</span>
                </div>
            @endif
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-gray-900 truncate">{{ $ambassador->organization_name ?? $ambassador->name }}</p>
                <p class="text-[11px] text-gray-400">体験レッスンの申し込みフォーム</p>
            </div>
            <span class="badge badge-green">受付中</span>
        </div>

        {{-- Osagari Info --}}
        @if(isset($osagariPost) && $osagariPost)
        <div class="card p-4 border-2 border-brand-200 bg-brand-50/30">
            <div class="flex items-center gap-2 mb-2">
                <span class="text-lg">🎁</span>
                <span class="text-sm font-black text-brand-800">お下がり希望</span>
                <span class="badge badge-green">自動入力済</span>
            </div>
            <p class="text-[13px] text-gray-700 font-medium">{{ $osagariPost->osagari_item }}@if($osagariPost->osagari_size)（{{ $osagariPost->osagari_size }}）@endif</p>
        </div>
        @endif

        {{-- Spot Selection --}}
        <div class="card p-4">
            <label class="section-label mb-2 block">教室を選択</label>
            <select name="spot_id" required class="input-field text-sm">
                <option value="">選択してください</option>
                @foreach($ambassador->managedSpots as $spot)
                    <option value="{{ $spot->id }}" {{ (old('spot_id') ?? ($osagariPost->spot_id ?? '')) == $spot->id ? 'selected' : '' }}>{{ $spot->title }}</option>
                @endforeach
            </select>
            @error('spot_id')
                <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Parent/Child Info --}}
        <div class="card p-4 space-y-4">
            <div class="flex items-center gap-2 mb-1">
                <div class="w-7 h-7 bg-gradient-to-br from-brand-100 to-brand-200 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" stroke="#16a34a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </div>
                <span class="text-sm font-black text-gray-900">保護者・お子さま情報</span>
            </div>

            <div>
                <label class="text-[11px] font-bold text-gray-500 block mb-1">保護者のお名前 <span class="text-red-400">*</span></label>
                <input type="text" name="parent_name" value="{{ old('parent_name') }}" required placeholder="山田 太郎" class="input-field text-sm">
                @error('parent_name')
                    <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="text-[11px] font-bold text-gray-500 block mb-1">お子さまのお名前 <span class="text-red-400">*</span></label>
                <input type="text" name="child_name" value="{{ old('child_name') }}" required placeholder="山田 花子" class="input-field text-sm">
                @error('child_name')
                    <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="text-[11px] font-bold text-gray-500 block mb-1">お子さまの年齢・学年 <span class="text-red-400">*</span></label>
                <input type="text" name="child_age" value="{{ old('child_age') }}" required placeholder="小学2年生（8歳）" class="input-field text-sm">
                @error('child_age')
                    <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Contact Info --}}
        <div class="card p-4 space-y-4">
            <div class="flex items-center gap-2 mb-1">
                <div class="w-7 h-7 bg-gradient-to-br from-blue-100 to-blue-200 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                </div>
                <span class="text-sm font-black text-gray-900">連絡先</span>
            </div>

            <div>
                <label class="text-[11px] font-bold text-gray-500 block mb-1">電話番号 <span class="text-red-400">*</span></label>
                <input type="tel" name="phone" value="{{ old('phone') }}" required placeholder="090-1234-5678" class="input-field text-sm">
                @error('phone')
                    <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="text-[11px] font-bold text-gray-500 block mb-1">メールアドレス</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="example@mail.com" class="input-field text-sm">
                @error('email')
                    <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Note --}}
        <div class="card p-4">
            <label class="text-[11px] font-bold text-gray-500 block mb-1">備考・ご質問</label>
            @php
                $defaultNote = old('note');
                if (!$defaultNote && isset($osagariPost) && $osagariPost) {
                    $defaultNote = '【お下がり希望】' . $osagariPost->osagari_item;
                    if ($osagariPost->osagari_size) {
                        $defaultNote .= '（' . $osagariPost->osagari_size . '）';
                    }
                    $defaultNote .= ' を希望します。';
                }
            @endphp
            <textarea name="note" rows="3" placeholder="気になることがあれば…" class="input-field text-sm resize-none">{{ $defaultNote }}</textarea>
            @error('note')
                <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Privacy Notice --}}
        <div class="bg-amber-50/60 rounded-2xl px-4 py-3 border border-amber-100/50">
            <div class="flex items-start gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="#d97706" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mt-0.5 flex-shrink-0"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                <p class="text-[11px] text-amber-700 leading-relaxed font-medium">
                    入力された個人情報は、この教室の先生のみが閲覧できます。第三者に共有されることはありません。
                </p>
            </div>
        </div>

        {{-- Submit --}}
        <button type="submit"
            class="w-full bg-gradient-to-r from-brand-500 to-brand-600 text-white font-bold text-sm py-4 rounded-2xl shadow-[0_6px_20px_rgba(34,197,94,0.3)] active:scale-[0.98] transition-all">
            体験を申し込む
        </button>

        <p class="text-center text-[10px] text-gray-300 pb-4">先生から折り返しご連絡いたします</p>
    </form>
</div>
@endsection
