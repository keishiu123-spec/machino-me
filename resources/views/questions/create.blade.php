@extends('layouts.app')

@section('content')
<div class="bg-white min-h-screen pb-24">

    {{-- Header --}}
    <div class="flex items-center justify-between px-5 py-3 border-b border-gray-100/60 sticky top-[41px] bg-white/95 backdrop-blur-xl z-40">
        <a href="{{ route('questions.index') }}" class="w-10 h-10 bg-gray-50 rounded-xl flex items-center justify-center text-gray-400 hover:text-gray-600 active:scale-95 transition-all">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
        </a>
        <h1 class="text-sm font-black text-gray-900">習い事について相談する</h1>
        <div class="w-10"></div>
    </div>

    <form action="{{ route('questions.store') }}" method="POST" enctype="multipart/form-data" class="px-5 pt-5 pb-6 space-y-6">
        @csrf

        {{-- Category --}}
        <div>
            <label class="text-xs font-bold text-gray-500 mb-2.5 block">何について知りたい？</label>
            <div class="grid grid-cols-3 gap-2">
                @php
                    $cats = [
                        ['val'=>'月謝・費用', 'emoji'=>'💰', 'color'=>'amber'],
                        ['val'=>'先生・指導', 'emoji'=>'👩‍🏫', 'color'=>'blue'],
                        ['val'=>'当番・親の負担', 'emoji'=>'📋', 'color'=>'red'],
                        ['val'=>'始め時・年齢', 'emoji'=>'🎒', 'color'=>'purple'],
                        ['val'=>'教室選び', 'emoji'=>'🔍', 'color'=>'brand'],
                        ['val'=>'送迎・スケジュール', 'emoji'=>'🚗', 'color'=>'gray'],
                    ];
                @endphp
                @foreach($cats as $i => $cat)
                <label class="cat-radio cursor-pointer">
                    <input type="radio" name="category" value="{{ $cat['val'] }}" class="sr-only" {{ $i===0?'checked':'' }}>
                    <div class="border-2 border-gray-100 rounded-xl p-3 text-center transition-all active:scale-95">
                        <div class="text-xl mb-1">{{ $cat['emoji'] }}</div>
                        <span class="text-[10px] font-bold text-gray-600 leading-tight block">{{ $cat['val'] }}</span>
                    </div>
                </label>
                @endforeach
            </div>
        </div>

        {{-- Title --}}
        <div>
            <label class="text-xs font-bold text-gray-500 mb-2 block">どんなことが知りたい？</label>
            <input type="text" name="title" value="{{ old('title') }}"
                placeholder="例：スイミング教室の月謝相場ってどのくらい？"
                class="w-full bg-gray-50 rounded-xl px-4 py-3.5 text-sm font-medium outline-none border border-gray-100 focus:border-brand-400 focus:ring-2 focus:ring-brand-100 transition-all placeholder-gray-300" required>
        </div>

        {{-- Note --}}
        <div>
            <label class="text-xs font-bold text-gray-500 mb-2 block">くわしく教えて</label>
            <textarea name="note" rows="4" placeholder="背景や条件を書くと、より的確な回答がもらえます。&#10;例：小2の息子にスイミングを習わせたいのですが、弦巻エリアの相場感が分かりません…"
                class="w-full bg-gray-50 rounded-xl px-4 py-3.5 text-sm font-medium outline-none border border-gray-100 focus:border-brand-400 focus:ring-2 focus:ring-brand-100 transition-all resize-none leading-relaxed placeholder-gray-300" required>{{ old('note') }}</textarea>
        </div>

        {{-- Spot Selection (optional) --}}
        <div>
            <label class="text-xs font-bold text-gray-500 mb-2 block">関連する教室 <span class="text-gray-300 font-medium">（任意）</span></label>
            <select name="spot_id" class="w-full bg-gray-50 rounded-xl px-4 py-3.5 text-sm font-medium outline-none border border-gray-100 focus:border-brand-400 focus:ring-2 focus:ring-brand-100 transition-all text-gray-700">
                <option value="">教室を選択しない</option>
                @foreach($spots as $spot)
                    <option value="{{ $spot->id }}" {{ old('spot_id') == $spot->id ? 'selected' : '' }}>{{ $spot->title }}（{{ $spot->category }}）</option>
                @endforeach
            </select>
            <p class="text-[10px] text-gray-300 mt-1 px-1">特定の教室について聞きたい場合に選択</p>
        </div>

        {{-- Target Age --}}
        <div>
            <label class="text-xs font-bold text-gray-500 mb-2 block">お子さんの年齢 <span class="text-gray-300 font-medium">（任意）</span></label>
            <div class="flex flex-wrap gap-2">
                @php $ages = ['未就園児', '年少', '年中', '年長', '小1', '小2', '小3', '小4', '小5', '小6', '中学生']; @endphp
                @foreach($ages as $age)
                <label class="age-radio cursor-pointer">
                    <input type="radio" name="target_age" value="{{ $age }}" class="sr-only" {{ old('target_age') === $age ? 'checked' : '' }}>
                    <span class="inline-block px-3 py-1.5 rounded-lg text-[11px] font-bold border border-gray-100 text-gray-500 transition-all active:scale-95">{{ $age }}</span>
                </label>
                @endforeach
            </div>
        </div>

        {{-- Photo --}}
        <div>
            <label class="text-xs font-bold text-gray-500 mb-2 block">写真を追加 <span class="text-gray-300 font-medium">（任意）</span></label>
            <label class="flex items-center gap-3 bg-gray-50 rounded-xl px-4 py-3 border border-gray-100 cursor-pointer hover:border-brand-300 transition-all group">
                <div class="w-9 h-9 bg-white rounded-lg flex items-center justify-center group-hover:bg-brand-50 transition-colors">
                    <svg class="text-gray-300 group-hover:text-brand-400 transition-colors" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-400" id="file-label">タップして写真を選択</p>
                </div>
                <input type="file" name="image" class="hidden" accept="image/*"
                    onchange="var l=document.getElementById('file-label'); if(this.files[0]){l.textContent=this.files[0].name; l.classList.remove('text-gray-400'); l.classList.add('text-brand-600');} else {l.textContent='タップして写真を選択';}">
            </label>
        </div>

        {{-- Errors --}}
        @if ($errors->any())
        <div class="p-3.5 bg-red-50 border border-red-100 rounded-xl">
            <ul class="text-xs text-red-600 font-medium space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Submit --}}
        <button type="submit"
            class="w-full bg-brand-500 hover:bg-brand-600 text-white font-bold py-4 rounded-2xl shadow-[0_8px_24px_rgba(34,197,94,0.3)] active:scale-[0.98] transition-all text-sm flex items-center justify-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            相談を投稿する
        </button>
    </form>
</div>

<style>
    .cat-radio:has(input:checked) div {
        border-color: #22c55e;
        background: #f0fdf4;
    }
    .age-radio:has(input:checked) span {
        border-color: #a855f7;
        background: #faf5ff;
        color: #7c3aed;
    }
</style>
@endsection
