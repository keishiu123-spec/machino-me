@extends('layouts.app')

@section('content')
<div class="bg-surface-50 min-h-screen pb-24">

    {{-- Profile Header --}}
    <div class="relative bg-white">
        {{-- Cover --}}
        <div class="h-36 bg-gradient-to-br from-amber-200 via-orange-100 to-amber-50 overflow-hidden">
            @php $firstPost = $posts->first(); @endphp
            @if($firstPost)
                <img src="{{ str_starts_with($firstPost->photo_path, 'http') ? $firstPost->photo_path : asset('storage/' . $firstPost->photo_path) }}"
                     alt="" class="w-full h-full object-cover opacity-60">
            @endif
        </div>

        {{-- Back button --}}
        <a href="{{ route('ambassador.index') }}" class="absolute top-4 left-4 w-9 h-9 bg-white/80 backdrop-blur-sm rounded-full flex items-center justify-center shadow-sm active:scale-90 transition-all">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="#374151" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
        </a>

        {{-- Avatar --}}
        <div class="px-5 -mt-10 relative z-10">
            <div class="relative inline-block">
                @if($ambassador->avatar_url)
                    <img src="{{ $ambassador->avatar_url }}" alt="" class="w-20 h-20 rounded-2xl object-cover ring-4 ring-white shadow-lg">
                @else
                    <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center ring-4 ring-white shadow-lg">
                        <span class="text-white text-2xl font-black">{{ mb_substr($ambassador->name, 0, 1) }}</span>
                    </div>
                @endif
                <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-amber-400 rounded-full flex items-center justify-center border-[3px] border-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="white" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                </div>
            </div>
        </div>

        {{-- Name & Meta --}}
        <div class="px-5 pt-3 pb-4">
            <div class="flex items-center gap-2">
                <h1 class="text-xl font-black text-gray-900">{{ $ambassador->organization_name ?? $ambassador->name }}</h1>
                <span class="badge badge-amber">公式</span>
            </div>
            <p class="text-xs text-gray-400 font-medium mt-0.5">{{ $ambassador->name }}</p>

            @if($ambassador->bio)
            <p class="text-[13px] text-gray-500 leading-relaxed mt-3">{{ $ambassador->bio }}</p>
            @endif

            {{-- Managed Spots --}}
            @if($ambassador->managedSpots->count())
            <div class="flex gap-2 overflow-x-auto no-scrollbar mt-3 pb-1">
                @foreach($ambassador->managedSpots as $spot)
                <a href="{{ route('spots.index', ['focus' => $spot->id]) }}" class="flex items-center gap-2 bg-brand-50 rounded-xl px-3 py-2 flex-shrink-0 border border-brand-100/40 active:scale-95 transition-all">
                    @if($spot->image_path)
                        <img src="{{ $spot->image_path }}" alt="" class="w-8 h-8 rounded-lg object-cover">
                    @else
                        <div class="w-8 h-8 rounded-lg bg-brand-100 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="#16a34a" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 1 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        </div>
                    @endif
                    <div>
                        <p class="text-[11px] font-bold text-brand-800">{{ $spot->title }}</p>
                        <p class="text-[9px] text-brand-600">マップで見る</p>
                    </div>
                </a>
                @endforeach
            </div>
            @endif

            {{-- CTA Buttons --}}
            <div class="grid grid-cols-2 gap-2 mt-4">
                <a href="{{ route('ambassador.trial', $ambassador) }}"
                   class="flex items-center justify-center gap-1.5 bg-gradient-to-r from-brand-500 to-brand-600 text-white text-xs font-bold py-3 rounded-xl shadow-[0_4px_16px_rgba(34,197,94,0.25)] active:scale-95 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    体験を申し込む
                </a>
                <a href="#qa-section"
                   class="flex items-center justify-center gap-1.5 bg-white border-2 border-amber-200 text-amber-700 text-xs font-bold py-3 rounded-xl active:scale-95 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    先生に質問
                </a>
            </div>

            {{-- Stats --}}
            <div class="flex items-center gap-5 mt-4 pt-3 border-t border-gray-100/60">
                <div>
                    <span class="text-lg font-black text-gray-900">{{ $posts->total() }}</span>
                    <span class="text-[11px] font-bold text-gray-400 ml-0.5">投稿</span>
                </div>
                <div>
                    <span class="text-lg font-black text-gray-900">{{ $questions->count() }}</span>
                    <span class="text-[11px] font-bold text-gray-400 ml-0.5">Q&A</span>
                </div>
                @if($posts->first())
                <div class="ml-auto">
                    <span class="text-[11px] font-bold text-gray-400">最終更新 {{ $posts->first()->created_at->diffForHumans() }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Tab Navigation --}}
    <div class="sticky top-[41px] z-30 bg-white border-b border-gray-100/60">
        <div class="flex">
            <button onclick="switchTab('posts')" id="tab-posts" class="amb-tab active flex-1 py-3 text-xs font-bold text-center border-b-2 transition-all">通信</button>
            <button onclick="switchTab('qa')" id="tab-qa" class="amb-tab flex-1 py-3 text-xs font-bold text-center border-b-2 transition-all">Q&A <span class="badge badge-amber ml-1" style="font-size:9px;">{{ $questions->count() }}</span></button>
        </div>
    </div>

    {{-- ===== Posts Tab ===== --}}
    <div id="panel-posts" class="px-4 pt-4 space-y-4">
        @forelse($posts as $post)
        <article class="card card-elevated overflow-hidden fade-up" style="animation-delay: {{ $loop->index * 0.05 }}s;">
            <div class="relative bg-gray-100" style="aspect-ratio: 4/3;">
                <img src="{{ str_starts_with($post->photo_path, 'http') ? $post->photo_path : asset('storage/' . $post->photo_path) }}"
                     alt="" class="w-full h-full object-cover" loading="lazy">
                <div class="absolute top-3 left-3 flex gap-1.5">
                    @if($post->mood_tag)
                    <span class="badge badge-amber backdrop-blur-sm bg-white/90 text-gray-700">{{ $post->mood_tag }}</span>
                    @endif
                    @if($post->has_osagari)
                    <span class="badge backdrop-blur-sm bg-white/90 text-brand-700" style="background:rgba(220,252,231,0.9);">🎁 お下がり有</span>
                    @endif
                </div>
                <div class="absolute bottom-3 right-3">
                    <span class="bg-black/40 backdrop-blur-sm text-white text-[10px] font-medium px-2 py-0.5 rounded-md">{{ $post->created_at->format('n/j H:i') }}</span>
                </div>
            </div>
            <div class="px-4 py-3.5">
                <p class="text-sm text-gray-800 leading-relaxed font-medium">{{ $post->message }}</p>
                @if($post->has_osagari)
                <div class="mt-2.5 flex items-center gap-2 bg-brand-50/70 rounded-lg px-3 py-2 border border-brand-100/40">
                    <span class="text-lg">🎁</span>
                    <div class="flex-1 min-w-0">
                        <p class="text-[11px] font-bold text-brand-800">お下がり：{{ $post->osagari_item }}</p>
                        @if($post->osagari_size)
                        <p class="text-[10px] text-brand-600">{{ $post->osagari_size }}</p>
                        @endif
                    </div>
                    <a href="{{ route('ambassador.trial', ['ambassador' => $ambassador, 'osagari_post' => $post->id]) }}"
                       class="bg-brand-500 text-white text-[10px] font-bold px-2.5 py-1.5 rounded-lg active:scale-95 transition-all flex-shrink-0">
                        申込む
                    </a>
                </div>
                @endif
                @if($post->spot)
                <a href="{{ route('spots.index', ['focus' => $post->spot->id]) }}" class="mt-2.5 flex items-center gap-2 bg-brand-50/70 rounded-lg px-2.5 py-2 group border border-brand-100/30">
                    @if($post->spot->image_path)
                        <img src="{{ $post->spot->image_path }}" alt="" class="w-7 h-7 rounded-md object-cover">
                    @else
                        <div class="w-7 h-7 rounded-md bg-brand-100 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" stroke="#16a34a" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 1 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        </div>
                    @endif
                    <p class="text-[11px] font-bold text-brand-700 flex-1 truncate">{{ $post->spot->title }}</p>
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" stroke="#16a34a" stroke-width="2.5" stroke-linecap="round" class="flex-shrink-0"><polyline points="9 18 15 12 9 6"/></svg>
                </a>
                @endif
            </div>
        </article>
        @empty
        <div class="text-center py-16">
            <p class="text-sm text-gray-400 font-medium">まだ投稿がありません</p>
        </div>
        @endforelse

        @if($posts->hasPages())
        <div class="py-4">{{ $posts->links() }}</div>
        @endif
    </div>

    {{-- ===== Q&A Tab ===== --}}
    <div id="panel-qa" class="px-4 pt-4 space-y-4 hidden">

        {{-- Ask a question --}}
        <div class="card p-4" id="qa-section">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-8 h-8 bg-gradient-to-br from-amber-100 to-orange-100 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" stroke="#d97706" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                </div>
                <h3 class="text-sm font-black text-gray-900">先生に質問する</h3>
            </div>
            <div class="flex gap-2">
                <input type="text" id="qa-input" placeholder="気になることを聞いてみよう…" class="input-field flex-1 text-sm"
                    onkeydown="if(event.key==='Enter'&&!window._qComposing){event.preventDefault();submitAmbQ();}">
                <button onclick="submitAmbQ()" class="bg-gradient-to-r from-amber-500 to-orange-500 text-white font-bold text-xs px-4 rounded-xl active:scale-95 transition-all shadow-sm flex-shrink-0">
                    送信
                </button>
            </div>
            <p class="text-[10px] text-gray-300 mt-2">質問と回答は全員に公開されます</p>
        </div>

        {{-- Q&A List --}}
        <div id="qa-list" class="space-y-3">
            @forelse($questions as $q)
            <div class="card p-4 fade-up" style="animation-delay:{{ $loop->index * 0.05 }}s">
                {{-- Question --}}
                <div class="flex gap-2.5 items-start">
                    <div class="w-7 h-7 bg-gradient-to-br from-blue-100 to-blue-200 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-1.5 mb-1">
                            <span class="text-[11px] font-bold text-gray-700">{{ $q->user->nickname ?? $q->user->name }}</span>
                            <span class="text-[10px] text-gray-300">{{ $q->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-[13px] text-gray-700 leading-relaxed">{{ $q->body }}</p>
                    </div>
                </div>

                {{-- Answers --}}
                @foreach($q->answers as $a)
                <div class="flex gap-2.5 items-start mt-3 ml-6">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5 {{ $a->user->isAmbassador() ? 'bg-gradient-to-br from-amber-200 to-orange-200' : 'bg-gradient-to-br from-gray-100 to-gray-200' }}">
                        @if($a->user->isAmbassador())
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="#d97706" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" stroke="#6b7280" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-1.5 mb-1">
                            <span class="text-[11px] font-bold {{ $a->user->isAmbassador() ? 'text-amber-700' : 'text-gray-700' }}">{{ $a->user->nickname ?? $a->user->name }}</span>
                            @if($a->user->isAmbassador())
                                <span class="badge badge-amber" style="font-size:8px;padding:1px 5px;">先生</span>
                            @endif
                            <span class="text-[10px] text-gray-300">{{ $a->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="bg-{{ $a->user->isAmbassador() ? 'amber-50 border border-amber-100/50' : 'gray-50' }} rounded-xl rounded-tl-sm px-3 py-2.5">
                            <p class="text-[13px] text-gray-700 leading-relaxed">{{ $a->body }}</p>
                        </div>
                    </div>
                </div>
                @endforeach

                {{-- Reply input --}}
                <div class="mt-3 ml-6 flex gap-2">
                    <input type="text" id="ans-input-{{ $q->id }}" placeholder="返信する…"
                        class="input-field flex-1 text-[12px] py-2"
                        onkeydown="if(event.key==='Enter'&&!window._qComposing){event.preventDefault();submitAmbAns({{ $q->id }});}">
                    <button onclick="submitAmbAns({{ $q->id }})" class="bg-amber-500 text-white font-bold text-[10px] px-3 rounded-lg active:scale-95 transition-all flex-shrink-0">
                        返信
                    </button>
                </div>
            </div>
            @empty
            <div class="text-center py-12">
                <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center mx-auto mb-3 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="#d1d5db" stroke-width="1.5"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                </div>
                <p class="text-sm font-bold text-gray-400">まだ質問がありません</p>
                <p class="text-[10px] text-gray-300 mt-1">先生に気軽に聞いてみましょう</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<style>
.amb-tab { color: #9ca3af; border-color: transparent; }
.amb-tab.active { color: #d97706; border-color: #f59e0b; }
</style>

<script>
var csrfToken = '{{ csrf_token() }}';
var ambassadorId = {{ $ambassador->id }};
window._qComposing = false;
document.addEventListener('compositionstart', function() { window._qComposing = true; });
document.addEventListener('compositionend', function() { window._qComposing = false; });

function switchTab(tab) {
    document.getElementById('panel-posts').classList.toggle('hidden', tab !== 'posts');
    document.getElementById('panel-qa').classList.toggle('hidden', tab !== 'qa');
    document.getElementById('tab-posts').classList.toggle('active', tab === 'posts');
    document.getElementById('tab-qa').classList.toggle('active', tab === 'qa');
}

// Hash navigation
if (window.location.hash === '#qa-section') { switchTab('qa'); }

function submitAmbQ() {
    var input = document.getElementById('qa-input');
    var body = input.value.trim();
    if (!body) return;
    input.disabled = true;

    fetch('/ambassador/' + ambassadorId + '/questions', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
        body: JSON.stringify({ body: body })
    }).then(function(r){ return r.json(); }).then(function(data){
        input.value = '';
        input.disabled = false;

        var list = document.getElementById('qa-list');
        var empty = list.querySelector('.text-center.py-12');
        if (empty) empty.remove();

        var html = '<div class="card p-4 slide-in">' +
            '<div class="flex gap-2.5 items-start">' +
            '<div class="w-7 h-7 bg-gradient-to-br from-blue-100 to-blue-200 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">' +
            '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>' +
            '<div class="flex-1 min-w-0"><div class="flex items-center gap-1.5 mb-1">' +
            '<span class="text-[11px] font-bold text-gray-700">' + escapeHtml(data.user_name) + '</span>' +
            '<span class="text-[10px] text-gray-300">' + escapeHtml(data.created_at) + '</span></div>' +
            '<p class="text-[13px] text-gray-700 leading-relaxed">' + escapeHtml(data.body) + '</p></div></div>' +
            '<div class="mt-3 ml-6 flex gap-2"><input type="text" id="ans-input-' + data.id + '" placeholder="返信する…" class="input-field flex-1 text-[12px] py-2" onkeydown="if(event.key===\'Enter\'&&!window._qComposing){event.preventDefault();submitAmbAns(' + data.id + ');}">' +
            '<button onclick="submitAmbAns(' + data.id + ')" class="bg-amber-500 text-white font-bold text-[10px] px-3 rounded-lg active:scale-95 transition-all flex-shrink-0">返信</button></div></div>';

        list.insertAdjacentHTML('afterbegin', html);
    }).catch(function(){ input.disabled = false; });
}

function submitAmbAns(questionId) {
    var input = document.getElementById('ans-input-' + questionId);
    var body = input.value.trim();
    if (!body) return;
    input.disabled = true;

    fetch('/ambassador-questions/' + questionId + '/answers', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
        body: JSON.stringify({ body: body })
    }).then(function(r){ return r.json(); }).then(function(data){
        input.value = '';
        input.disabled = false;

        var wrapper = input.closest('.card');
        var inputRow = input.closest('.flex.gap-2');

        var bgClass = data.is_ambassador ? 'bg-amber-50 border border-amber-100/50' : 'bg-gray-50';
        var nameClass = data.is_ambassador ? 'text-amber-700' : 'text-gray-700';
        var iconBg = data.is_ambassador ? 'bg-gradient-to-br from-amber-200 to-orange-200' : 'bg-gradient-to-br from-gray-100 to-gray-200';
        var icon = data.is_ambassador
            ? '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="#d97706" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>'
            : '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" stroke="#6b7280" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>';
        var badge = data.is_ambassador ? '<span class="badge badge-amber" style="font-size:8px;padding:1px 5px;">先生</span>' : '';

        var html = '<div class="flex gap-2.5 items-start mt-3 ml-6 slide-in">' +
            '<div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5 ' + iconBg + '">' + icon + '</div>' +
            '<div class="flex-1 min-w-0"><div class="flex items-center gap-1.5 mb-1">' +
            '<span class="text-[11px] font-bold ' + nameClass + '">' + escapeHtml(data.user_name) + '</span>' +
            badge +
            '<span class="text-[10px] text-gray-300">' + escapeHtml(data.created_at) + '</span></div>' +
            '<div class="' + bgClass + ' rounded-xl rounded-tl-sm px-3 py-2.5">' +
            '<p class="text-[13px] text-gray-700 leading-relaxed">' + escapeHtml(data.body) + '</p></div></div></div>';

        inputRow.insertAdjacentHTML('beforebegin', html);
    }).catch(function(){ input.disabled = false; });
}

function escapeHtml(text) {
    var div = document.createElement('div');
    div.appendChild(document.createTextNode(text));
    return div.innerHTML;
}
</script>
@endsection
