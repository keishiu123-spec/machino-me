<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#f8faf9">
    <title>KIDS COMPASS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    brand: { 50:'#f0fdf4', 100:'#dcfce7', 200:'#bbf7d0', 300:'#86efac', 400:'#4ade80', 500:'#22c55e', 600:'#16a34a', 700:'#15803d', 800:'#166534', 900:'#14532d' },
                    surface: { 50:'#fafcfb', 100:'#f3f6f4' },
                },
                fontFamily: { sans: ['-apple-system','BlinkMacSystemFont','Hiragino Sans','Yu Gothic UI','sans-serif'] },
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
        * { -webkit-tap-highlight-color: transparent; }
        body { padding-bottom: env(safe-area-inset-bottom, 0px); }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        /* ===== Animations ===== */
        @keyframes fadeUp { from { opacity:0; transform:translateY(16px); } to { opacity:1; transform:translateY(0); } }
        .fade-up { animation: fadeUp 0.4s cubic-bezier(0.22,1,0.36,1) forwards; opacity:0; }
        @keyframes scaleIn { from { opacity:0; transform:scale(0.92); } to { opacity:1; transform:scale(1); } }
        .scale-in { animation: scaleIn 0.3s cubic-bezier(0.22,1,0.36,1) forwards; }
        @keyframes slideIn { from { opacity:0; transform:translateX(-8px); } to { opacity:1; transform:translateX(0); } }
        .slide-in { animation: slideIn 0.3s ease-out forwards; }

        /* ===== Desktop shell ===== */
        @media (min-width: 640px) {
            body { background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 30%, #f0f9ff 70%, #faf5ff 100%); min-height: 100vh; }
            .app-shell { max-width: 430px; margin: 0 auto; background: white; min-height: 100vh;
                box-shadow: 0 0 60px rgba(0,0,0,0.08), 0 0 0 1px rgba(0,0,0,0.03);
                border-radius: 0; position: relative; }
            .desktop-brand { display: flex; }
        }
        @media (max-width: 639px) {
            .app-shell { max-width: 100%; }
            .desktop-brand { display: none; }
        }

        /* ===== Card system ===== */
        .card { background: white; border-radius: 20px; border: 1px solid rgba(0,0,0,0.04);
            box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 4px 12px rgba(0,0,0,0.02); transition: transform 0.2s, box-shadow 0.2s; }
        .card:active { transform: scale(0.985); }
        .card-elevated { box-shadow: 0 4px 20px rgba(0,0,0,0.06), 0 1px 3px rgba(0,0,0,0.04); }

        /* ===== Section headers ===== */
        .section-label { font-size: 11px; font-weight: 800; letter-spacing: 0.02em; text-transform: uppercase; color: #9ca3af; }

        /* ===== Badge system ===== */
        .badge { display: inline-flex; align-items: center; gap: 3px; padding: 3px 8px; border-radius: 8px; font-size: 10px; font-weight: 700; }
        .badge-green { background: #dcfce7; color: #15803d; }
        .badge-amber { background: #fef3c7; color: #92400e; }
        .badge-purple { background: #f3e8ff; color: #7c3aed; }
        .badge-blue { background: #dbeafe; color: #1e40af; }
        .badge-red { background: #fee2e2; color: #dc2626; }
        .badge-gray { background: #f3f4f6; color: #6b7280; }

        /* ===== Pill chips ===== */
        .chip { display: inline-flex; align-items: center; gap: 4px; padding: 6px 14px; border-radius: 100px;
            font-size: 12px; font-weight: 700; border: 1.5px solid #e5e7eb; color: #6b7280;
            background: white; cursor: pointer; transition: all 0.2s; white-space: nowrap; flex-shrink: 0; }
        .chip.active, .chip:hover { border-color: #22c55e; color: #15803d; background: #f0fdf4; }

        /* ===== Input refinements ===== */
        .input-field { width: 100%; background: #f9fafb; border-radius: 14px; padding: 12px 16px;
            font-size: 14px; font-weight: 500; outline: none; border: 1.5px solid #f0f0f0;
            transition: all 0.2s; }
        .input-field:focus { border-color: #22c55e; box-shadow: 0 0 0 3px rgba(34,197,94,0.1); background: white; }
        .input-field::placeholder { color: #cbd5e1; }

        /* ===== Bottom nav glass ===== */
        .bottom-nav { background: rgba(255,255,255,0.92); backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%); border-top: 1px solid rgba(0,0,0,0.05); }
        .nav-item { display: flex; flex-direction: column; align-items: center; justify-content: center;
            gap: 2px; transition: color 0.2s; position: relative; }
        .nav-item.active { color: #16a34a; }
        .nav-item.active::after { content: ''; position: absolute; bottom: -2px; width: 4px; height: 4px;
            border-radius: 50%; background: #22c55e; }

        /* ===== Page transitions ===== */
        .page-content { animation: fadeUp 0.3s cubic-bezier(0.22,1,0.36,1) forwards; }
    </style>
</head>
<body class="text-gray-900 font-sans antialiased">

{{-- Desktop brand watermark --}}
<div class="desktop-brand fixed top-6 left-8 z-10 items-center gap-2.5 opacity-60">
    <div class="w-8 h-8 bg-brand-500 rounded-xl flex items-center justify-center">
        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="none" stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 11l3-3 2 2 4-4 3 3"/><circle cx="8" cy="4" r="1.5"/></svg>
    </div>
    <span class="text-sm font-black text-gray-400 tracking-tight">KIDS <span class="text-brand-400">COMPASS</span></span>
</div>
<div class="desktop-brand fixed bottom-6 left-8 z-10 text-[11px] text-gray-300 font-medium opacity-60">
    地域の習い事を、もっと身近に。
</div>

<div class="app-shell">

{{-- ===== Brand Header ===== --}}
<header class="sticky top-0 z-[2500] bg-white/95 backdrop-blur-xl border-b border-gray-100/50">
    <div class="flex items-center justify-center py-2 px-5">
        <a href="{{ route('home') }}" class="flex items-center gap-2 active:scale-95 transition-transform">
            <div class="w-7 h-7 bg-gradient-to-br from-brand-400 to-brand-600 rounded-lg flex items-center justify-center shadow-[0_2px_8px_rgba(34,197,94,0.25)]">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" stroke="white" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M3 11l3-3 2 2 4-4 3 3"/><circle cx="8" cy="4" r="1.5"/></svg>
            </div>
            <span class="text-[13px] font-black tracking-tight text-gray-800">KIDS <span class="text-brand-500">COMPASS</span></span>
        </a>
    </div>
</header>

<main class="page-content">
    @yield('content')
</main>

</div>{{-- .app-shell --}}

{{-- ===== Bottom Navigation ===== --}}
<nav class="fixed bottom-0 left-0 right-0 z-[3000]" style="padding-bottom: env(safe-area-inset-bottom, 0px);">
    <div class="app-shell mx-auto relative" style="box-shadow:none; min-height:auto;">
        {{-- FAB --}}
        <div class="absolute -top-7 left-1/2 -translate-x-1/2 z-[3001]">
            <button onclick="togglePostMenu()"
                class="w-[52px] h-[52px] bg-gradient-to-br from-brand-400 to-brand-600 rounded-2xl shadow-[0_6px_20px_rgba(34,197,94,0.4)] flex items-center justify-center text-white border-[3px] border-white active:scale-90 transition-all duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            </button>
        </div>

        <div class="bottom-nav rounded-t-2xl">
            <div class="grid grid-cols-5 h-[58px] items-center">
                @php
                    $tabs = [
                        ['route' => 'spots.index', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 1 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>', 'label' => 'マップ', 'match' => 'spots.*,home'],
                        ['route' => 'questions.index', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>', 'label' => '質問箱', 'match' => 'questions.*'],
                        ['route' => null, 'icon' => '', 'label' => '', 'match' => ''],
                        ['route' => 'ambassador.index', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>', 'label' => '通信', 'match' => 'ambassador.*'],
                        ['route' => 'mypage.index', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>', 'label' => 'マイページ', 'match' => 'mypage.*'],
                    ];
                @endphp

                @foreach($tabs as $tab)
                    @if($tab['route'])
                        @php $active = collect(explode(',', $tab['match']))->contains(fn($m) => request()->routeIs(trim($m))); @endphp
                        <a href="{{ route($tab['route']) }}" class="nav-item {{ $active ? 'active text-brand-600' : 'text-gray-400' }}">
                            {!! $tab['icon'] !!}
                            <span class="text-[10px] font-bold">{{ $tab['label'] }}</span>
                        </a>
                    @else
                        <div></div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</nav>

{{-- ===== Post Menu Overlay ===== --}}
<div id="post-menu-overlay" class="fixed inset-0 z-[4000] hidden">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="togglePostMenu()"></div>
    <div class="absolute bottom-24 left-4 right-4 max-w-md mx-auto space-y-2.5">
        <a href="{{ route('spots.create') }}"
           class="fade-up card flex items-center gap-4 w-full p-4 active:scale-[0.98] transition-transform"
           style="animation-delay:0.05s;">
            <div class="w-11 h-11 bg-gradient-to-br from-brand-50 to-brand-100 rounded-xl flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="#16a34a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 1 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            </div>
            <div>
                <p class="font-bold text-sm text-gray-900">スポットを登録</p>
                <p class="text-[11px] text-gray-400 font-medium">習い事・公園・施設を地図に追加</p>
            </div>
        </a>
        <a href="{{ route('questions.create') }}"
           class="fade-up card flex items-center gap-4 w-full p-4 active:scale-[0.98] transition-transform"
           style="animation-delay:0.1s;">
            <div class="w-11 h-11 bg-gradient-to-br from-amber-50 to-amber-100 rounded-xl flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="#d97706" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            </div>
            <div>
                <p class="font-bold text-sm text-gray-900">みんなに質問</p>
                <p class="text-[11px] text-gray-400 font-medium">地域の先輩パパママに聞いてみよう</p>
            </div>
        </a>
        <button onclick="togglePostMenu()"
            class="fade-up w-full py-3.5 rounded-2xl text-gray-500 font-bold text-sm bg-white/90 backdrop-blur shadow-lg active:scale-[0.98] transition-transform"
            style="animation-delay:0.15s;">
            キャンセル
        </button>
    </div>
</div>

{{-- ===== Success Toast ===== --}}
@if(session('success'))
<div id="success-toast" class="fixed top-4 left-4 right-4 z-[5000] max-w-md mx-auto scale-in">
    <div class="bg-gradient-to-r from-brand-500 to-brand-600 text-white px-5 py-3.5 rounded-2xl shadow-[0_8px_30px_rgba(34,197,94,0.3)] flex items-center gap-3">
        <div class="w-7 h-7 bg-white/20 rounded-lg flex items-center justify-center flex-shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <span class="text-sm font-bold">{{ session('success') }}</span>
    </div>
</div>
<script>setTimeout(function(){var t=document.getElementById('success-toast');if(t){t.style.transition='all 0.4s';t.style.opacity='0';t.style.transform='translateY(-20px)';setTimeout(function(){t.remove();},400);}},2500);</script>
@endif

<script>
function togglePostMenu() {
    document.getElementById('post-menu-overlay').classList.toggle('hidden');
}
</script>
</body>
</html>
