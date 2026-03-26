@extends('layouts.app')

@section('content')
<div class="min-h-screen pb-24" style="background: var(--color-cream);">

    {{-- Slack-style Header --}}
    <header class="px-4 pt-3 pb-2 sticky top-[41px] z-40" style="background: var(--color-cream); border-bottom: 0.5px solid var(--color-border);">
        <div class="flex items-center justify-between mb-2.5">
            <div class="flex items-center gap-2">
                <span class="text-lg font-serif" style="color: var(--color-ink);"># 質問箱</span>
                <span class="text-[11px] text-gray-400 font-medium mt-0.5">{{ $questions->count() }}件の相談</span>
            </div>
            <a href="{{ route('questions.create') }}"
               class="w-9 h-9 text-white rounded-lg" style="background: var(--color-coral); flex items-center justify-center active:scale-95 transition-all shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            </a>
        </div>

        {{-- Search --}}
        <div class="relative mb-2.5">
            <input type="text" id="q-search" placeholder="メッセージを検索…"
                class="w-full bg-gray-100 rounded-lg px-3 py-2 pl-8 text-[13px] font-medium outline-none border border-transparent focus:border-brand-400 focus:bg-white transition-all placeholder-gray-400"
                oninput="searchQuestions(this.value)">
            <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        </div>

        {{-- Channel-style category tabs --}}
        <div class="flex gap-1.5 overflow-x-auto no-scrollbar pb-0.5">
            <button onclick="filterCat('all')" class="cat-tab active" data-cat="all"># すべて</button>
            <button onclick="filterCat('月謝・費用')" class="cat-tab" data-cat="月謝・費用"># 費用</button>
            <button onclick="filterCat('先生・指導')" class="cat-tab" data-cat="先生・指導"># 先生</button>
            <button onclick="filterCat('当番・親の負担')" class="cat-tab" data-cat="当番・親の負担"># 当番</button>
            <button onclick="filterCat('始め時・年齢')" class="cat-tab" data-cat="始め時・年齢"># 年齢</button>
            <button onclick="filterCat('教室選び')" class="cat-tab" data-cat="教室選び"># 教室選び</button>
            <button onclick="filterCat('送迎・スケジュール')" class="cat-tab" data-cat="送迎・スケジュール"># 送迎</button>
        </div>
    </header>

    {{-- Message Feed --}}
    <div class="pt-2" id="q-feed">
        @forelse($questions as $question)
        <article class="q-item border-b fade-up" style="background: var(--color-white); border-color: var(--color-border);" style="animation-delay:{{ $loop->index * 0.04 }}s"
                 data-status="{{ $question->status }}" data-cat="{{ $question->category }}">

            <div class="px-4 py-3">
                {{-- Question message (Slack-style) --}}
                <div class="flex gap-3">
                    {{-- Avatar --}}
                    @php
                        $avatarColors = ['from-blue-400 to-blue-600', 'from-purple-400 to-purple-600', 'from-orange-400 to-orange-600', 'from-pink-400 to-pink-600', 'from-teal-400 to-teal-600', 'from-indigo-400 to-indigo-600'];
                        $avatarColor = $avatarColors[$question->id % count($avatarColors)];
                        $avatarInitials = ['👩', '👨', '🧑', '👩‍🦰', '👨‍🦱', '🧑‍🦳'];
                        $avatarEmoji = $avatarInitials[$question->id % count($avatarInitials)];
                    @endphp
                    <div class="w-9 h-9 bg-gradient-to-br {{ $avatarColor }} rounded-lg flex items-center justify-center flex-shrink-0 text-sm shadow-sm">
                        {{ $avatarEmoji }}
                    </div>

                    <div class="flex-1 min-w-0">
                        {{-- Name + timestamp --}}
                        <div class="flex items-baseline gap-2 mb-0.5">
                            <span class="text-[13px] font-extrabold text-gray-900">保護者さん</span>
                            <span class="text-[11px] text-gray-400 font-medium">{{ $question->created_at->format('n/j H:i') }}</span>
                            @if($question->status === 'resolved')
                                <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[9px] font-bold bg-brand-100 text-brand-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="8" height="8" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
                                    解決済み
                                </span>
                            @endif
                        </div>

                        {{-- Channel tag --}}
                        <span class="inline-block text-[10px] font-medium px-1.5 py-0.5 rounded mb-1.5" style="color: var(--color-coral); background: var(--color-coral-light);"># {{ $question->category }}</span>

                        {{-- Title (bold like Slack message) --}}
                        <h3 class="font-bold text-[14px] text-gray-900 leading-snug mb-1">{{ $question->title }}</h3>

                        {{-- Body --}}
                        <p class="text-[13px] text-gray-600 leading-relaxed mb-2">{{ $question->note }}</p>

                        {{-- Spot link --}}
                        @if($question->spot)
                        <a href="{{ route('spots.index', ['focus' => $question->spot->id]) }}"
                           class="inline-flex items-center gap-1.5 text-[11px] font-bold text-blue-600 hover:text-blue-700 bg-blue-50 rounded-md px-2 py-1 mb-2 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 1 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            {{ $question->spot->title }}
                        </a>
                        @endif

                        @if($question->target_age)
                        <span class="inline-flex items-center text-[10px] font-bold text-purple-600 bg-purple-50 rounded-md px-1.5 py-0.5 mb-2">🎒 {{ $question->target_age }}</span>
                        @endif

                        @if($question->image_path)
                            <img src="{{ asset('storage/' . $question->image_path) }}" alt="" class="rounded-lg max-w-[260px] w-full mb-2 border border-gray-100">
                        @endif

                        {{-- Reactions bar (Slack-style) --}}
                        @php $totalThanks = $question->comments->sum('thanks_count'); @endphp
                        <div class="flex items-center gap-2 mt-1">
                            @if($totalThanks > 0)
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-gray-100 text-[11px] font-bold text-gray-600 border border-gray-200/50">
                                ❤️ {{ $totalThanks }}
                            </span>
                            @endif
                            @if($question->status === 'open')
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-amber-50 text-[11px] font-bold text-amber-600 border border-amber-200/50">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                                回答募集中
                            </span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Thread replies section --}}
                @if($question->comments->count() > 0)
                <div class="ml-12 mt-2">
                    {{-- Thread toggle --}}
                    <button onclick="toggleThread({{ $question->id }})" class="thread-toggle flex items-center gap-2 py-1.5 group" id="thread-btn-{{ $question->id }}">
                        {{-- Stacked mini avatars --}}
                        <div class="flex -space-x-1.5">
                            @foreach($question->comments->take(3) as $ci => $c)
                            @php
                                $cColors = ['bg-orange-400', 'bg-blue-400', 'bg-purple-400', 'bg-pink-400', 'bg-teal-400'];
                                $cColor = $cColors[$ci % count($cColors)];
                            @endphp
                            <div class="w-5 h-5 {{ $cColor }} rounded-md flex items-center justify-center text-[8px] text-white font-bold border border-white">返</div>
                            @endforeach
                        </div>
                        <span class="text-[12px] font-bold text-blue-600 group-hover:underline">
                            <span class="comment-count-{{ $question->id }}">{{ $question->comments->count() }}</span>件の返信
                        </span>
                        <span class="text-[11px] text-gray-400 font-medium">
                            最終 {{ $question->comments->last()->created_at->diffForHumans() }}
                        </span>
                        <svg class="thread-chevron text-gray-300 transition-transform" xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>

                    {{-- Thread messages (collapsed by default) --}}
                    <div class="thread-messages hidden border-l-2 border-gray-200 pl-3 mt-1 space-y-2.5 comments-section-{{ $question->id }}" id="thread-{{ $question->id }}">
                        @foreach($question->comments as $comment)
                        <div class="comment-item flex gap-2.5 items-start">
                            @php
                                $replyColors = ['from-orange-300 to-orange-500', 'from-sky-300 to-sky-500', 'from-violet-300 to-violet-500', 'from-rose-300 to-rose-500', 'from-emerald-300 to-emerald-500'];
                                $replyColor = $replyColors[$comment->id % count($replyColors)];
                            @endphp
                            <div class="w-7 h-7 bg-gradient-to-br {{ $replyColor }} rounded-md flex items-center justify-center flex-shrink-0 text-[10px] text-white font-bold shadow-sm">返</div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-baseline gap-2 mb-0.5">
                                    <span class="text-[12px] font-bold text-gray-800">先輩ママ</span>
                                    <span class="text-[10px] text-gray-400">{{ $comment->created_at->format('n/j H:i') }}</span>
                                </div>
                                <p class="text-[13px] text-gray-700 leading-relaxed">{{ $comment->body }}</p>
                                {{-- Slack-style reaction --}}
                                <button onclick="thankComment(this, {{ $comment->id }})"
                                    class="thanks-btn inline-flex items-center gap-1 mt-1 px-2 py-0.5 rounded-full text-[10px] font-bold transition-all active:scale-95 border
                                    {{ $comment->thanks_count > 0 ? 'bg-red-50 text-red-500 border-red-200/60' : 'bg-gray-50 text-gray-400 border-gray-200/60 hover:bg-red-50 hover:text-red-500 hover:border-red-200/60' }}">
                                    <span class="thanks-icon">{{ $comment->thanks_count > 0 ? '❤️' : '🤍' }}</span>
                                    助かった <span class="thanks-count">{{ $comment->thanks_count }}</span>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @else
                <div class="ml-12 mt-2 comments-section-{{ $question->id }}"></div>
                @endif

                {{-- Reply input (Slack-style composer) --}}
                <div class="ml-12 mt-2.5">
                    <div class="flex items-center gap-2 bg-gray-50 rounded-lg border border-gray-200 overflow-hidden focus-within:border-brand-400 focus-within:bg-white focus-within:shadow-sm transition-all">
                        <input type="text" id="comment-input-{{ $question->id }}" placeholder="返信する…"
                            class="flex-1 px-3 py-2 text-[13px] font-medium outline-none bg-transparent placeholder-gray-400"
                            onkeydown="handleCommentKey(event, {{ $question->id }})">
                        <button onclick="submitComment({{ $question->id }})"
                            class="comment-submit-btn px-3 py-2 text-brand-500 hover:text-brand-600 font-bold text-xs transition-colors flex-shrink-0 disabled:opacity-40">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        </article>
        @empty
        <div class="py-20 text-center fade-up">
            <div class="w-14 h-14 bg-white rounded-xl flex items-center justify-center mx-auto mb-3 shadow-sm border border-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="#9ca3af" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            </div>
            <p class="text-sm font-bold text-gray-400">まだ相談がありません</p>
            <p class="text-[11px] text-gray-300 mt-1">最初の質問を投稿してみましょう</p>
        </div>
        @endforelse
    </div>
