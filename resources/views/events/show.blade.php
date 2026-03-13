@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto bg-white min-h-screen pb-20">
    {{-- Header --}}
    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 sticky top-0 bg-white/95 backdrop-blur-xl z-[2000]">
        <a href="{{ route('events.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
        </a>
        <h1 class="text-base font-black text-gray-900">イベント詳細</h1>
        <div class="w-6"></div>
    </div>

    <div class="p-5 space-y-5">
        {{-- Date & Title --}}
        <div class="flex gap-4 items-start">
            <div class="flex-shrink-0 w-16 h-[72px] bg-brand-500 rounded-2xl flex flex-col items-center justify-center text-white shadow-sm">
                <span class="text-[10px] font-bold opacity-80 uppercase">{{ $event->event_date->format('M') }}</span>
                <span class="text-2xl font-black leading-none">{{ $event->event_date->format('d') }}</span>
                <span class="text-[10px] font-bold opacity-80">{{ $event->event_date->format('Y') }}</span>
            </div>
            <div class="flex-1">
                <h2 class="text-xl font-black text-gray-900 leading-tight mb-2">{{ $event->title }}</h2>
                <div class="flex flex-wrap gap-1.5">
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-lg bg-brand-50 text-brand-700 border border-brand-100">
                        {{ $event->organizer_name }}
                    </span>
                    @if($event->target_age)
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-lg bg-purple-50 text-purple-600 border border-purple-100">
                        {{ $event->target_age }}
                    </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Image --}}
        @if($event->image_path)
        <img src="{{ asset('storage/' . $event->image_path) }}" alt="{{ $event->title }}" class="w-full rounded-2xl shadow-sm">
        @endif

        {{-- Info cards --}}
        <div class="grid grid-cols-2 gap-2">
            <div class="bg-gray-50 rounded-2xl p-3.5">
                <div class="flex items-center gap-2 mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="#6b7280" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 1 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    <span class="text-[10px] font-bold text-gray-400">場所</span>
                </div>
                <p class="text-xs font-bold text-gray-800">{{ $event->location_name }}</p>
            </div>
            <div class="bg-gray-50 rounded-2xl p-3.5">
                <div class="flex items-center gap-2 mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="#6b7280" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    <span class="text-[10px] font-bold text-gray-400">日時</span>
                </div>
                <p class="text-xs font-bold text-gray-800">{{ $event->event_date->format('Y/m/d') }}</p>
            </div>
        </div>

        {{-- Description --}}
        <div class="bg-gray-50 rounded-2xl p-4">
            <p class="text-[13px] text-gray-600 leading-relaxed font-medium">{{ $event->description }}</p>
        </div>

        {{-- External link --}}
        @if($event->link_url)
        <a href="{{ $event->link_url }}" target="_blank" rel="noopener"
           class="flex items-center justify-center gap-2 bg-blue-50 text-blue-600 font-bold text-sm py-3.5 rounded-2xl border border-blue-100 active:scale-[0.98] transition-transform">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
            詳しくはこちら
        </a>
        @endif

        {{-- Back --}}
        <a href="{{ route('events.index') }}"
           class="flex items-center justify-center gap-2 w-full bg-gray-900 text-white py-3.5 rounded-2xl font-bold text-sm active:scale-[0.98] transition-transform">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
            一覧に戻る
        </a>
    </div>
</div>
@endsection
