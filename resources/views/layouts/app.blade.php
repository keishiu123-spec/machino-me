<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#26304F">
    <title>みっけ | 子どもの「好き」が見つかる場所</title>
    <meta name="description" content="個人教室・スポーツ少年団・地域の習い事を地図で発見。みっけは、ネットに載っていない習い事を可視化するサービスです。">
    <meta property="og:title" content="みっけ（MIKKE）">
    <meta property="og:description" content="子どもの「好き」は、もうこの街に存在している。ただ、見えていないだけだ。">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&family=Noto+Serif+JP:wght@500&display=swap" rel="stylesheet">
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
                    card:  '12px',
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
            --radius-card:  12px;
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

        /* ===== Tagline ===== */
        .tagline-main { font-family: var(--font-serif); font-size: 0.95rem; font-weight: 500; color: #ffffff; line-height: 1.5; margin-bottom: 2px; }
        .tagline-sub  { font-size: 0.75rem; color: rgba(250,247,242,0.5); }

        /* ===== Page transitions ===== */
        .page-content { animation: fadeUp 0.3s cubic-bezier(0.22,1,0.36,1) forwards; }
    </style>
</head>
<body>

{{-- Desktop brand watermark --}}
<div class="desktop-brand fixed top-6 left-8 z-10 items-center gap-2.5 opacity-60">
    <span class="font-serif text-sm text-ink-soft tracking-tight">みっけ</span>
</div>
<div class="desktop-brand fixed bottom-6 left-8 z-10 text-[11px] text-ink-soft font-medium opacity-60">
    子どもの「好き」は、もうこの街に存在している。
</div>

<div class="app-shell">

{{-- ===== Brand Header ===== --}}
<header class="sticky top-0 z-[2500]" style="background-color: var(--color-ink);">
    <div class="px-4 pt-3 pb-2.5">
        <div class="flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-2 active:scale-95 transition-transform">
                <span class="font-serif text-[1.25rem] tracking-[0.03em]" style="color: var(--color-cream);">みっけ</span>
            </a>
            @auth
            <div class="flex items-center gap-2">
                <a href="{{ route('mypage.index') }}" class="flex items-center gap-1.5 active:scale-95 transition-transform">
                    @if(auth()->user()->display_avatar)
                        <img src="{{ auth()->user()->display_avatar }}" alt="" class="w-7 h-7 rounded-full object-cover" style="border: 0.5px solid rgba(250,247,242,0.3);">
                    @else
                        <div class="w-7 h-7 rounded-full flex items-center justify-center" style="background: rgba(250,247,242,0.15);">
                            <span class="text-[11px] font-medium" style="color: var(--color-cream);">{{ mb_substr(auth()->user()->name, 0, 1) }}</span>
                        </div>
                    @endif
                </a>
            </div>
            @else
            <a href="{{ route('auth.line') }}"
               class="flex items-center gap-1.5 bg-[#06C755] text-white text-[11px] font-medium px-3 py-1.5 rounded-pill active:scale-95 transition-all" style="border-radius: var(--radius-pill);">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="white"><path d="M19.365 9.863c.349 0 .63.285.63.631 0 .345-.281.63-.63.63H17.61v1.125h1.755c.349 0 .63.283.63.63 0 .344-.281.629-.63.629h-2.386c-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.63-.63h2.386c.346 0 .627.285.627.63 0 .349-.281.63-.63.63H17.61v1.125h1.755zm-3.855 3.016c0 .27-.174.51-.432.596-.064.021-.133.031-.199.031-.211 0-.391-.09-.51-.25l-2.443-3.317v2.94c0 .344-.279.629-.631.629-.346 0-.626-.285-.626-.629V8.108c0-.27.173-.51.43-.595.06-.023.136-.033.194-.033.195 0 .375.104.495.254l2.462 3.33V8.108c0-.345.282-.63.63-.63.345 0 .63.285.63.63v4.771zm-5.741 0c0 .344-.282.629-.631.629-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.63-.63.346 0 .628.285.628.63v4.771zm-2.466.629H4.917c-.345 0-.63-.285-.63-.629V8.108c0-.345.285-.63.63-.63.348 0 .63.285.63.63v4.141h1.756c.348 0 .629.283.629.63 0 .344-.282.629-.629.629M24 10.314C24 4.943 18.615.572 12 .572S0 4.943 0 10.314c0 4.811 4.27 8.842 10.035 9.608.391.082.923.258 1.058.59.12.301.079.766.038 1.08l-.164 1.02c-.045.301-.24 1.186 1.049.645 1.291-.539 6.916-4.078 9.436-6.975C23.176 14.393 24 12.458 24 10.314"/></svg>
                LINEログイン
            </a>
            @endauth
        </div>
        @if(request()->routeIs('home') || request()->routeIs('spots.index'))
        <div class="mt-2">
            <p class="tagline-main">子どもの「好き」は、もうこの街に存在している。</p>
            <p class="tagline-sub">ただ、見えていないだけだ。</p>
        </div>
        @endif
    </div>
</header>

<main class="page-content">
    @yield('content')
</main>

</div>{{-- .app-shell --}}

{{-- ===== Bottom Navigation ===== --}}
<nav class="fixed bottom-0 left-0 right-0 z-[3000]" style="padding-bottom: env(safe-area-inset-bottom, 0px);">
    <div class="app-shell mx-auto relative" style="border:none; min-height:auto;">
        {{-- FAB — スポット登録への直リンク --}}
        <div class="absolute -top-7 left-1/2 -translate-x-1/2 z-[3001]">
            <a href="{{ route('spots.create') }}"
                class="w-[52px] h-[52px] rounded-2xl flex items-center justify-center text-white border-[3px] border-white active:scale-90 transition-all duration-200"
                style="background: var(--color-coral);">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            </a>
        </div>

        <div class="bottom-nav rounded-t-2xl">
            <div class="grid grid-cols-3 h-[58px] items-center">
                @php
                    $tabs = [
                        ['route' => 'spots.index', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 1 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>', 'label' => 'マップ', 'match' => 'spots.*,home'],
                        ['route' => null, 'icon' => '', 'label' => '', 'match' => ''],
                        ['route' => 'mypage.index', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>', 'label' => 'マイページ', 'match' => 'mypage.*'],
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
