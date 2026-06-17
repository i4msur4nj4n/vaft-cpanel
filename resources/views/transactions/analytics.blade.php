@extends('layouts.app')

@section('content')
<div class="space-y-6">

    {{-- Report Filters --}}
    <div class="rounded-2xl border border-gray-100 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm p-6">
        <div class="flex items-center gap-2 mb-5">
            <svg class="w-4 h-4 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
            <h2 class="text-sm font-bold uppercase tracking-wider text-gray-900 dark:text-white">Report Filters</h2>
        </div>

        <form method="GET" action="/transactions/analytics" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
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
                <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-500 mb-1">Investment Project</label>
                <select name="project_id" class="w-full rounded-lg border border-gray-200 dark:border-slate-800 bg-gray-50/55 dark:bg-slate-950 text-xs py-2 px-3">
                    <option value="">All Projects</option>
                    @foreach($projects as $project)
                    <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-500 mb-1">Keyword Search</label>
                <div class="relative">
                    <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="Search..." class="w-full rounded-lg border border-gray-200 dark:border-slate-800 bg-gray-50/55 dark:bg-slate-950 text-xs py-2 pl-8 pr-3">
                </div>
            </div>
            <div class="lg:col-span-6 flex items-center gap-3 pt-2">
                <button type="submit" class="rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[11px] px-4 py-2 transition">Apply Filters</button>
                <a href="/transactions/analytics" class="rounded-lg border border-gray-200 dark:border-slate-700 text-[11px] font-bold text-gray-600 dark:text-gray-400 px-4 py-2 hover:bg-gray-50 transition">Clear All</a>
            </div>
        </form>
    </div>

    {{-- Matches & Export --}}
    <div class="flex items-center justify-between">
        <p class="text-xs text-gray-500">Matches Found: <span class="font-bold text-gray-900 dark:text-white">{{ count($transactions) }} Transactions</span></p>
        <a href="/transactions/analytics/export-csv?{{ http_build_query(request()->query()) }}" class="rounded-lg bg-rose-600 hover:bg-rose-700 text-white font-bold text-[11px] px-4 py-2 transition italic no-underline">Export to CSV File</a>
    </div>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="rounded-2xl border-l-4 border-emerald-500 bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 p-5 shadow-sm">
            <p class="text-[10px] font-bold uppercase tracking-wider text-gray-500 mb-2">Total Income</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">৳ {{ number_format($totalIncome) }}</p>
        </div>
        <div class="rounded-2xl border-l-4 border-rose-500 bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 p-5 shadow-sm">
            <p class="text-[10px] font-bold uppercase tracking-wider text-gray-500 mb-2">Total Expense</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">৳ {{ number_format($totalExpense) }}</p>
        </div>
        <div class="rounded-2xl border-l-4 border-emerald-500 bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 p-5 shadow-sm">
            <p class="text-[10px] font-bold uppercase tracking-wider text-gray-500 mb-2">Total Net Balance</p>
            <p class="text-2xl font-bold text-emerald-600">৳ {{ number_format($netBalance) }}</p>
        </div>
    </div>

    {{-- Results Table --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-slate-800 flex items-center gap-2">
            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
            <h3 class="text-sm font-bold text-gray-900 dark:text-white">Filtered Results Table</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead><tr class="border-b border-gray-100 dark:border-slate-800 text-gray-500 uppercase tracking-wider font-semibold"><th class="py-3 px-5">Select Date</th><th class="py-3 px-5">Category Selector</th><th class="py-3 px-5">Investment Project</th><th class="py-3 px-5">Transaction Type</th><th class="py-3 px-5">Short Notes / Description</th><th class="py-3 px-5">Action Initiated By</th><th class="py-3 px-5 text-right">Amount (BDT)</th></tr></thead>
                <tbody class="divide-y divide-gray-50 dark:divide-slate-800/50">
                    @foreach($transactions as $txn)
                    <tr class="hover:bg-gray-50/40 dark:hover:bg-slate-800/30 transition-colors">
                        <td class="py-3 px-5 font-medium text-gray-500 uppercase">{{ $txn->date->format('d-M-y') }}</td>
                        <td class="py-3 px-5 font-bold text-gray-900 dark:text-white">{{ $txn->category->name_en ?? '' }} | {{ $txn->category->name_bn ?? '' }}</td>
                        <td class="py-3 px-5 text-gray-500">{{ $txn->project->name ?? '—' }}</td>
                        <td class="py-3 px-5">
                            @if($txn->type === 'income')
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-emerald-50 text-emerald-700">Income</span>
                            @else
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-rose-50 text-rose-700">Expense</span>
                            @endif
                        </td>
                        <td class="py-3 px-5 text-gray-600 dark:text-gray-400 max-w-[200px] truncate">{{ $txn->notes }}</td>
                        <td class="py-3 px-5 text-gray-600">{{ $txn->user->name ?? '—' }}</td>
                        <td class="py-3 px-5 text-right font-bold {{ $txn->type === 'income' ? 'text-emerald-600' : 'text-rose-600' }}">{{ $txn->type === 'income' ? '+' : '-' }} ৳ {{ number_format($txn->amount) }}</td>
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
