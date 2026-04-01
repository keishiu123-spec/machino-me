<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#CCFF66">
    <title>みっけ | 子どもの「好き」が見つかる場所</title>
    <meta name="description" content="個人教室・スポーツ少年団・地域の習い事を地図で発見。みっけは、ネットに載っていない習い事を可視化するサービスです。">
    <meta property="og:title" content="みっけ（MIKKE）">
    <meta property="og:description" content="子どもの「好き」は、もうこの街に存在している。ただ、見えていないだけだ。">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@900&family=Noto+Sans+JP:wght@400;500;700&family=Noto+Serif+JP:wght@500&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    ink:          '#26304F',
                    'ink-mid':    '#5A6480',
                    'ink-soft':   '#9AA0B4',
                    cream:        '#FAF7F2',
                    'cream-mid':  '#F0EAE0',
                    'cream-border':'#E4DDD4',
                    white:        '#FFFFFF',
                    coral:        '#E8704A',
                    'coral-light':'#FBF0EB',
                    'coral-mid':  '#F4A882',
                    sage:         '#5D8F7C',
                    'sage-light': '#EBF4F0',
                    gold:         '#C9973A',
                    'gold-light': '#FBF3E3',
                },
                fontFamily: {
                    serif: ['Noto Serif JP', 'serif'],
                    sans:  ['Noto Sans JP', 'sans-serif'],
                },
                borderRadius: {
                    card:  '16px',
                    pill:  '20px',
                    input: '10px',
                },
                screens: { 'xs': '375px' },
            }
        }
    }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        window.__GOOGLE_MAPS_KEY = @json(config('services.google_maps.api_key'));
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.api_key') }}&libraries=marker,places&callback=Function.prototype" async defer></script>
    <script src="https://unpkg.com/@googlemaps/markerclusterer/dist/index.min.js"></script>
    <style>
        :root {
            --color-ink:         #26304F;
            --color-ink-mid:     #5A6480;
            --color-ink-soft:    #9AA0B4;
            --color-cream:       #FAF7F2;
            --color-cream-mid:   #F0EAE0;
            --color-border:      #E4DDD4;
            --color-white:       #FFFFFF;
            --color-coral:       #E8704A;
            --color-coral-light: #FBF0EB;
            --color-coral-mid:   #F4A882;
            --color-sage:        #5D8F7C;
            --color-sage-light:  #EBF4F0;
            --color-gold:        #C9973A;
            --color-gold-light:  #FBF3E3;
            --font-serif: 'Noto Serif JP', serif;
            --font-sans:  'Noto Sans JP', sans-serif;
            --radius-card:  16px;
            --radius-pill:  20px;
            --radius-input: 10px;
        }

        * { -webkit-tap-highlight-color: transparent; }
        html, body {
            background-color: var(--color-cream);
            color: var(--color-ink);
            font-family: var(--font-sans);
            font-weight: 400;
            -webkit-font-smoothing: antialiased;
            padding-bottom: env(safe-area-inset-bottom, 0px);
        }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        /* ===== Typography ===== */
        h1, h2, h3 { font-family: var(--font-serif); font-weight: 500; color: var(--color-ink); }
        h1 { font-size: 1.5rem; }
        h2 { font-size: 1.2rem; }
        h3 { font-size: 1rem; }

        /* ===== Animations ===== */
        @keyframes fadeUp { from { opacity:0; transform:translateY(16px); } to { opacity:1; transform:translateY(0); } }
        .fade-up { animation: fadeUp 0.4s cubic-bezier(0.22,1,0.36,1) forwards; opacity:0; }
        @keyframes scaleIn { from { opacity:0; transform:scale(0.92); } to { opacity:1; transform:scale(1); } }
        .scale-in { animation: scaleIn 0.3s cubic-bezier(0.22,1,0.36,1) forwards; }
        @keyframes slideIn { from { opacity:0; transform:translateX(-8px); } to { opacity:1; transform:translateX(0); } }
        .slide-in { animation: slideIn 0.3s ease-out forwards; }

        /* ===== Desktop shell ===== */
        @media (min-width: 640px) {
            body { background: var(--color-cream); min-height: 100vh; }
            .app-shell { max-width: 430px; margin: 0 auto; background: var(--color-cream); min-height: 100vh;
                border-left: 0.5px solid var(--color-border); border-right: 0.5px solid var(--color-border);
                position: relative; }
            .desktop-brand { display: flex; }
        }
        @media (max-width: 639px) {
            .app-shell { max-width: 100%; }
            .desktop-brand { display: none; }
        }

        /* ===== Card system ===== */
        .card { background: var(--color-white); border-radius: var(--radius-card); border: 0.5px solid var(--color-border);
            box-shadow: none; transition: transform 0.2s; }
        .card:active { transform: scale(0.985); }
        .card-elevated { background: var(--color-white); border: 0.5px solid var(--color-border); box-shadow: none; }

        /* ===== Section headers ===== */
        .section-label { font-size: 11px; font-weight: 700; letter-spacing: 0.02em; color: var(--color-ink-soft); }

        /* ===== Badge system ===== */
        .badge { display: inline-flex; align-items: center; gap: 3px; padding: 2px 10px; border-radius: var(--radius-pill); font-size: 10px; font-weight: 500; }
        .badge-green { background: var(--color-sage-light); color: var(--color-sage); }
        .badge-amber { background: var(--color-gold-light); color: var(--color-gold); }
        .badge-purple { background: #F5EDFC; color: #7c3aed; }
        .badge-blue { background: #EBF0F8; color: #3b5998; }
        .badge-red { background: var(--color-coral-light); color: var(--color-coral); }
        .badge-gray { background: var(--color-cream-mid); color: var(--color-ink-soft); }

        /* Status tags */
        .tag-open { background: var(--color-coral-light); color: var(--color-coral); font-size: 0.7rem; padding: 2px 10px; border-radius: var(--radius-pill); font-weight: 500; }
        .tag-full { background: var(--color-cream-mid); color: var(--color-ink-soft); font-size: 0.7rem; padding: 2px 10px; border-radius: var(--radius-pill); font-weight: 500; }
        .tag-few  { background: var(--color-gold-light); color: var(--color-gold); font-size: 0.7rem; padding: 2px 10px; border-radius: var(--radius-pill); font-weight: 500; }

        /* Genre colors */
        .genre-sports { background: var(--color-coral-light); }
        .genre-swim   { background: var(--color-sage-light); }
        .genre-art    { background: #F5EDFC; }
        .genre-music  { background: #EBF0F8; }
        .genre-study  { background: var(--color-gold-light); }
        .genre-other  { background: var(--color-cream-mid); }

        /* ===== Pill chips ===== */
        .chip { display: inline-flex; align-items: center; gap: 4px; padding: 6px 14px; border-radius: var(--radius-pill);
            font-size: 12px; font-weight: 500; border: 0.5px solid var(--color-border); color: var(--color-ink-mid);
            background: var(--color-white); cursor: pointer; transition: all 0.2s; white-space: nowrap; flex-shrink: 0; }
        .chip.active, .chip:hover { border-color: var(--color-coral); color: var(--color-coral); background: var(--color-coral-light); }

        /* ===== Input refinements ===== */
        .input-field { width: 100%; background: var(--color-white); border-radius: var(--radius-input); padding: 10px 14px;
            font-size: 14px; font-weight: 400; font-family: var(--font-sans); outline: none; border: 0.5px solid var(--color-border);
            color: var(--color-ink); transition: all 0.2s; }
        .input-field:focus { border-color: var(--color-ink); background: var(--color-white); box-shadow: none; }
        .input-field::placeholder { color: var(--color-ink-soft); }

        /* ===== Bottom nav ===== */
        .bottom-nav { background: rgba(255,255,255,0.95); backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%); border-top: 0.5px solid var(--color-border); }
        .nav-item { display: flex; flex-direction: column; align-items: center; justify-content: center;
            gap: 2px; transition: color 0.2s; position: relative; }
        .nav-item.active { color: var(--color-coral); }
        .nav-item.active::after { content: ''; position: absolute; bottom: -2px; width: 4px; height: 4px;
            border-radius: 50%; background: var(--color-coral); }

        /* ===== Tagline (kept for non-home pages if needed) ===== */

        /* ===== Page transitions ===== */
        .page-content { animation: fadeUp 0.3s cubic-bezier(0.22,1,0.36,1) forwards; }
    </style>
</head>
<body>

{{-- Desktop brand watermark --}}
<div class="desktop-brand fixed top-6 left-8 z-10 items-center gap-2.5 opacity-60">
    <span style="font-family:'Nunito',sans-serif;font-size:14px;font-weight:900;color:#9AA0B4;">みっけ</span>
</div>
<div class="desktop-brand fixed bottom-6 left-8 z-10 text-[11px] text-ink-soft font-medium opacity-60">
    子どもの「好き」は、もうこの街に存在している。
</div>

<div class="app-shell">

{{-- ===== Brand Header ===== --}}
<header class="sticky top-0 z-[2500] relative overflow-hidden" style="background-color:#CCFF66;border-bottom:4px solid #E8704A;">
    {{-- Decorative circle --}}
    <div style="position:absolute;top:-40px;right:-20px;width:130px;height:130px;background:rgba(255,255,255,0.15);border-radius:50%;pointer-events:none;"></div>

    <div style="display:flex;align-items:center;padding:12px 16px;gap:12px;position:relative;">
        {{-- Logo --}}
        <a href="{{ route('home') }}" class="active:scale-95 transition-transform" style="flex-shrink:0;">
            <x-mikke-logo />
        </a>

        {{-- Copy — トップページのみ表示 --}}
        @if(request()->routeIs('home') || request()->routeIs('spots.index'))
        <div style="flex:1;min-width:0;text-align:right;">
            <p style="font-size:11px;font-weight:700;color:#1a1a1a;line-height:1.5;margin:0;">
                子どもの「好き」は、<span style="color:#E8704A">もうこの街にある。</span>
            </p>
        </div>
        @endif
    </div>
</header>

<main class="page-content">
    @yield('content')
</main>

</div>{{-- .app-shell --}}

{{-- ===== FAB: スポット追加（登録ページ自体では非表示） ===== --}}
@unless(request()->routeIs('spots.create'))
<a id="fab-add-spot" href="{{ route('spots.create') }}"
   class="fixed z-[2900] active:scale-95 transition-all"
   style="bottom:80px;left:50%;transform:translateX(-50%);background:#E8704A;color:#fff;border-radius:50px;padding:14px 28px;font-size:15px;font-weight:700;text-decoration:none;box-shadow:0 4px 16px rgba(232,112,74,0.35);transition:opacity 0.3s;">
    ＋ スポット
</a>
@endunless

{{-- ===== Bottom Navigation ===== --}}
<nav class="fixed bottom-0 left-0 right-0 z-[3000]" style="padding-bottom: env(safe-area-inset-bottom, 0px);">
    <div class="app-shell mx-auto relative" style="border:none; min-height:auto;">
        <div class="bottom-nav rounded-t-2xl">
            <div class="grid grid-cols-3 h-[58px] items-center">
                @php
                    $tabs = [
                        ['route' => 'spots.index', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 1 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>', 'label' => 'マップ', 'match' => 'spots.*,home'],
                        ['route' => null, 'icon' => '', 'label' => '', 'match' => ''],
                        // GGA demo: hidden
                        // ['route' => 'mypage.index', 'icon' => '<svg ...>', 'label' => 'マイページ', 'match' => 'mypage.*'],
                    ];
                @endphp
                {{-- v2: 質問箱・アンバサダー通信タブ
                    ['route' => 'questions.index', 'icon' => '...', 'label' => '質問箱', 'match' => 'questions.*'],
                    ['route' => 'ambassador.index', 'icon' => '...', 'label' => '通信', 'match' => 'ambassador.*'],
                --}}

                @foreach($tabs as $tab)
                    @if($tab['route'])
                        @php $active = collect(explode(',', $tab['match']))->contains(fn($m) => request()->routeIs(trim($m))); @endphp
                        <a href="{{ route($tab['route']) }}" class="nav-item {{ $active ? 'active' : '' }}" style="color: {{ $active ? 'var(--color-coral)' : 'var(--color-ink-soft)' }};">
                            {!! $tab['icon'] !!}
                            <span class="text-[10px] font-medium">{{ $tab['label'] }}</span>
                        </a>
                    @else
                        <div></div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</nav>

{{-- v2: Post Menu Overlay（質問投稿メニュー含む）
<div id="post-menu-overlay" class="fixed inset-0 z-[4000] hidden">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="togglePostMenu()"></div>
    <div class="absolute bottom-24 left-4 right-4 max-w-md mx-auto space-y-2.5">
        <a href="{{ route('spots.create') }}"
           class="fade-up card flex items-center gap-4 w-full p-4 active:scale-[0.98] transition-transform"
           style="animation-delay:0.05s;">
            <div class="w-11 h-11 rounded-xl flex items-center justify-center" style="background: var(--color-coral-light);">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="var(--color-coral)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 1 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            </div>
            <div>
                <p class="font-medium text-sm" style="color: var(--color-ink);">スポットを登録</p>
                <p class="text-[11px] font-normal" style="color: var(--color-ink-soft);">習い事・公園・施設を地図に追加</p>
            </div>
        </a>
        <a href="{{ route('questions.create') }}"
           class="fade-up card flex items-center gap-4 w-full p-4 active:scale-[0.98] transition-transform"
           style="animation-delay:0.1s;">
            <div class="w-11 h-11 rounded-xl flex items-center justify-center" style="background: var(--color-gold-light);">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="var(--color-gold)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            </div>
            <div>
                <p class="font-medium text-sm" style="color: var(--color-ink);">みんなに質問</p>
                <p class="text-[11px] font-normal" style="color: var(--color-ink-soft);">地域の先輩パパママに聞いてみよう</p>
            </div>
        </a>
        <button onclick="togglePostMenu()"
            class="fade-up w-full py-3.5 text-sm font-medium active:scale-[0.98] transition-transform"
            style="border-radius: var(--radius-pill); background: var(--color-white); border: 0.5px solid var(--color-border); color: var(--color-ink); animation-delay:0.15s;">
            キャンセル
        </button>
    </div>
</div>
--}}

{{-- ===== Success Toast ===== --}}
@if(session('success'))
<div id="success-toast" class="fixed top-4 left-4 right-4 z-[5000] max-w-md mx-auto scale-in">
    <div class="px-5 py-3.5 flex items-center gap-3" style="background: var(--color-ink); color: #fff; border-radius: var(--radius-card);">
        <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0" style="background: rgba(250,247,242,0.15);">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <span class="text-sm font-medium">{{ session('success') }}</span>
    </div>
</div>
<script>setTimeout(function(){var t=document.getElementById('success-toast');if(t){t.style.transition='all 0.4s';t.style.opacity='0';t.style.transform='translateY(-20px)';setTimeout(function(){t.remove();},400);}},2500);</script>
@endif

{{-- v2: Post menu toggle
<script>
function togglePostMenu() {
    document.getElementById('post-menu-overlay').classList.toggle('hidden');
}
</script>
--}}
</body>
</html>
