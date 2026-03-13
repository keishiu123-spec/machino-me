@extends('layouts.app')

@section('content')
<div class="bg-surface-50 min-h-screen pb-24">

    {{-- Header --}}
    <header class="px-5 pt-4 pb-3 bg-white/95 backdrop-blur-xl sticky top-[41px] z-40 border-b border-gray-100/60">
        <div class="flex items-center justify-between mb-3">
            <div>
                <h1 class="text-lg font-black text-gray-900 tracking-tight">習い事なんでも相談</h1>
                <p class="text-[11px] text-gray-400 font-medium mt-0.5">先輩パパママの知恵を借りよう</p>
            </div>
            <a href="{{ route('questions.create') }}"
               class="bg-gradient-to-r from-brand-500 to-brand-600 text-white text-xs font-bold px-4 py-2.5 rounded-xl active:scale-95 transition-transform shadow-[0_4px_16px_rgba(34,197,94,0.25)] flex items-center gap-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                相談する
            </a>
        </div>

        {{-- Search --}}
        <div class="relative mb-3">
            <input type="text" id="q-search" placeholder="質問を検索…" class="input-field pl-9"
                oninput="searchQuestions(this.value)">
            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-300" xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        </div>

        {{-- Category filter --}}
        <div class="flex gap-2 overflow-x-auto no-scrollbar pb-1">
            <button onclick="filterCat('all')" class="chip active" data-cat="all">すべて</button>
            <button onclick="filterCat('月謝・費用')" class="chip" data-cat="月謝・費用">💰 費用</button>
            <button onclick="filterCat('先生・指導')" class="chip" data-cat="先生・指導">👩‍🏫 先生</button>
            <button onclick="filterCat('当番・親の負担')" class="chip" data-cat="当番・親の負担">📋 当番</button>
            <button onclick="filterCat('始め時・年齢')" class="chip" data-cat="始め時・年齢">🎒 年齢</button>
            <button onclick="filterCat('教室選び')" class="chip" data-cat="教室選び">🔍 教室選び</button>
            <button onclick="filterCat('送迎・スケジュール')" class="chip" data-cat="送迎・スケジュール">🚗 送迎</button>
        </div>
    </header>

    {{-- Feed --}}
    <div class="px-4 pt-4 space-y-3" id="q-feed">
        @forelse($questions as $question)
        <article class="q-item card overflow-hidden fade-up" style="animation-delay:{{ $loop->index * 0.06 }}s"
                 data-status="{{ $question->status }}" data-cat="{{ $question->category }}">

            {{-- Status & Category --}}
            <div class="px-4 pt-3.5 pb-2 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    @if($question->status === 'resolved')
                        <span class="badge badge-green">
                            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
                            解決済み
                        </span>
                    @else
                        <span class="badge badge-amber">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                            回答募集中
                        </span>
                    @endif
                    <span class="badge badge-gray">{{ $question->category }}</span>
                </div>
                <span class="text-[10px] text-gray-300 font-medium">{{ $question->created_at->diffForHumans() }}</span>
            </div>

            <div class="px-4 pb-4">
                {{-- Title --}}
                <h3 class="font-black text-[15px] text-gray-900 leading-snug mb-1.5">{{ $question->title }}</h3>

                {{-- Note --}}
                <p class="text-[13px] text-gray-500 leading-relaxed mb-3">{{ $question->note }}</p>

                {{-- Spot Link --}}
                @if($question->spot)
                <a href="{{ route('spots.index', ['focus' => $question->spot->id]) }}" class="flex items-center gap-2.5 bg-brand-50/60 rounded-xl px-3 py-2 mb-3 group border border-brand-100/40">
                    @if($question->spot->image_path)
                        <img src="{{ $question->spot->image_path }}" alt="" class="w-8 h-8 rounded-lg object-cover">
                    @else
                        <div class="w-8 h-8 rounded-lg bg-brand-100 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="#16a34a" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 1 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <p class="text-[11px] font-bold text-brand-700 truncate">{{ $question->spot->title }}</p>
                        <p class="text-[9px] text-brand-500">この教室について</p>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" stroke="#16a34a" stroke-width="2.5" stroke-linecap="round" class="flex-shrink-0 opacity-50"><polyline points="9 18 15 12 9 6"/></svg>
                </a>
                @endif

                @if($question->target_age)
                <div class="flex items-center gap-1 mb-3">
                    <span class="badge badge-purple">🎒 {{ $question->target_age }}</span>
                </div>
                @endif

                @if($question->image_path)
                    <img src="{{ asset('storage/' . $question->image_path) }}" alt="" class="rounded-xl w-full mb-3">
                @endif

                {{-- Stats --}}
                <div class="flex items-center gap-3 mb-3">
                    <span class="text-[11px] font-bold text-gray-400 flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                        <span class="comment-count-{{ $question->id }}">{{ $question->comments->count() }}</span>件の回答
                    </span>
                    @php $totalThanks = $question->comments->sum('thanks_count'); @endphp
                    @if($totalThanks > 0)
                    <span class="text-[11px] font-bold text-brand-600 flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="#16a34a" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                        {{ $totalThanks }}人が助かった
                    </span>
                    @endif
                </div>

                {{-- Answers --}}
                <div class="comments-section-{{ $question->id }}">
                    @if($question->comments->count() > 0)
                    <div class="space-y-2.5 mb-3">
                        @foreach($question->comments as $comment)
                        <div class="comment-item flex gap-2 items-start">
                            <div class="w-7 h-7 bg-gradient-to-br from-orange-100 to-amber-200 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" stroke="#d97706" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="bg-gray-50 rounded-xl rounded-tl-sm px-3 py-2.5">
                                    <p class="text-[13px] text-gray-700 leading-relaxed">{{ $comment->body }}</p>
                                </div>
                                <button onclick="thankComment(this, {{ $comment->id }})"
                                    class="thanks-btn text-[10px] font-bold flex items-center gap-1 px-2 py-0.5 rounded-lg mt-1 transition-all active:scale-95 {{ $comment->thanks_count > 0 ? 'text-brand-600' : 'text-gray-300 hover:text-brand-500' }}">
                                    <svg class="thanks-icon" xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="{{ $comment->thanks_count > 0 ? '#16a34a' : 'none' }}" stroke="{{ $comment->thanks_count > 0 ? '#16a34a' : 'currentColor' }}" stroke-width="2" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                                    助かった <span class="thanks-count">{{ $comment->thanks_count }}</span>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>

                {{-- Reply input --}}
                <div class="flex items-center gap-2">
                    <div class="flex-1 flex items-center bg-gray-50 rounded-xl border border-gray-100 overflow-hidden focus-within:border-brand-400 focus-within:ring-2 focus-within:ring-brand-100 transition-all">
                        <input type="text" id="comment-input-{{ $question->id }}" placeholder="知っていることを教えてあげよう..."
                            class="flex-1 px-3 py-2.5 text-sm font-medium outline-none bg-transparent placeholder-gray-300"
                            onkeydown="handleCommentKey(event, {{ $question->id }})">
                        <button onclick="submitComment({{ $question->id }})"
                            class="comment-submit-btn bg-brand-500 hover:bg-brand-600 text-white px-3.5 py-2.5 font-bold text-xs transition-colors flex-shrink-0">
                            送信
                        </button>
                    </div>
                </div>
            </div>
        </article>
        @empty
        <div class="py-20 text-center fade-up">
            <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" stroke="#86efac" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            </div>
            <p class="text-sm font-bold text-gray-400">まだ相談がありません</p>
            <p class="text-[11px] text-gray-300 mt-1">最初の質問を投稿してみましょう</p>
        </div>
        @endforelse
    </div>
