<!DOCTYPE html>
<html lang="en" class="">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $appTitleEn ?? 'Village Agro Farm' }} - Income & Expense Ledger</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;600;700;800;900&family=Noto+Sans+Bengali:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Noto Sans"', '"Noto Sans Bengali"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        /* Interface Size */
        .size-small { font-size: 13px; }
        .size-medium { font-size: 16px; }
        .size-big { font-size: 18px; }
    </style>
</head>
<body class="min-h-screen bg-slate-50 dark:bg-slate-950 text-gray-800 dark:text-gray-100 flex flex-col font-sans transition-all duration-300 antialiased selection:bg-emerald-600 selection:text-white size-{{ $interfaceSize ?? 'medium' }}">
    {{-- Header --}}
    <header class="sticky top-0 z-40 bg-white dark:bg-slate-900 border-b border-gray-200 dark:border-slate-800 px-4 sm:px-6 lg:px-8 xl:px-10 py-4">
        <div class="max-w-[1920px] mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-emerald-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                </div>
                <div>
                    <h1 class="text-xl font-black text-gray-900 dark:text-white">{{ $appTitleEn ?? 'Village Agro Farm' }}</h1>
                    <p class="text-[11px] font-semibold tracking-widest text-gray-500 dark:text-gray-400 uppercase">{{ __("ui.subtitle") }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <button onclick="toggleDarkMode()" class="flex items-center justify-center rounded-xl border border-gray-200 dark:border-slate-700 py-2 px-3 text-xs font-bold text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-800 transition-all cursor-pointer">
                    <span id="theme-icon">🌙</span>
                    <span class="ml-1.5" id="theme-text">Dark</span>
                </button>
                <a href="/lang/{{ ($lang ?? 'en') === 'en' ? 'bn' : 'en' }}" class="flex items-center gap-1.5 rounded-xl border border-gray-200 dark:border-slate-700 py-2 px-3 text-xs font-bold text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-800 transition-all cursor-pointer">
                    @if(($lang ?? 'en') === 'en')🇧🇩 <span>বাংলা</span>@else🇬🇧 <span>English</span>@endif
                </a>
                @auth
                <div class="flex items-center gap-2 ml-2">
                    <div class="text-right">
                        <p class="text-xs font-bold text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] text-gray-500">{{ ucfirst(Auth::user()->role) }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="inline" id="logout-form">
                        @csrf
                        <button type="button" onclick="document.getElementById('logout-modal').classList.remove('hidden')" class="flex items-center justify-center h-9 w-9 rounded-xl border border-gray-200 dark:border-slate-700 text-gray-500 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/20 transition-all cursor-pointer">
                            🚪
                        </button>
                    </form>
                @endauth
            </div>
        </div>
    </header>

    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="max-w-[1580px] xl:max-w-[1780px] 2xl:max-w-[1920px] w-full mx-auto px-4 sm:px-6 lg:px-8 xl:px-10 mt-4">
        <div class="p-3 bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800 rounded-xl text-emerald-700 dark:text-emerald-300 text-sm font-medium">
            ✅ {{ session('success') }}
        </div>
    </div>
    @endif
    @if(session('error'))
    <div class="max-w-[1580px] xl:max-w-[1780px] 2xl:max-w-[1920px] w-full mx-auto px-4 sm:px-6 lg:px-8 xl:px-10 mt-4">
        <div class="p-3 bg-rose-50 dark:bg-rose-950/30 border border-rose-200 dark:border-rose-800 rounded-xl text-rose-700 dark:text-rose-300 text-sm font-medium">
            ❌ {{ session('error') }}
        </div>
    </div>
    @endif

    {{-- Main Layout Container --}}
    <div class="flex-1 max-w-[1580px] xl:max-w-[1780px] 2xl:max-w-[1920px] w-full mx-auto px-4 sm:px-6 lg:px-8 xl:px-10 py-5 lg:py-8 grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-start">

        {{-- Mobile Navigation Tabs --}}
        @auth
        <div class="lg:hidden w-full sticky top-[72px] z-30 bg-white dark:bg-slate-900 border border-gray-150 dark:border-slate-800/80 p-2.5 rounded-2xl shadow-sm flex items-center overflow-x-auto no-scrollbar gap-1.5">
            <a href="/dashboard" class="flex items-center gap-1.5 py-2 px-4 rounded-xl text-xs font-bold transition-all cursor-pointer whitespace-nowrap {{ request()->is('dashboard') ? 'bg-emerald-600 text-white shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100/60 dark:hover:bg-slate-800' }}">
                📊 Dashboard
            </a>
            <a href="/transactions/create" class="flex items-center gap-1.5 py-2 px-4 rounded-xl text-xs font-bold transition-all cursor-pointer whitespace-nowrap {{ request()->is('transactions/create') ? 'bg-emerald-600 text-white shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100/60 dark:hover:bg-slate-800' }}">
                📝 Add Transaction
            </a>
            <a href="/projects" class="flex items-center gap-1.5 py-2 px-4 rounded-xl text-xs font-bold transition-all cursor-pointer whitespace-nowrap {{ request()->is('projects') ? 'bg-emerald-600 text-white shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100/60 dark:hover:bg-slate-800' }}">
                💹 Project Investments
            </a>
            <a href="/transactions/analytics" class="flex items-center gap-1.5 py-2 px-4 rounded-xl text-xs font-bold transition-all cursor-pointer whitespace-nowrap {{ request()->is('transactions/analytics') ? 'bg-emerald-600 text-white shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100/60 dark:hover:bg-slate-800' }}">
                📈 Category Analytics
            </a>
            <a href="/transactions/history" class="flex items-center gap-1.5 py-2 px-4 rounded-xl text-xs font-bold transition-all cursor-pointer whitespace-nowrap {{ request()->is('transactions/history') ? 'bg-emerald-600 text-white shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100/60 dark:hover:bg-slate-800' }}">
                📋 Transaction History
            </a>
            <a href="/accounting" class="flex items-center gap-1.5 py-2 px-4 rounded-xl text-xs font-bold transition-all cursor-pointer whitespace-nowrap {{ request()->is('accounting') ? 'bg-emerald-600 text-white shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100/60 dark:hover:bg-slate-800' }}">
                🧾 Accounting Suite
            </a>
            <a href="/control-panel" class="flex items-center gap-1.5 py-2 px-4 rounded-xl text-xs font-bold transition-all cursor-pointer whitespace-nowrap {{ request()->is('control-panel') ? 'bg-emerald-600 text-white shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100/60 dark:hover:bg-slate-800' }}">
                ⚙️ Control Panel
            </a>
            @if(Auth::user()->isAdmin())
            <a href="/admin-panel" class="flex items-center gap-1.5 py-2 px-4 rounded-xl text-xs font-bold transition-all cursor-pointer whitespace-nowrap {{ request()->is('admin-panel') ? 'bg-emerald-600 text-white shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100/60 dark:hover:bg-slate-800' }}">
                🛡️ Admin Panel
            </a>
            @endif
        </div>
        @endauth

        {{-- Desktop Sidebar --}}
        @auth
        <aside class="hidden lg:block lg:col-span-3 xl:col-span-2.5 lg:sticky lg:top-[90px] bg-white dark:bg-slate-900 rounded-2xl border border-gray-150 dark:border-slate-800/80 p-5 shadow-sm space-y-2.5 self-start">
            <h2 class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-4 px-2">General Ledger Navigation</h2>

            <a href="/dashboard" class="w-full flex items-center justify-between py-3 px-4 rounded-xl text-xs font-bold tracking-tight transition-all border-l-4 {{ request()->is('dashboard') ? 'bg-gradient-to-r from-emerald-50/90 to-transparent dark:from-emerald-950/45 text-emerald-800 dark:text-emerald-400 border-emerald-500' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50/50 dark:hover:bg-slate-800/40 hover:text-gray-900 dark:hover:text-gray-100 border-transparent' }}">
                <span class="flex items-center gap-2.5">📊 Dashboard</span>
                <span class="text-gray-300 dark:text-gray-600">→</span>
            </a>

            <a href="/transactions/create" class="w-full flex items-center justify-between py-3 px-4 rounded-xl text-xs font-bold tracking-tight transition-all border-l-4 {{ request()->is('transactions/create') ? 'bg-gradient-to-r from-emerald-50/90 to-transparent dark:from-emerald-950/45 text-emerald-800 dark:text-emerald-400 border-emerald-500' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50/50 dark:hover:bg-slate-800/40 hover:text-gray-900 dark:hover:text-gray-100 border-transparent' }}">
                <span class="flex items-center gap-2.5">📝 Add Transaction</span>
                <span class="text-gray-300 dark:text-gray-600">→</span>
            </a>

            <a href="/projects" class="w-full flex items-center justify-between py-3 px-4 rounded-xl text-xs font-bold tracking-tight transition-all border-l-4 {{ request()->is('projects') ? 'bg-gradient-to-r from-emerald-50/90 to-transparent dark:from-emerald-950/45 text-emerald-800 dark:text-emerald-400 border-emerald-500' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50/50 dark:hover:bg-slate-800/40 hover:text-gray-900 dark:hover:text-gray-100 border-transparent' }}">
                <span class="flex items-center gap-2.5">💹 Project Investments</span>
                <span class="text-gray-300 dark:text-gray-600">→</span>
            </a>

            <a href="/transactions/analytics" class="w-full flex items-center justify-between py-3 px-4 rounded-xl text-xs font-bold tracking-tight transition-all border-l-4 {{ request()->is('transactions/analytics') ? 'bg-gradient-to-r from-emerald-50/90 to-transparent dark:from-emerald-950/45 text-emerald-800 dark:text-emerald-400 border-emerald-500' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50/50 dark:hover:bg-slate-800/40 hover:text-gray-900 dark:hover:text-gray-100 border-transparent' }}">
                <span class="flex items-center gap-2.5">📈 Category Analytics</span>
                <span class="text-gray-300 dark:text-gray-600">→</span>
            </a>

            <a href="/transactions/history" class="w-full flex items-center justify-between py-3 px-4 rounded-xl text-xs font-bold tracking-tight transition-all border-l-4 {{ request()->is('transactions/history') ? 'bg-gradient-to-r from-emerald-50/90 to-transparent dark:from-emerald-950/45 text-emerald-800 dark:text-emerald-400 border-emerald-500' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50/50 dark:hover:bg-slate-800/40 hover:text-gray-900 dark:hover:text-gray-100 border-transparent' }}">
                <span class="flex items-center gap-2.5">📋 Transaction History</span>
                <span class="text-gray-300 dark:text-gray-600">→</span>
            </a>

            <a href="/accounting" class="w-full flex items-center justify-between py-3 px-4 rounded-xl text-xs font-bold tracking-tight transition-all border-l-4 {{ request()->is('accounting') ? 'bg-gradient-to-r from-emerald-50/90 to-transparent dark:from-emerald-950/45 text-emerald-800 dark:text-emerald-400 border-emerald-500' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50/50 dark:hover:bg-slate-800/40 hover:text-gray-900 dark:hover:text-gray-100 border-transparent' }}">
                <span class="flex items-center gap-2.5">🧾 Accounting Suite</span>
                <span class="text-gray-300 dark:text-gray-600">→</span>
            </a>

            <a href="/control-panel" class="w-full flex items-center justify-between py-3 px-4 rounded-xl text-xs font-bold tracking-tight transition-all border-l-4 {{ request()->is('control-panel') ? 'bg-gradient-to-r from-emerald-50/90 to-transparent dark:from-emerald-950/45 text-emerald-800 dark:text-emerald-400 border-emerald-500' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50/50 dark:hover:bg-slate-800/40 hover:text-gray-900 dark:hover:text-gray-100 border-transparent' }}">
                <span class="flex items-center gap-2.5">⚙️ Control Panel</span>
                <span class="text-gray-300 dark:text-gray-600">→</span>
            </a>

            @if(Auth::user()->isAdmin())
            <a href="/admin-panel" class="w-full flex items-center justify-between py-3 px-4 rounded-xl text-xs font-bold tracking-tight transition-all border-l-4 {{ request()->is('admin-panel') ? 'bg-gradient-to-r from-emerald-50/90 to-transparent dark:from-emerald-950/45 text-emerald-800 dark:text-emerald-400 border-emerald-500' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50/50 dark:hover:bg-slate-800/40 hover:text-gray-900 dark:hover:text-gray-100 border-transparent' }}">
                <span class="flex items-center gap-2.5">🛡️ Admin Panel</span>
                <span class="text-gray-300 dark:text-gray-600">→</span>
            </a>
            @endif

            {{-- Dynamic Vault State --}}
            <div class="pt-4 mt-6 border-t border-gray-100 dark:border-slate-800/80 hidden lg:block text-xs">
                <div class="bg-slate-50 dark:bg-slate-950 rounded-xl p-3 border border-gray-150/50 dark:border-slate-800/80">
                    <p class="text-gray-400 dark:text-gray-500 font-bold uppercase text-[9px] mb-2 tracking-wide">Dynamic Vault State</p>
                    <p class="text-gray-400 dark:text-gray-400 mt-1">Net Flow Balance:</p>
                    <p class="font-black text-sm mt-0.5 text-emerald-700 dark:text-emerald-450">৳ {{ number_format($netBalance ?? 0) }}</p>
                </div>
            </div>
        </aside>
        @endauth

        {{-- Main Content --}}
        <main class="lg:col-span-9 xl:col-span-9 space-y-6">
            @yield('content')
        </main>

    </div>

    {{-- Footer --}}
    <footer class="bg-white dark:bg-slate-900 border-t border-gray-200 dark:border-slate-800 px-6 py-5 text-center mt-auto">
        <p class="text-xs text-gray-500 dark:text-gray-400">© 2026 Village Agro Farm secure bilingual system. Built using Node, TypeScript/React & Google relational structure engines. Handled securely with active session encryption.</p>
    </footer>

    <script>
        function toggleDarkMode() {
            document.documentElement.classList.toggle('dark');
            const isDark = document.documentElement.classList.contains('dark');
            localStorage.setItem('darkMode', isDark);
            document.getElementById('theme-icon').textContent = isDark ? '☀️' : '🌙';
            document.getElementById('theme-text').textContent = isDark ? 'Light' : 'Dark';
        }

        // Load saved preference
        if (localStorage.getItem('darkMode') === 'true') {
            document.documentElement.classList.add('dark');
            document.getElementById('theme-icon').textContent = '☀️';
            document.getElementById('theme-text').textContent = 'Light';
        }
    </script>
{{-- Logout Confirmation Modal --}}
<div id="logout-modal" class="hidden fixed inset-0 z-[999] flex items-center justify-center p-4" style="background:rgba(0,0,0,0.5)">
    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden">
        <div class="p-6 text-center space-y-4">
            <div class="mx-auto w-14 h-14 rounded-full bg-rose-50 dark:bg-rose-950/30 flex items-center justify-center">
                <span class="text-2xl">🚪</span>
            </div>
            <h3 class="text-lg font-black text-gray-900 dark:text-white">{{ __("ui.logout") }}?</h3>
            <p class="text-sm text-gray-500">Are you sure you want to sign out of your account?</p>
            <div class="flex gap-3 justify-center pt-2">
                <button onclick="document.getElementById('logout-modal').classList.add('hidden')" class="px-5 py-2.5 rounded-xl border border-gray-200 dark:border-slate-700 text-sm font-bold text-gray-600 hover:bg-gray-50 dark:hover:bg-slate-800 transition-all cursor-pointer">{{ __("ui.cancel") }}</button>
                <button onclick="document.getElementById('logout-form').submit()" class="px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-sm font-bold transition-all cursor-pointer shadow-sm">{{ __("ui.logout") }}</button>
            </div>
        </div>
    </div>
</div>
</body>
</html>
