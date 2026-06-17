@extends('layouts.app')

@section('content')
<div class="space-y-6">

    {{-- Filters --}}
    <div class="rounded-2xl border border-gray-100 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm p-6">
        <div class="flex items-center gap-2 mb-5">
            <svg class="w-4 h-4 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
            <h2 class="text-sm font-bold uppercase tracking-wider text-gray-900 dark:text-white">Filters</h2>
        </div>

        <form method="GET" action="/transactions/history" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3">
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-500 mb-1">From Date</label>
                <input type="date" name="from_date" value="{{ request('from_date') }}" class="w-full rounded-lg border border-gray-200 dark:border-slate-800 bg-gray-50/55 dark:bg-slate-950 text-xs py-2 px-3">
            </div>
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-500 mb-1">To Date</label>
                <input type="date" name="to_date" value="{{ request('to_date') }}" class="w-full rounded-lg border border-gray-200 dark:border-slate-800 bg-gray-50/55 dark:bg-slate-950 text-xs py-2 px-3">
            </div>
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-500 mb-1">Category Selector</label>
                <select name="category_id" class="w-full rounded-lg border border-gray-200 dark:border-slate-800 bg-gray-50/55 dark:bg-slate-950 text-xs py-2 px-3">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name_en }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-500 mb-1">Transaction Type</label>
                <select name="type" class="w-full rounded-lg border border-gray-200 dark:border-slate-800 bg-gray-50/55 dark:bg-slate-950 text-xs py-2 px-3">
                    <option value="">All Types</option>
                    <option value="income" {{ request('type') === 'income' ? 'selected' : '' }}>Income</option>
                    <option value="expense" {{ request('type') === 'expense' ? 'selected' : '' }}>Expense</option>
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-500 mb-1">Keyword Search</label>
                <div class="relative">
                    <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="Search notes..." class="w-full rounded-lg border border-gray-200 dark:border-slate-800 bg-gray-50/55 dark:bg-slate-950 text-xs py-2 pl-8 pr-3">
                </div>
            </div>
            <div class="lg:col-span-5 flex items-center gap-3 pt-2">
                <button type="submit" class="rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[11px] px-4 py-2 transition">Apply Filters</button>
                <a href="/transactions/history" class="rounded-lg border border-gray-200 dark:border-slate-700 text-[11px] font-bold text-gray-600 px-4 py-2 hover:bg-gray-50 transition">Clear All</a>
            </div>
        </form>
    </div>

    {{-- Transaction Table --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead><tr class="border-b border-gray-100 dark:border-slate-800 text-gray-500 uppercase tracking-wider font-semibold"><th class="py-4 px-5">Select Date</th><th class="py-4 px-5">Category Selector</th><th class="py-4 px-5">Transaction Type</th><th class="py-4 px-5">Short Notes / Description</th><th class="py-4 px-5">Action Initiated By</th><th class="py-4 px-5 text-right">Amount (BDT)</th><th class="py-4 px-5 text-center">Actions</th></tr></thead>
                <tbody class="divide-y divide-gray-50 dark:divide-slate-800/50">
                    @foreach($transactions as $txn)
                    <tr class="hover:bg-gray-50/40 dark:hover:bg-slate-800/30 transition-colors">
                        <td class="py-4 px-5 font-medium text-gray-500 uppercase">{{ $txn->date->format('d-M-y') }}</td>
                        <td class="py-4 px-5"><span class="flex items-center gap-1.5 font-bold text-gray-900 dark:text-white"><svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12.586 2.586A2 2 0 0 0 11.172 2H4a2 2 0 0 0-2 2v7.172a2 2 0 0 0 .586 1.414l8.704 8.704a2.426 2.426 0 0 0 3.42 0l6.58-6.58a2.426 2.426 0 0 0 0-3.42z"/><circle cx="7.5" cy="7.5" r=".5" fill="currentColor"/></svg>{{ ($lang === "bn" ? ($txn->category->name_bn ?: $txn->category->name_en) : $txn->category->name_en) ?? '' }} | {{ $txn->category->name_bn ?? '' }}</span></td>
                        <td class="py-4 px-5">@if($txn->type === 'income')<span class="inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-emerald-50 text-emerald-700">Income</span>@else<span class="inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-rose-50 text-rose-700">Expense</span>@endif</td>
                        <td class="py-4 px-5 text-gray-600 dark:text-gray-400 max-w-[200px] truncate">{{ $txn->notes }}</td>
                        <td class="py-4 px-5 text-gray-600">{{ $txn->user->name ?? '—' }}</td>
                        <td class="py-4 px-5 text-right font-bold {{ $txn->type === 'income' ? 'text-emerald-600' : 'text-rose-600' }}">{{ $txn->type === 'income' ? '+' : '-' }} ৳ {{ number_format($txn->amount) }}</td>
                        <td class="py-4 px-5 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button class="text-gray-400 hover:text-emerald-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z"/></svg></button>
                                <form method="POST" action="/transactions/{{ $txn->id }}" onsubmit="return confirm('Delete?')" class="inline">@csrf @method('DELETE')<button class="text-gray-400 hover:text-rose-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button></form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form[method="GET"]');
    const inputs = form.querySelectorAll('input, select');
    let debounceTimer;

    inputs.forEach(input => {
        input.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                sessionStorage.setItem('vaft_focus', this.name);
                form.submit();
            }, 600);
        });
        input.addEventListener('change', function() {
            if (this.tagName === 'SELECT') {
                sessionStorage.setItem('vaft_focus', this.name);
                form.submit();
            }
        });
    });

    // Restore focus after page reload
    const focusName = sessionStorage.getItem('vaft_focus');
    if (focusName) {
        const el = form.querySelector('[name="' + focusName + '"]');
        if (el && el.type === 'text') {
            el.focus();
            el.setSelectionRange(el.value.length, el.value.length);
        }
        sessionStorage.removeItem('vaft_focus');
    }
});
</script>
@endsection
