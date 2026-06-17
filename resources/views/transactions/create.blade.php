@extends('layouts.app')
@section('content')
<div class="max-w-xl mx-auto bg-white dark:bg-slate-900 rounded-xl border border-gray-100 dark:border-slate-800/80 shadow-md overflow-hidden">
    {{-- Green Header --}}
    <div class="bg-gradient-to-r from-emerald-600 to-teal-600 py-3.5 px-5 text-white">
        <h3 class="font-extrabold text-base tracking-tight">{{ __("ui.add_transaction") }}</h3>
        <p class="text-[11px] text-emerald-100 mt-0.5">Record transaction inside the secure cryptographic ledger.</p>
    </div>

    <form method="POST" action="/transactions" class="p-5 space-y-3.5">
        @csrf

        {{-- Transaction Type --}}
        <div>
            <label class="block text-[10px] font-extrabold text-gray-450 dark:text-gray-400 uppercase tracking-wider mb-1.5">Transaction Type</label>
            <div class="grid grid-cols-2 gap-2.5">
                <button type="button" onclick="setType('expense')" id="btn-expense" class="flex items-center justify-center gap-2 rounded-lg border py-2 text-xs font-bold transition-all cursor-pointer border-rose-500 bg-rose-50/50 dark:bg-rose-950/25 text-rose-800 dark:text-rose-400 ring-2 ring-rose-500/20">
                    <span class="h-1.5 w-1.5 rounded-full bg-rose-600"></span>Expense (ব্যয়)
                </button>
                <button type="button" onclick="setType('income')" id="btn-income" class="flex items-center justify-center gap-2 rounded-lg border py-2 text-xs font-bold transition-all cursor-pointer border-gray-200 dark:border-slate-800 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-slate-800">
                    <span class="h-1.5 w-1.5 rounded-full bg-gray-300"></span>Income (আয়)
                </button>
            </div>
            <input type="hidden" name="type" id="type-input" value="{{ old('type', 'expense') }}">
            @error('type')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Date + Amount Row --}}
        <div class="grid grid-cols-2 gap-3.5">
            <div>
                <label class="block text-[10px] font-extrabold text-gray-450 dark:text-gray-400 uppercase tracking-wider mb-1.5">Select Date</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>
                    </span>
                    <input type="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required
                        class="block w-full pl-9 pr-3 py-2 rounded-lg border border-gray-200 dark:border-slate-800 bg-gray-50/55 dark:bg-slate-950 text-xs text-gray-950 dark:text-gray-100 focus:border-emerald-500 focus:bg-white dark:focus:bg-slate-900 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition-all">
                </div>
                @error('date')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-[10px] font-extrabold text-gray-450 dark:text-gray-400 uppercase tracking-wider mb-1.5">Amount (BDT)</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none font-bold text-gray-500 dark:text-gray-450 text-[11px]">৳</span>
                    <input type="number" name="amount" value="{{ old('amount') }}" step="0.01" min="0.01" required placeholder="e.g. 1500"
                        class="block w-full pl-9 pr-3 py-2 rounded-lg border border-gray-200 dark:border-slate-800 bg-gray-50/55 dark:bg-slate-950 text-xs text-gray-950 dark:text-gray-100 focus:border-emerald-500 focus:bg-white dark:focus:bg-slate-900 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition-all">
                </div>
                @error('amount')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- Category + Project Row --}}
        <div class="grid grid-cols-2 gap-3.5">
            <div>
                <label class="block text-[10px] font-extrabold text-gray-450 dark:text-gray-400 uppercase tracking-wider mb-1.5">Category Selector</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12.586 2.586A2 2 0 0 0 11.172 2H4a2 2 0 0 0-2 2v7.172a2 2 0 0 0 .586 1.414l8.704 8.704a2.426 2.426 0 0 0 3.42 0l6.58-6.58a2.426 2.426 0 0 0 0-3.42z"/><circle cx="7.5" cy="7.5" r=".5" fill="currentColor"/></svg>
                    </span>
                    <select name="category_id" required
                        class="block w-full pl-9 pr-3 py-2 rounded-lg border border-gray-200 dark:border-slate-800 bg-gray-50/55 dark:bg-slate-950 text-xs text-gray-950 dark:text-gray-100 focus:border-emerald-500 focus:bg-white dark:focus:bg-slate-900 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition-all cursor-pointer appearance-none">
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ ($lang === "bn" ? ($cat->name_bn ?: $cat->name_en) : $cat->name_en) }}</option>
                        @endforeach
                    </select>
                </div>
                @error('category_id')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-[10px] font-extrabold text-gray-450 dark:text-gray-400 uppercase tracking-wider mb-1.5">Investment Project</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <span class="text-[12px]">💼</span>
                    </span>
                    <select name="project_id"
                        class="block w-full pl-9 pr-3 py-2 rounded-lg border border-gray-200 dark:border-slate-800 bg-gray-50/55 dark:bg-slate-950 text-xs text-gray-950 dark:text-gray-100 focus:border-emerald-500 focus:bg-white dark:focus:bg-slate-900 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition-all cursor-pointer appearance-none">
                        <option value="">General (No Project)</option>
                        @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Notes --}}
        <div>
            <label class="block text-[10px] font-extrabold text-gray-450 dark:text-gray-400 uppercase tracking-wider mb-1.5">Short Notes / Description</label>
            <div class="relative">
                <span class="absolute top-2.5 left-3 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/></svg>
                </span>
                <textarea name="notes" rows="2" placeholder="Describe transaction details..."
                    class="block w-full pl-9 pr-3 py-2 rounded-lg border border-gray-200 dark:border-slate-800 bg-gray-50/55 dark:bg-slate-950 text-xs text-gray-950 dark:text-gray-100 focus:border-emerald-500 focus:bg-white dark:focus:bg-slate-900 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition-all resize-y">{{ old('notes') }}</textarea>
            </div>
            @error('notes')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Buttons --}}
        <div class="flex justify-end gap-2.5 pt-1">
            <a href="/dashboard" class="px-4 py-1.5 rounded-lg border border-gray-200 dark:border-slate-800 text-[11px] font-bold text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-slate-800 hover:text-gray-800 dark:hover:text-gray-200 transition-all">Cancel</a>
            <button type="submit" class="flex items-center gap-1.5 px-5 py-2 rounded-lg bg-emerald-600 text-white font-bold text-[11px] hover:bg-emerald-700 transition-all shadow-sm shadow-emerald-600/10 hover:shadow-md cursor-pointer">
                <span>{{ __("ui.save_transaction") }}</span>
            </button>
        </div>
    </form>