</div>

<style>
    /* Channel-style tabs */
    .cat-tab {
        display: inline-flex; align-items: center; padding: 4px 10px; border-radius: 6px;
        font-size: 12px; font-weight: 700; color: #6b7280; background: transparent;
        cursor: pointer; transition: all 0.15s; white-space: nowrap; flex-shrink: 0;
    }
    .cat-tab:hover { background: #f3f4f6; color: #374151; }
    .cat-tab.active { background: var(--color-coral-light); color: var(--color-coral); }

    /* Thread toggle */
    .thread-toggle:hover { background: #f9fafb; border-radius: 6px; padding-left: 4px; padding-right: 4px; }
    .thread-toggle .thread-chevron { transform: rotate(0deg); }
    .thread-toggle.open .thread-chevron { transform: rotate(180deg); }

    /* New comment animation */
    @keyframes slideInComment { from { opacity:0; transform:translateY(8px); } to { opacity:1; transform:translateY(0); } }
    .comment-item.new { animation: slideInComment 0.3s ease-out forwards; }
</style>

<script>
var csrfToken = '{{ csrf_token() }}';
var isComposing = false;
document.addEventListener('compositionstart', function() { isComposing = true; });
document.addEventListener('compositionend', function() { isComposing = false; });

function toggleThread(questionId) {
    var thread = document.getElementById('thread-' + questionId);
    var btn = document.getElementById('thread-btn-' + questionId);
    if (thread.classList.contains('hidden')) {
        thread.classList.remove('hidden');
        btn.classList.add('open');
    } else {
        thread.classList.add('hidden');
        btn.classList.remove('open');
    }
}

function handleCommentKey(event, questionId) {
    if (event.key === 'Enter' && !isComposing) {
        event.preventDefault();
        submitComment(questionId);
    }
}

function submitComment(questionId) {
    var input = document.getElementById('comment-input-' + questionId);
    var body = input.value.trim();
    if (!body) return;

    var btn = input.closest('.flex').querySelector('.comment-submit-btn');
    btn.disabled = true;
    btn.style.opacity = '0.4';

    fetch('/questions/' + questionId + '/comments', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ body: body })
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        input.value = '';
        btn.disabled = false;
        btn.style.opacity = '';

        // Find or create thread container
        var section = document.querySelector('.comments-section-' + questionId);
        var threadEl = document.getElementById('thread-' + questionId);

        if (!threadEl) {
            // First reply — create thread structure
            var threadHtml = '<div class="border-l-2 border-gray-200 pl-3 mt-1 space-y-2.5" id="thread-' + questionId + '"></div>';
            section.innerHTML = threadHtml;
            threadEl = document.getElementById('thread-' + questionId);
        }

        // Make sure thread is visible
        threadEl.classList.remove('hidden');
        var toggleBtn = document.getElementById('thread-btn-' + questionId);
        if (toggleBtn) toggleBtn.classList.add('open');

        var replyColors = ['from-orange-300 to-orange-500', 'from-sky-300 to-sky-500', 'from-violet-300 to-violet-500', 'from-rose-300 to-rose-500', 'from-emerald-300 to-emerald-500'];
        var rc = replyColors[data.id % replyColors.length];

        var html = '<div class="comment-item new flex gap-2.5 items-start">' +
            '<div class="w-7 h-7 bg-gradient-to-br ' + rc + ' rounded-md flex items-center justify-center flex-shrink-0 text-[10px] text-white font-bold shadow-sm">返</div>' +
            '<div class="flex-1 min-w-0">' +
            '<div class="flex items-baseline gap-2 mb-0.5">' +
            '<span class="text-[12px] font-bold text-gray-800">あなた</span>' +
            '<span class="text-[10px] text-gray-400">たった今</span>' +
            '</div>' +
            '<p class="text-[13px] text-gray-700 leading-relaxed">' + escapeHtml(data.body) + '</p>' +
            '<button onclick="thankComment(this, ' + data.id + ')" class="thanks-btn inline-flex items-center gap-1 mt-1 px-2 py-0.5 rounded-full text-[10px] font-bold transition-all active:scale-95 border bg-gray-50 text-gray-400 border-gray-200/60 hover:bg-red-50 hover:text-red-500 hover:border-red-200/60">' +
            '<span class="thanks-icon">🤍</span> 助かった <span class="thanks-count">0</span></button>' +
            '</div></div>';

        threadEl.insertAdjacentHTML('beforeend', html);

        // Update reply count
        var countEl = document.querySelector('.comment-count-' + questionId);
        if (countEl) {
            countEl.textContent = parseInt(countEl.textContent) + 1;
        }

        threadEl.lastElementChild.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    })
    .catch(function() {
        btn.disabled = false;
        btn.style.opacity = '';
    });
}