</div>

<script>
var csrfToken = '{{ csrf_token() }}';
var isComposing = false;
document.addEventListener('compositionstart', function() { isComposing = true; });
document.addEventListener('compositionend', function() { isComposing = false; });

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
    btn.textContent = '...';

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
        btn.textContent = '送信';

        var section = document.querySelector('.comments-section-' + questionId);
        var list = section.querySelector('.space-y-2\\.5');
        if (!list) {
            list = document.createElement('div');
            list.className = 'space-y-2.5 mb-3';
            section.appendChild(list);
        }

        var html = '<div class="comment-item new flex gap-2 items-start">' +
            '<div class="w-7 h-7 bg-gradient-to-br from-brand-100 to-brand-200 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">' +
            '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" stroke="#16a34a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>' +
            '</div>' +
            '<div class="flex-1 min-w-0">' +
            '<div class="bg-brand-50/50 rounded-xl rounded-tl-sm px-3 py-2.5 border border-brand-100/50">' +
            '<p class="text-[13px] text-gray-700 leading-relaxed">' + escapeHtml(data.body) + '</p>' +
            '</div>' +
            '<button onclick="thankComment(this, ' + data.id + ')" class="thanks-btn text-[10px] font-bold flex items-center gap-1 px-2 py-0.5 rounded-lg mt-1 transition-all active:scale-95 text-gray-300 hover:text-brand-500">' +
            '<svg class="thanks-icon" xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>' +
            '助かった <span class="thanks-count">0</span></button>' +
            '</div></div>';

        list.insertAdjacentHTML('beforeend', html);

        var countEl = document.querySelector('.comment-count-' + questionId);
        if (countEl) countEl.textContent = parseInt(countEl.textContent) + 1;

        list.lastElementChild.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    })
    .catch(function() {
        btn.disabled = false;
        btn.textContent = '送信';
    });
}

function thankComment(btn, commentId) {
    if (btn.dataset.thanked) return;
    btn.dataset.thanked = '1';

    var countEl = btn.querySelector('.thanks-count');
    var icon = btn.querySelector('.thanks-icon');

    var newCount = parseInt(countEl.textContent) + 1;
    countEl.textContent = newCount;
    icon.setAttribute('fill', '#16a34a');
    icon.setAttribute('stroke', '#16a34a');
    btn.classList.remove('text-gray-300', 'hover:text-brand-500');
    btn.classList.add('text-brand-600');
    btn.style.pointerEvents = 'none';
    btn.style.transform = 'scale(1.15)';
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
    document.querySelectorAll('.chip').forEach(function(c) {
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