</div>

<script>
function setType(type) {
    document.getElementById('type-input').value = type;
    const btnExpense = document.getElementById('btn-expense');
    const btnIncome = document.getElementById('btn-income');
    if (type === 'expense') {
        btnExpense.className = 'flex items-center justify-center gap-2 rounded-lg border py-2 text-xs font-bold transition-all cursor-pointer border-rose-500 bg-rose-50/50 dark:bg-rose-950/25 text-rose-800 dark:text-rose-400 ring-2 ring-rose-500/20';
        btnExpense.innerHTML = '<span class="h-1.5 w-1.5 rounded-full bg-rose-600"></span>Expense (ব্যয়)';
        btnIncome.className = 'flex items-center justify-center gap-2 rounded-lg border py-2 text-xs font-bold transition-all cursor-pointer border-gray-200 dark:border-slate-800 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-slate-800';
        btnIncome.innerHTML = '<span class="h-1.5 w-1.5 rounded-full bg-gray-300"></span>Income (আয়)';
    } else {
        btnIncome.className = 'flex items-center justify-center gap-2 rounded-lg border py-2 text-xs font-bold transition-all cursor-pointer border-emerald-500 bg-emerald-50/50 dark:bg-emerald-950/25 text-emerald-800 dark:text-emerald-400 ring-2 ring-emerald-500/20';
        btnIncome.innerHTML = '<span class="h-1.5 w-1.5 rounded-full bg-emerald-600"></span>Income (আয়)';
        btnExpense.className = 'flex items-center justify-center gap-2 rounded-lg border py-2 text-xs font-bold transition-all cursor-pointer border-gray-200 dark:border-slate-800 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-slate-800';
        btnExpense.innerHTML = '<span class="h-1.5 w-1.5 rounded-full bg-gray-300"></span>Expense (ব্যয়)';
    }
}
// Set initial state
document.addEventListener('DOMContentLoaded', () => setType('{{ old('type', 'expense') }}'));
</script>
@endsection
