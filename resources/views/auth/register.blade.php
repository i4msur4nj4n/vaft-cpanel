<!DOCTYPE html>
<html lang="en" class="">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Village Agro Farm - Sign Up</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;600;700;800;900&family=Noto+Sans+Bengali:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = { darkMode: 'class', theme: { extend: { fontFamily: { sans: ['"Noto Sans"', '"Noto Sans Bengali"', 'sans-serif'] } } } }</script>
</head>
<body class="min-h-screen bg-slate-50 dark:bg-slate-950 font-sans antialiased">
    {{-- Header --}}
    <header class="bg-white dark:bg-slate-900 border-b border-gray-200 dark:border-slate-800 px-6 py-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <svg width="36" height="43" viewBox="0 0 200 240" xmlns="http://www.w3.org/2000/svg"><defs><linearGradient id="lb" x1="0%" y1="0%" x2="0%" y2="100%"><stop offset="0%" stop-color="#F97316"/><stop offset="35%" stop-color="#FBBF24"/><stop offset="70%" stop-color="#10B981"/><stop offset="100%" stop-color="#15803D"/></linearGradient><radialGradient id="ls" cx="50%" cy="50%" r="50%"><stop offset="0%" stop-color="#FFFBEB"/><stop offset="40%" stop-color="#FBBF24"/><stop offset="85%" stop-color="#F97316"/><stop offset="100%" stop-color="#EA580C"/></radialGradient><linearGradient id="ll" x1="0%" y1="100%" x2="0%" y2="0%"><stop offset="0%" stop-color="#14532D"/><stop offset="45%" stop-color="#16A34A"/><stop offset="100%" stop-color="#4ADE80"/></linearGradient></defs><rect x="12" y="12" width="176" height="216" rx="88" ry="88" fill="#FFF" stroke="url(#lb)" stroke-width="11"/><g><polygon points="63,101 54,103 62,109" fill="#F97316"/><polygon points="66,85 58,82 69,76" fill="#F97316"/><polygon points="78,72 72,66 83,63" fill="#F97316"/><polygon points="91,65 92,56 99,64" fill="#F97316"/><polygon points="109,65 108,56 101,64" fill="#F97316"/><polygon points="122,72 128,66 117,63" fill="#F97316"/><polygon points="134,85 142,82 131,76" fill="#F97316"/><polygon points="137,101 146,103 138,109" fill="#F97316"/><circle cx="100" cy="105" r="32" fill="url(#ls)"/></g><g><path d="M 100 135 L 100 155" stroke="#16A34A" stroke-width="6" stroke-linecap="round"/><path d="M 100 148 C 72 138, 56 102, 72 78 C 88 110, 100 114, 100 148" fill="url(#ll)"/><path d="M 100 148 C 128 138, 144 102, 128 78 C 112 110, 100 114, 100 148" fill="url(#ll)"/></g><g><path d="M 52 170 C 72 178, 82 165, 100 171 C 118 177, 128 164, 148 170" fill="none" stroke="#14532D" stroke-width="10" stroke-linecap="round"/><path d="M 52 192 C 72 200, 82 187, 100 193 C 118 199, 128 186, 148 192" fill="none" stroke="#14532D" stroke-width="10" stroke-linecap="round"/></g></svg>
            <div>
                <h1 class="text-lg font-black text-gray-900 dark:text-white">Village Agro Farm</h1>
                <p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">INCOME & EXPENSE LEDGER</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="document.documentElement.classList.toggle('dark')" class="flex items-center gap-1.5 rounded-xl border border-gray-200 dark:border-slate-700 py-2 px-3 text-xs font-bold text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-800 transition-all cursor-pointer">🌙 Dark</button>
            <button class="flex items-center gap-1.5 rounded-xl border border-gray-200 dark:border-slate-700 py-2 px-3 text-xs font-bold text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-800 transition-all cursor-pointer">🌐 বাংলা</button>
        </div>
    </header>

    {{-- Main --}}
    <main class="flex items-center justify-center px-4 py-12 min-h-[calc(100vh-72px)]">
        <div class="w-full max-w-md">
            <div class="relative bg-white dark:bg-slate-900 rounded-2xl shadow-xl overflow-hidden border border-gray-100 dark:border-slate-800">
                <div class="h-1.5 bg-gradient-to-r from-orange-500 via-amber-400 to-emerald-500"></div>

                <div class="p-8">
                    {{-- Logo --}}
                    <div class="flex justify-center mb-4">
                        <svg width="72" height="86" viewBox="0 0 200 240" xmlns="http://www.w3.org/2000/svg"><defs><linearGradient id="lb2" x1="0%" y1="0%" x2="0%" y2="100%"><stop offset="0%" stop-color="#F97316"/><stop offset="35%" stop-color="#FBBF24"/><stop offset="70%" stop-color="#10B981"/><stop offset="100%" stop-color="#15803D"/></linearGradient><radialGradient id="ls2" cx="50%" cy="50%" r="50%"><stop offset="0%" stop-color="#FFFBEB"/><stop offset="40%" stop-color="#FBBF24"/><stop offset="85%" stop-color="#F97316"/><stop offset="100%" stop-color="#EA580C"/></radialGradient><linearGradient id="ll2" x1="0%" y1="100%" x2="0%" y2="0%"><stop offset="0%" stop-color="#14532D"/><stop offset="45%" stop-color="#16A34A"/><stop offset="100%" stop-color="#4ADE80"/></linearGradient></defs><rect x="12" y="12" width="176" height="216" rx="88" ry="88" fill="#FFF" stroke="url(#lb2)" stroke-width="11"/><g><polygon points="63,101 54,103 62,109" fill="#F97316"/><polygon points="66,85 58,82 69,76" fill="#F97316"/><polygon points="78,72 72,66 83,63" fill="#F97316"/><polygon points="91,65 92,56 99,64" fill="#F97316"/><polygon points="109,65 108,56 101,64" fill="#F97316"/><polygon points="122,72 128,66 117,63" fill="#F97316"/><polygon points="134,85 142,82 131,76" fill="#F97316"/><polygon points="137,101 146,103 138,109" fill="#F97316"/><circle cx="100" cy="105" r="32" fill="url(#ls2)"/></g><g><path d="M 100 135 L 100 155" stroke="#16A34A" stroke-width="6" stroke-linecap="round"/><path d="M 100 148 C 72 138, 56 102, 72 78 C 88 110, 100 114, 100 148" fill="url(#ll2)"/><path d="M 100 148 C 128 138, 144 102, 128 78 C 112 110, 100 114, 100 148" fill="url(#ll2)"/></g><g><path d="M 52 170 C 72 178, 82 165, 100 171 C 118 177, 128 164, 148 170" fill="none" stroke="#14532D" stroke-width="10" stroke-linecap="round"/><path d="M 52 192 C 72 200, 82 187, 100 193 C 118 199, 128 186, 148 192" fill="none" stroke="#14532D" stroke-width="10" stroke-linecap="round"/></g></svg>
                    </div>

                    <h2 class="text-center text-2xl font-extrabold italic bg-gradient-to-r from-orange-500 to-emerald-600 bg-clip-text text-transparent mb-1">Village Agro Farm</h2>
                    <p class="text-center text-xs text-gray-400 uppercase tracking-widest mb-6">INCOME & EXPENSE LEDGER</p>

                    {{-- Login / Sign Up Tabs --}}
                    <div class="grid grid-cols-2 mb-6 border-b border-gray-200 dark:border-slate-700">
                        <a href="/" class="pb-3 text-center text-sm font-bold text-gray-400 hover:text-gray-600 border-b-2 border-transparent">Login</a>
                        <a href="/register" class="pb-3 text-center text-sm font-bold text-emerald-600 border-b-2 border-emerald-600">Sign Up</a>
                    </div>

                    {{-- Form --}}
                    <form method="POST" action="/register" class="space-y-5">
                        @csrf
                        <div>
                            <label class="block text-[11px] font-extrabold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">FULL NAME</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                </span>
                                <input type="text" name="name" value="{{ old('name') }}" required placeholder="Sajib Ahmed" class="block w-full pl-11 pr-4 py-3 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800 text-sm text-gray-900 dark:text-gray-100 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition-all">
                            </div>
                            @error('name')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-[11px] font-extrabold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">EMAIL ADDRESS</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                                </span>
                                <input type="email" name="email" value="{{ old('email') }}" required placeholder="you@example.com" class="block w-full pl-11 pr-4 py-3 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800 text-sm text-gray-900 dark:text-gray-100 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition-all">
                            </div>
                            @error('email')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-[11px] font-extrabold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">PASSWORD</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                </span>
                                <input type="password" name="password" required placeholder="••••••••" class="block w-full pl-11 pr-4 py-3 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800 text-sm text-gray-900 dark:text-gray-100 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition-all">
                            </div>
                            @error('password')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-[11px] font-extrabold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">ASSIGN SYSTEM ROLE</label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="cursor-pointer">
                                    <input type="radio" name="role" value="user" class="peer sr-only" checked>
                                    <div class="flex items-center justify-center gap-2 py-2.5 rounded-xl border-2 border-gray-200 dark:border-slate-700 peer-checked:border-emerald-500 peer-checked:bg-emerald-50/50 dark:peer-checked:bg-emerald-950/30 text-xs font-bold text-gray-600 dark:text-gray-300 peer-checked:text-emerald-700 transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                        Standard User
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="role" value="admin" class="peer sr-only">
                                    <div class="flex items-center justify-center gap-2 py-2.5 rounded-xl border-2 border-gray-200 dark:border-slate-700 peer-checked:border-emerald-500 peer-checked:bg-emerald-50/50 dark:peer-checked:bg-emerald-950/30 text-xs font-bold text-gray-600 dark:text-gray-300 peer-checked:text-emerald-700 transition-all">
                                        <span class="w-3 h-3 rounded-full border-2 border-gray-300"></span>
                                        Administrator
                                    </div>
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="w-full py-3.5 rounded-xl bg-gradient-to-r from-orange-500 via-amber-500 to-emerald-600 text-white font-bold text-sm shadow-lg hover:shadow-xl transition-all flex items-center justify-center gap-2">
                            Sign Up <span>→</span>
                        </button>
                    </form>

                    <p class="text-center text-xs text-gray-500 mt-5">Already have an account? <a href="/" class="text-emerald-600 font-bold hover:underline">Login</a></p>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