function thankComment(btn, commentId) {
    if (btn.dataset.thanked) return;
    btn.dataset.thanked = '1';

    var countEl = btn.querySelector('.thanks-count');
    var iconEl = btn.querySelector('.thanks-icon');

    var newCount = parseInt(countEl.textContent) + 1;
    countEl.textContent = newCount;
    iconEl.textContent = '❤️';
    btn.className = 'thanks-btn inline-flex items-center gap-1 mt-1 px-2 py-0.5 rounded-full text-[10px] font-bold transition-all active:scale-95 border bg-red-50 text-red-500 border-red-200/60';
    btn.style.pointerEvents = 'none';
    btn.style.transform = 'scale(1.1)';
    setTimeout(function(){ btn.style.transform = ''; }, 200);

    fetch('/api/comments/' + commentId + '/thanks', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
    });
}

function searchQuestions(query) {
    query = query.toLowerCase().trim();
    document.querySelectorAll('.q-item').forEach(function(item) {
        if (!query) { item.style.display = ''; return; }
        var text = item.textContent.toLowerCase();
        item.style.display = text.indexOf(query) !== -1 ? '' : 'none';
    });
}

function filterCat(cat) {
    document.querySelectorAll('.cat-tab').forEach(function(c) {
        c.classList.toggle('active', c.dataset.cat === cat);
    });
    document.querySelectorAll('.q-item').forEach(function(item) {
        if (cat === 'all') { item.style.display = ''; }
        else { item.style.display = item.dataset.cat === cat ? '' : 'none'; }
    });
}

function escapeHtml(text) {
    var div = document.createElement('div');
    div.appendChild(document.createTextNode(text));
    return div.innerHTML;
}
</script>
@endsection
