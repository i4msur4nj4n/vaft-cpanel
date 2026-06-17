@extends('layouts.app')
@section('content')
<div class="space-y-6">
    {{-- Tabs --}}
    <div class="border-b border-gray-200 dark:border-slate-800">
        <nav class="flex gap-8">
            <a href="/control-panel?tab=categories" class="pb-3 text-sm font-semibold transition-all {{ ($tab ?? 'categories') === 'categories' ? 'text-emerald-600 border-b-2 border-emerald-600' : 'text-gray-500 hover:text-gray-700' }}">{{ __("ui.category_configure") }}</a>
            <a href="/control-panel?tab=projects" class="pb-3 text-sm font-semibold transition-all {{ ($tab ?? '') === 'projects' ? 'text-emerald-600 border-b-2 border-emerald-600' : 'text-gray-500 hover:text-gray-700' }}">{{ __("ui.project_configure") }}</a>
            <a href="/control-panel?tab=branding" class="pb-3 text-sm font-semibold transition-all {{ ($tab ?? '') === 'branding' ? 'text-emerald-600 border-b-2 border-emerald-600' : 'text-gray-500 hover:text-gray-700' }}">{{ __("ui.system_branding") }}</a>
            <a href="/control-panel?tab=accounts" class="pb-3 text-sm font-semibold transition-all {{ ($tab ?? '') === 'accounts' ? 'text-emerald-600 border-b-2 border-emerald-600' : 'text-gray-500 hover:text-gray-700' }}">{{ __("ui.account_suites") }}</a>
        </nav>
    </div>

    @if(($tab ?? 'categories') === 'categories')
    {{-- CATEGORY CONFIGURE --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 p-6 shadow-sm">
            <div class="flex items-center gap-2 mb-1"><span class="text-emerald-600">+</span><h3 class="font-extrabold text-base text-gray-900 dark:text-white">{{ __("ui.add_txn_category") }}</h3></div>
            <p class="text-xs text-gray-500 mb-5">{{ __("ui.category_desc") }}</p>
            <form method="POST" action="/control-panel/categories" class="space-y-4">
                @csrf
                <div><label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-wider mb-1.5">{{ __("ui.cat_name_en") }}</label><input type="text" name="name_en" placeholder="e.g. Agriculture Supplies" required class="block w-full py-2 px-3 rounded-lg border border-gray-200 dark:border-slate-800 bg-gray-50/55 dark:bg-slate-950 text-xs focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none"></div>
                <div><label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-wider mb-1.5">{{ __("ui.cat_name_bn") }}</label><input type="text" name="name_bn" placeholder="যেমন: কৃষি সরবরাহ" required class="block w-full py-2 px-3 rounded-lg border border-gray-200 dark:border-slate-800 bg-gray-50/55 dark:bg-slate-950 text-xs focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none"></div>
                <div><label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-wider mb-1.5">{{ __("ui.category_icon") }}</label><select name="icon" class="block w-full py-2 px-3 rounded-lg border border-gray-200 dark:border-slate-800 bg-gray-50/55 dark:bg-slate-950 text-xs focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none"><option value="💰">💰 Money</option><option value="💼">💼 Briefcase</option><option value="📈">📈 Chart</option><option value="🍚">🍚 Food</option><option value="🏠">🏠 Housing</option><option value="⚡">⚡ Utilities</option><option value="🚗">🚗 Transport</option><option value="🎬">🎬 Entertainment</option><option value="🏥">🏥 Medical</option><option value="📚">📚 Education</option><option value="🌾">🌾 Agriculture</option></select></div>
                <button type="submit" class="w-full py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-all">{{ __("ui.register_category") }}</button>
            </form>
        </div>
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 p-6 shadow-sm">
            <div class="flex items-center gap-2 mb-1"><span class="text-emerald-600">🏷️</span><h3 class="font-extrabold text-base text-gray-900 dark:text-white">{{ __("ui.registered_categories") }}</h3></div>
            <p class="text-xs text-gray-500 mb-5">{{ __("ui.registered_cat_desc") }}</p>
            <table class="w-full"><thead><tr class="border-b border-gray-100 dark:border-slate-800"><th class="text-left text-[10px] font-bold uppercase tracking-wider text-gray-500 pb-2">{{ __("ui.category_title") }}</th><th class="text-left text-[10px] font-bold uppercase tracking-wider text-gray-500 pb-2">{{ __("ui.icon") }}</th><th class="text-right text-[10px] font-bold uppercase tracking-wider text-gray-500 pb-2">{{ __("ui.actions") }}</th></tr></thead><tbody class="divide-y divide-gray-50 dark:divide-slate-800/50">
                @foreach($categories as $category)
                <tr><td class="py-3"><p class="text-sm font-bold text-gray-900 dark:text-white">{{ $category->name_en }}</p><p class="text-[11px] text-gray-500">{{ $category->name_bn }}</p></td><td class="py-3 text-lg">{{ $category->icon }}</td><td class="py-3 text-right"><form method="POST" action="/control-panel/categories/{{ $category->id }}" onsubmit="return confirm('Delete?')" class="inline">@csrf @method('DELETE')<button class="text-gray-400 hover:text-rose-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg></button></form></td></tr>
                @endforeach
            </tbody></table>
        </div>
    </div>

    @elseif($tab === 'projects')
    {{-- PROJECT CONFIGURE --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 p-6 shadow-sm">
            <div class="flex items-center gap-2 mb-1"><span class="text-emerald-600">+</span><h3 class="font-extrabold text-base text-gray-900 dark:text-white">{{ __("ui.add_investment_project") }}</h3></div>
            <p class="text-xs text-gray-500 mb-5">{{ __("ui.project_desc") }}</p>
            <form method="POST" action="/control-panel/projects" class="space-y-4">
                @csrf
                <div><label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-wider mb-1.5">{{ __("ui.project_name_en") }}</label><input type="text" name="name" required placeholder="e.g. Stock Corn" class="block w-full py-2 px-3 rounded-lg border border-gray-200 dark:border-slate-800 bg-gray-50/55 dark:bg-slate-950 text-xs focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none"></div>
                <div><label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-wider mb-1.5">{{ __("ui.project_name_bn") }}</label><input type="text" name="name_bn" placeholder="যেমন: ভুট্টা স্টক" class="block w-full py-2 px-3 rounded-lg border border-gray-200 dark:border-slate-800 bg-gray-50/55 dark:bg-slate-950 text-xs focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none"></div>
                <div><label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-wider mb-1.5">{{ __("ui.project_icon") }}</label><select name="icon" class="block w-full py-2 px-3 rounded-lg border border-gray-200 dark:border-slate-800 bg-gray-50/55 dark:bg-slate-950 text-xs focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none"><option value="💼">💼 Briefcase</option><option value="🌾">🌾 Agriculture</option><option value="🐄">🐄 Livestock</option><option value="🌽">🌽 Corn</option><option value="🌿">🌿 Crops</option><option value="📦">📦 Stock</option></select></div>
                <div><label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-wider mb-1.5">{{ __("ui.investment_params_en") }}</label><textarea name="description" rows="2" placeholder="Describe project terms..." class="block w-full py-2 px-3 rounded-lg border border-gray-200 dark:border-slate-800 bg-gray-50/55 dark:bg-slate-950 text-xs focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none resize-y"></textarea></div>
                <div><label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-wider mb-1.5">{{ __("ui.investment_params_bn") }}</label><textarea name="description_bn" rows="2" placeholder="প্রজেক্টের বিবরণ..." class="block w-full py-2 px-3 rounded-lg border border-gray-200 dark:border-slate-800 bg-gray-50/55 dark:bg-slate-950 text-xs focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none resize-y"></textarea></div>
                <button type="submit" class="w-full py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-all">{{ __("ui.register_project") }}</button>
            </form>
        </div>
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 p-6 shadow-sm">
            <div class="flex items-center gap-2 mb-1"><span class="text-emerald-600">🏛️</span><h3 class="font-extrabold text-base text-gray-900 dark:text-white">{{ __("ui.registered_projects") }}</h3></div>
            <p class="text-xs text-gray-500 mb-5">{{ __("ui.registered_proj_desc") }}</p>
            <table class="w-full"><thead><tr class="border-b border-gray-100 dark:border-slate-800"><th class="text-left text-[10px] font-bold uppercase tracking-wider text-gray-500 pb-2">{{ __("ui.project_title") }}</th><th class="text-left text-[10px] font-bold uppercase tracking-wider text-gray-500 pb-2">{{ __("ui.technical_id") }}</th><th class="text-right text-[10px] font-bold uppercase tracking-wider text-gray-500 pb-2">{{ __("ui.actions") }}</th></tr></thead><tbody class="divide-y divide-gray-50 dark:divide-slate-800/50">
                @foreach($projects as $project)
                <tr><td class="py-3"><div class="flex items-center gap-2"><span class="text-lg">{{ $project->icon ?? '🌾' }}</span><div><p class="text-sm font-bold text-gray-900 dark:text-white">{{ $project->name }}</p><p class="text-[11px] text-gray-500">{{ $project->name_bn ?? '' }}</p></div></div></td><td class="py-3 text-xs text-gray-500 font-mono">{{ $project->slug ?? strtolower(str_replace(' ', '-', $project->name)) }}</td><td class="py-3 text-right"><form method="POST" action="/control-panel/projects/{{ $project->id }}" onsubmit="return confirm('Delete?')" class="inline">@csrf @method('DELETE')<button class="text-gray-400 hover:text-rose-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg></button></form></td></tr>
                @endforeach
            </tbody></table>
        </div>
    </div>

    @elseif($tab === 'branding')
    {{-- SYSTEM BRANDING --}}
    @php
        $appTitleEn = \App\Models\Setting::get('app_title_en', 'Village Agro Farm');
        $appTitleBn = \App\Models\Setting::get('app_title_bn', 'ভিলেজ এগ্রো ফার্ম');
        $interfaceSize = \App\Models\Setting::get('interface_size', 'medium');
    @endphp
    <div class="max-w-2xl mx-auto bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 p-8 shadow-sm">
        <div class="flex items-center gap-3 mb-1"><span class="text-xl">⚙️</span><h3 class="font-extrabold text-lg text-gray-900 dark:text-white">{{ __("ui.brand_config") }}</h3></div>
        <p class="text-xs text-gray-500 mb-8">{{ __("ui.brand_desc") }}</p>
        <form method="POST" action="/control-panel/branding" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <div class="grid grid-cols-2 gap-5">
                <div><label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-wider mb-1.5">{{ __("ui.app_title_en") }}</label><input type="text" name="app_title_en" value="{{ $appTitleEn }}" class="block w-full py-2.5 px-3 rounded-lg border border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none"></div>
                <div><label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-wider mb-1.5">{{ __("ui.app_title_bn") }}</label><input type="text" name="app_title_bn" value="{{ $appTitleBn }}" class="block w-full py-2.5 px-3 rounded-lg border border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none"></div>
            </div>
            <div><label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-wider mb-1.5">{{ __("ui.upload_logo") }}</label><div class="flex items-center gap-3"><label class="inline-flex items-center px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold cursor-pointer">{{ __("ui.choose_file") }}<input type="file" name="logo" class="hidden" accept=".svg,.png"></label><span class="text-xs text-gray-400">{{ __("ui.logo_hint") }}</span>@if($appLogo ?? false)<span class="text-xs text-emerald-600 font-bold">✓ Logo uploaded</span>@endif</div></div>
            <div><label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-wider mb-2.5">{{ __("ui.interface_size") }}</label>
                <div class="grid grid-cols-3 gap-0 border border-gray-200 dark:border-slate-800 rounded-lg overflow-hidden">
                    <button type="button" onclick="document.getElementById('sizeInput').value='small';this.className='py-2.5 text-xs font-bold text-white bg-emerald-600';this.nextElementSibling.className='py-2.5 text-xs font-bold text-gray-600 hover:bg-gray-50';this.nextElementSibling.nextElementSibling.className='py-2.5 text-xs font-bold text-gray-600 hover:bg-gray-50 border-l border-gray-200'" class="py-2.5 text-xs font-bold {{ $interfaceSize === 'small' ? 'text-white bg-emerald-600' : 'text-gray-600 hover:bg-gray-50' }} border-r border-gray-200">{{ __("ui.small") }}</button>
                    <button type="button" onclick="document.getElementById('sizeInput').value='medium';this.previousElementSibling.className='py-2.5 text-xs font-bold text-gray-600 hover:bg-gray-50 border-r border-gray-200';this.className='py-2.5 text-xs font-bold text-white bg-emerald-600';this.nextElementSibling.className='py-2.5 text-xs font-bold text-gray-600 hover:bg-gray-50 border-l border-gray-200'" class="py-2.5 text-xs font-bold {{ $interfaceSize === 'medium' ? 'text-white bg-emerald-600' : 'text-gray-600 hover:bg-gray-50' }}">{{ __("ui.medium") }}</button>
                    <button type="button" onclick="document.getElementById('sizeInput').value='big';this.previousElementSibling.previousElementSibling.className='py-2.5 text-xs font-bold text-gray-600 hover:bg-gray-50 border-r border-gray-200';this.previousElementSibling.className='py-2.5 text-xs font-bold text-gray-600 hover:bg-gray-50';this.className='py-2.5 text-xs font-bold text-white bg-emerald-600 border-l border-gray-200'" class="py-2.5 text-xs font-bold {{ $interfaceSize === 'big' ? 'text-white bg-emerald-600' : 'text-gray-600 hover:bg-gray-50' }} border-l border-gray-200">{{ __("ui.big") }}</button>
                </div>
                <input type="hidden" id="sizeInput" name="interface_size" value="{{ $interfaceSize }}">
            </div>
            <button type="submit" class="w-full py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-all">{{ __("ui.apply_branding") }}</button>
        </form>
    </div>

    @elseif($tab === 'accounts')
    {{-- ACCOUNT SUITES --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 p-6 shadow-sm">
            <div class="flex items-center gap-2 mb-1"><span class="text-emerald-600">+</span><h3 class="font-extrabold text-base text-gray-900 dark:text-white">{{ __("ui.add_account") }}</h3></div>
            <p class="text-xs text-gray-500 mb-5">{{ __("ui.account_desc") }}</p>
            <form method="POST" action="/control-panel/accounts" class="space-y-4">
                @csrf
                <div><label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-wider mb-1.5">{{ __("ui.account_code") }}</label><input type="text" name="code" placeholder="e.g. 1010, 5030" required class="block w-full py-2 px-3 rounded-lg border border-gray-200 dark:border-slate-800 bg-gray-50/55 dark:bg-slate-950 text-xs focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none"></div>
                <div><label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-wider mb-1.5">{{ __("ui.account_title_name") }}</label><input type="text" name="title" placeholder="e.g. Cash in Hand" required class="block w-full py-2 px-3 rounded-lg border border-gray-200 dark:border-slate-800 bg-gray-50/55 dark:bg-slate-950 text-xs focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none"></div>
                <div><label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-wider mb-1.5">{{ __("ui.class_type") }}</label><select name="class" required class="block w-full py-2 px-3 rounded-lg border border-gray-200 dark:border-slate-800 bg-gray-50/55 dark:bg-slate-950 text-xs focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none"><option value="asset">Asset (সম্পদ)</option><option value="liability">Liability (দায়)</option><option value="equity">Equity (মূলধন)</option><option value="revenue">Revenue (রাজস্ব)</option><option value="expense">Expense (ব্যয়)</option></select></div>
                <div><label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-wider mb-2">{{ __("ui.normal_balance") }}</label><div class="grid grid-cols-2 gap-0 border border-gray-200 dark:border-slate-800 rounded-lg overflow-hidden"><button type="button" onclick="setBalance('debit')" id="btn-debit" class="py-2.5 text-xs font-bold text-white bg-emerald-600">Debit</button><button type="button" onclick="setBalance('credit')" id="btn-credit" class="py-2.5 text-xs font-bold text-gray-600 hover:bg-gray-50 border-l border-gray-200">Credit</button></div><input type="hidden" name="normal_balance" id="balance-input" value="debit"></div>
                <div><label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-wider mb-1.5">{{ __("ui.account_param_desc") }}</label><textarea name="description" rows="2" placeholder="e.g. Petty cash reserves..." class="block w-full py-2 px-3 rounded-lg border border-gray-200 dark:border-slate-800 bg-gray-50/55 dark:bg-slate-950 text-xs focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none resize-y"></textarea></div>
                <button type="submit" class="w-full py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-all">{{ __("ui.register_account") }}</button>
            </form>
            <script>function setBalance(t){document.getElementById('balance-input').value=t;document.getElementById('btn-debit').className=t==='debit'?'py-2.5 text-xs font-bold text-white bg-emerald-600':'py-2.5 text-xs font-bold text-gray-600 hover:bg-gray-50 border-r border-gray-200';document.getElementById('btn-credit').className=t==='credit'?'py-2.5 text-xs font-bold text-white bg-emerald-600':'py-2.5 text-xs font-bold text-gray-600 hover:bg-gray-50 border-l border-gray-200';}</script>
        </div>
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 p-6 shadow-sm">
            <div class="flex items-center gap-2 mb-1"><span class="text-emerald-600">🏦</span><h3 class="font-extrabold text-base text-gray-900 dark:text-white">{{ __("ui.dynamic_chart") }}</h3></div>
            <p class="text-xs text-gray-500 mb-5">{{ __("ui.dynamic_chart_desc") }}</p>
            <table class="w-full"><thead><tr class="border-b border-gray-100 dark:border-slate-800"><th class="text-left text-[10px] font-bold uppercase tracking-wider text-gray-500 pb-2">{{ __("ui.code") }}</th><th class="text-left text-[10px] font-bold uppercase tracking-wider text-gray-500 pb-2">{{ __("ui.account_title_col") }}</th><th class="text-left text-[10px] font-bold uppercase tracking-wider text-gray-500 pb-2">{{ __("ui.class") }}</th><th class="text-left text-[10px] font-bold uppercase tracking-wider text-gray-500 pb-2">TYPE</th><th class="text-right text-[10px] font-bold uppercase tracking-wider text-gray-500 pb-2">{{ __("ui.actions") }}</th></tr></thead><tbody class="divide-y divide-gray-50 dark:divide-slate-800/50">
                @foreach($accounts as $account)
                @php $classColors = ['asset'=>'bg-emerald-50 text-emerald-700','liability'=>'bg-amber-50 text-amber-700','equity'=>'bg-blue-50 text-blue-700','revenue'=>'bg-emerald-50 text-emerald-700','expense'=>'bg-rose-50 text-rose-700']; @endphp
                <tr><td class="py-3 text-sm font-bold text-gray-900 dark:text-white">{{ $account->code }}</td><td class="py-3"><p class="text-sm font-bold text-gray-900 dark:text-white">{{ $account->title }}</p><p class="text-[11px] text-gray-500">{{ $account->description }}</p></td><td class="py-3"><span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold {{ $classColors[$account->class] ?? 'bg-gray-100 text-gray-600' }}">{{ ucfirst($account->class) }}</span></td><td class="py-3 text-xs text-gray-600">{{ ucfirst($account->normal_balance) }}</td><td class="py-3 text-right"><form method="POST" action="/control-panel/accounts/{{ $account->id }}" onsubmit="return confirm('Delete?')" class="inline">@csrf @method('DELETE')<button class="text-gray-400 hover:text-rose-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg></button></form></td></tr>
                @endforeach
            </tbody></table>
        </div>
    </div>
    @endif
</div>
@endsection
