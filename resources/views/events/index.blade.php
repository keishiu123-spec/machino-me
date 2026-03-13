@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto bg-white min-h-screen pb-20">
    <header class="px-5 py-4 bg-white/95 backdrop-blur-xl sticky top-0 z-50 border-b border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-lg font-black text-gray-900">イベント</h1>
                <p class="text-[11px] text-gray-400 font-medium">世田谷エリアの子育てイベント</p>
            </div>
        </div>
    </header>

    {{-- Category chips --}}
    <div class="px-5 py-3 flex gap-2 overflow-x-auto no-scrollbar border-b border-gray-50">
        @foreach(['すべて','体験','ワークショップ','マーケット','季節行事','スポーツ'] as $i => $cat)
        <button class="flex-shrink-0 px-3.5 py-1.5 rounded-full text-xs font-bold border transition-all {{ $i === 0 ? 'bg-gray-900 text-white border-gray-900' : 'bg-white text-gray-500 border-gray-200' }}">
            {{ $cat }}
        </button>
        @endforeach
    </div>

    <div class="divide-y divide-gray-50">
        @forelse($events as $event)
        <a href="{{ route('events.show', ['id' => $event->id]) }}" class="block">
            <article class="p-5 active:bg-gray-50 transition-colors">
                <div class="flex gap-4">
                    {{-- Date card --}}
                    <div class="flex-shrink-0 w-14 h-16 bg-brand-500 rounded-2xl flex flex-col items-center justify-center text-white shadow-sm">
                        <span class="text-[10px] font-bold opacity-80 uppercase">{{ $event->event_date->format('M') }}</span>
                        <span class="text-xl font-black leading-none">{{ $event->event_date->format('d') }}</span>
                    </div>

                    <div class="flex-1 min-w-0">
                        {{-- Badges --}}
                        <div class="flex items-center gap-2 mb-1.5">
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-lg bg-brand-50 text-brand-700 border border-brand-100">
                                {{ $event->organizer_name }}
                            </span>
                            @if($event->target_age)
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-lg bg-purple-50 text-purple-600 border border-purple-100">
                                {{ $event->target_age }}
                            </span>
                            @endif
                        </div>

                        <h3 class="font-bold text-[15px] text-gray-900 leading-snug mb-1">{{ $event->title }}</h3>

                        {{-- Location --}}
                        <div class="flex items-center gap-1 mb-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" stroke="#9ca3af" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 1 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            <span class="text-[11px] text-gray-400 font-medium">{{ $event->location_name }}</span>
                        </div>

                        <p class="text-xs text-gray-500 leading-relaxed line-clamp-2 font-medium">{{ $event->description }}</p>
                    </div>

                    {{-- Arrow --}}
                    <div class="flex items-center flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="#d1d5db" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                    </div>
                </div>
            </article>
        </a>
        @empty
        <div class="py-24 text-center">
            <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" stroke="#d1d5db" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            </div>
            <p class="text-sm text-gray-400 font-medium">現在、予定されているイベントはありません</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
