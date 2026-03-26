@extends('layouts.app')

@section('content')
<div class="min-h-screen pb-24" style="background: var(--color-cream);">

    {{-- Header --}}
    <div style="background: var(--color-white); border-bottom: 0.5px solid var(--color-border);">
        <div class="flex items-center justify-between px-4 py-3">
            <div>
                <h1 class="text-base font-serif" style="color: var(--color-ink);">管理画面</h1>
                <p class="text-[10px] text-gray-400 font-medium">{{ auth()->user()->organization_name ?? auth()->user()->name }}</p>
            </div>
            <a href="{{ route('ambassador.show', auth()->user()) }}" class="badge badge-amber active:scale-95 transition-all">
                公開ページ →
            </a>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="px-4 pt-4 grid grid-cols-3 gap-2">
        <div class="card p-3 text-center">
            <p class="text-xl font-black text-brand-600">{{ $trialRequests->where('status', 'pending')->count() }}</p>
            <p class="text-[10px] font-bold text-gray-400 mt-0.5">未対応</p>
        </div>
        <div class="card p-3 text-center">
            <p class="text-xl font-black text-amber-500">{{ $questions->filter(fn($q) => $q->answers->where('user_id', auth()->id())->isEmpty())->count() }}</p>
            <p class="text-[10px] font-bold text-gray-400 mt-0.5">未回答Q&A</p>
        </div>
        <div class="card p-3 text-center">
            <p class="text-xl font-black text-gray-900">{{ $spots->count() }}</p>
            <p class="text-[10px] font-bold text-gray-400 mt-0.5">教室</p>
        </div>
    </div>

    {{-- Tab Navigation --}}
    <div class="sticky top-[41px] z-30 bg-white border-b border-gray-100/60 mt-4">
        <div class="flex">
            <button onclick="switchDashTab('trials')" id="dtab-trials" class="dash-tab active flex-1 py-3 text-xs font-bold text-center border-b-2 transition-all">
                体験申込 <span class="badge badge-green ml-1" style="font-size:9px;">{{ $trialRequests->count() }}</span>
            </button>
            <button onclick="switchDashTab('qa')" id="dtab-qa" class="dash-tab flex-1 py-3 text-xs font-bold text-center border-b-2 transition-all">
                Q&A <span class="badge badge-amber ml-1" style="font-size:9px;">{{ $questions->count() }}</span>
            </button>
        </div>
    </div>

    {{-- ===== Trial Requests Tab ===== --}}
    <div id="dpanel-trials" class="px-4 pt-4 space-y-3">
        @forelse($trialRequests as $trial)
        <div class="card p-4 fade-up" style="animation-delay:{{ $loop->index * 0.04 }}s">
            {{-- Status & Date --}}
            <div class="flex items-center justify-between mb-3">
                @php
                    $statusMap = [
                        'pending' => ['未対応', 'badge-red'],
                        'contacted' => ['連絡済み', 'badge-amber'],
                        'completed' => ['完了', 'badge-green'],
                        'cancelled' => ['キャンセル', 'badge-gray'],
                    ];
                    [$statusLabel, $statusClass] = $statusMap[$trial->status] ?? ['不明', 'badge-gray'];
                @endphp
                <span class="badge {{ $statusClass }}">{{ $statusLabel }}</span>
                <span class="text-[10px] text-gray-300 font-medium">{{ $trial->created_at->format('n/j H:i') }}</span>
            </div>

            {{-- Info Grid --}}
            <div class="space-y-2">
                <div class="flex items-center gap-2">
                    <span class="text-[10px] font-bold text-gray-400 w-14 flex-shrink-0">保護者</span>
                    <span class="text-sm font-bold text-gray-900">{{ $trial->parent_name }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-[10px] font-bold text-gray-400 w-14 flex-shrink-0">お子さま</span>
                    <span class="text-sm text-gray-700">{{ $trial->child_name }}（{{ $trial->child_age }}）</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-[10px] font-bold text-gray-400 w-14 flex-shrink-0">電話</span>
                    <a href="tel:{{ $trial->phone }}" class="text-sm text-brand-600 font-medium">{{ $trial->phone }}</a>
                </div>
                @if($trial->email)
                <div class="flex items-center gap-2">
                    <span class="text-[10px] font-bold text-gray-400 w-14 flex-shrink-0">メール</span>
                    <a href="mailto:{{ $trial->email }}" class="text-sm text-brand-600 font-medium truncate">{{ $trial->email }}</a>
                </div>
                @endif
                @if($trial->spot)
                <div class="flex items-center gap-2">
                    <span class="text-[10px] font-bold text-gray-400 w-14 flex-shrink-0">教室</span>
                    <span class="text-sm text-gray-700">{{ $trial->spot->title }}</span>
                </div>
                @endif
                @if($trial->note)
                <div class="mt-2 bg-gray-50 rounded-xl px-3 py-2.5">
                    <p class="text-[10px] font-bold text-gray-400 mb-1">備考</p>
                    <p class="text-[13px] text-gray-600 leading-relaxed">{{ $trial->note }}</p>
                </div>
                @endif
            </div>

            {{-- Status Update --}}
            <div class="mt-3 pt-3 border-t border-gray-100/60">
                <form action="{{ route('ambassador.trial.updateStatus', $trial) }}" method="POST" class="flex gap-2">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="input-field text-[11px] py-2 flex-1">
                        <option value="pending" {{ $trial->status === 'pending' ? 'selected' : '' }}>未対応</option>
                        <option value="contacted" {{ $trial->status === 'contacted' ? 'selected' : '' }}>連絡済み</option>
                        <option value="completed" {{ $trial->status === 'completed' ? 'selected' : '' }}>完了</option>
                        <option value="cancelled" {{ $trial->status === 'cancelled' ? 'selected' : '' }}>キャンセル</option>
                    </select>
                    <button type="submit" class="bg-gray-900 text-white text-[10px] font-bold px-4 rounded-xl active:scale-95 transition-all flex-shrink-0">
                        更新
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="text-center py-16">
            <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center mx-auto mb-3 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="#d1d5db" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            </div>
            <p class="text-sm font-bold text-gray-400">体験申込はまだありません</p>
            <p class="text-[10px] text-gray-300 mt-1">公開ページから申込を受け付けましょう</p>
        </div>
        @endforelse
    </div>

    {{-- ===== Q&A Tab ===== --}}
    <div id="dpanel-qa" class="px-4 pt-4 space-y-3 hidden">
        @forelse($questions as $q)
        <div class="card p-4 fade-up" style="animation-delay:{{ $loop->index * 0.04 }}s">
            @php
                $hasMyAnswer = $q->answers->where('user_id', auth()->id())->isNotEmpty();
            @endphp

            {{-- Status --}}
            <div class="flex items-center justify-between mb-2">
                @if($hasMyAnswer)
                    <span class="badge badge-green">回答済み</span>
                @else
                    <span class="badge badge-red">未回答</span>
                @endif
                <span class="text-[10px] text-gray-300 font-medium">{{ $q->created_at->diffForHumans() }}</span>
            </div>

            {{-- Question --}}
            <div class="flex gap-2.5 items-start">
                <div class="w-7 h-7 bg-gradient-to-br from-blue-100 to-blue-200 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </div>
                <div class="flex-1 min-w-0">
                    <span class="text-[11px] font-bold text-gray-700">{{ $q->user->nickname ?? $q->user->name }}</span>
                    <p class="text-[13px] text-gray-700 leading-relaxed mt-0.5">{{ $q->body }}</p>
                </div>
            </div>

            {{-- Existing Answers --}}
            @foreach($q->answers as $a)
            <div class="flex gap-2.5 items-start mt-3 ml-6">
                <div class="w-6 h-6 rounded-md flex items-center justify-center flex-shrink-0 mt-0.5 {{ $a->user->isAmbassador() ? 'bg-gradient-to-br from-amber-200 to-orange-200' : 'bg-gray-100' }}">
                    @if($a->user->isAmbassador())
                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="#d97706" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="none" stroke="#9ca3af" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-1 mb-0.5">
                        <span class="text-[10px] font-bold {{ $a->user->isAmbassador() ? 'text-amber-700' : 'text-gray-500' }}">{{ $a->user->nickname ?? $a->user->name }}</span>
                        @if($a->user->isAmbassador())
                            <span class="badge badge-amber" style="font-size:8px;padding:1px 4px;">先生</span>
                        @endif
                    </div>
                    <p class="text-[12px] text-gray-600 leading-relaxed">{{ $a->body }}</p>
                </div>
            </div>
            @endforeach

            {{-- Reply input --}}
            <div class="mt-3 ml-6 flex gap-2">
                <input type="text" id="dash-ans-{{ $q->id }}" placeholder="回答を入力…"
                    class="input-field flex-1 text-[12px] py-2"
                    onkeydown="if(event.key==='Enter'&&!window._dComposing){event.preventDefault();submitDashAns({{ $q->id }});}">
                <button onclick="submitDashAns({{ $q->id }})" class="bg-amber-500 text-white font-bold text-[10px] px-3 rounded-lg active:scale-95 transition-all flex-shrink-0">
                    回答
                </button>
            </div>
        </div>
        @empty
        <div class="text-center py-16">
            <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center mx-auto mb-3 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="#d1d5db" stroke-width="1.5"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            </div>
            <p class="text-sm font-bold text-gray-400">質問はまだありません</p>
        </div>
        @endforelse
    </div>

    {{-- Quick Actions --}}
    <div class="px-4 pt-6 pb-4">
        <span class="section-label">クイックアクション</span>
        <div class="grid grid-cols-2 gap-2 mt-2">
            <a href="{{ route('ambassador.create') }}" class="card p-3 flex items-center gap-2.5 active:scale-[0.98] transition-all">
                <div class="w-9 h-9 bg-gradient-to-br from-amber-50 to-orange-100 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="#d97706" stroke-width="2" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                </div>
                <span class="text-[12px] font-bold text-gray-700">通信を投稿</span>
            </a>
            <a href="{{ route('ambassador.show', auth()->user()) }}" class="card p-3 flex items-center gap-2.5 active:scale-[0.98] transition-all">
                <div class="w-9 h-9 bg-gradient-to-br from-brand-50 to-brand-100 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="#16a34a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                </div>
                <span class="text-[12px] font-bold text-gray-700">公開ページ</span>
            </a>
        </div>
    </div>
</div>

<style>
.dash-tab { color: #9ca3af; border-color: transparent; }
.dash-tab.active { color: #16a34a; border-color: #22c55e; }
</style>

<script>
var csrfToken = '{{ csrf_token() }}';
window._dComposing = false;
document.addEventListener('compositionstart', function() { window._dComposing = true; });
document.addEventListener('compositionend', function() { window._dComposing = false; });

function switchDashTab(tab) {
    document.getElementById('dpanel-trials').classList.toggle('hidden', tab !== 'trials');
    document.getElementById('dpanel-qa').classList.toggle('hidden', tab !== 'qa');
    document.getElementById('dtab-trials').classList.toggle('active', tab === 'trials');
    document.getElementById('dtab-qa').classList.toggle('active', tab === 'qa');
}

function submitDashAns(questionId) {
    var input = document.getElementById('dash-ans-' + questionId);
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
        var statusBadge = wrapper.querySelector('.badge-red');
        if (statusBadge) {
            statusBadge.className = 'badge badge-green';
            statusBadge.textContent = '回答済み';
        }

        var html = '<div class="flex gap-2.5 items-start mt-3 ml-6 slide-in">' +
            '<div class="w-6 h-6 rounded-md flex items-center justify-center flex-shrink-0 mt-0.5 bg-gradient-to-br from-amber-200 to-orange-200">' +
            '<svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="#d97706" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg></div>' +
            '<div class="flex-1 min-w-0"><div class="flex items-center gap-1 mb-0.5">' +
            '<span class="text-[10px] font-bold text-amber-700">' + escapeHtml(data.user_name) + '</span>' +
            '<span class="badge badge-amber" style="font-size:8px;padding:1px 4px;">先生</span></div>' +
            '<p class="text-[12px] text-gray-600 leading-relaxed">' + escapeHtml(data.body) + '</p></div></div>';

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
