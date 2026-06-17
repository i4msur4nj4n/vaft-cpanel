@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Header Banner --}}
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-2">
            <span class="text-3xl">🌱</span>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Active Project Investment Metrics</h1>
        </div>
        <p class="text-sm text-gray-500 dark:text-gray-400 ml-12">Real-time capital expense, income and net ROI tracking for active farming, stock and crop investments.</p>
    </div>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
        {{-- Capital Invested --}}
        <div class="relative rounded-2xl border border-gray-100 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm border-l-4 border-l-emerald-500 p-5">
            <div class="absolute top-4 right-4 text-emerald-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p class="text-[10px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-1">Capital Invested (Expenses)</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">৳ {{ number_format($totalCapital) }}</p>
            <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-1">Capital spent in projects</p>
        </div>

        {{-- Income Earned --}}
        <div class="relative rounded-2xl border border-gray-100 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm border-l-4 border-l-emerald-500 p-5">
            <div class="absolute top-4 right-4 text-emerald-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
            </div>
            <p class="text-[10px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-1">Income Earned (Returns)</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">৳ {{ number_format($totalReturns) }}</p>
            <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-1">Gross returns generated</p>
        </div>

        {{-- Net Profit/ROI --}}
        <div class="relative rounded-2xl border border-gray-100 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm border-l-4 border-l-emerald-500 p-5">
            <div class="absolute top-4 right-4 text-emerald-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            </div>
            <p class="text-[10px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-1">Net Profit/ROI</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">৳ {{ number_format($netRoi) }}</p>
            <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-1">Net positive returns</p>
        </div>
    </div>

    {{-- Section Title --}}
    <div class="mb-5">
        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ count($projects) }} Active Farming & Stock Projects</p>
    </div>

    {{-- Project Cards Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($projects as $project)
        <div class="rounded-2xl border border-gray-100 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm p-5">
            {{-- Card Header --}}
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">🌾</span>
                    <h3 class="font-bold text-sm text-gray-900 dark:text-white">{{ $project->name }}</h3>
                </div>
                <span class="rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400">Active</span>
            </div>

            {{-- Card Metrics --}}
            <div class="grid grid-cols-3 gap-2 pt-3 border-t border-gray-100 dark:border-slate-800">
                <div>
                    <p class="text-[9px] font-bold uppercase tracking-wider text-red-500 mb-0.5">Capital Spent</p>
                    <p class="text-xs font-bold text-gray-900 dark:text-white">৳ {{ number_format($project->capital) }}</p>
                </div>
                <div>
                    <p class="text-[9px] font-bold uppercase tracking-wider text-red-500 mb-0.5">Returns</p>
                    <p class="text-xs font-bold text-gray-900 dark:text-white">৳ {{ number_format($project->returns) }}</p>
                </div>
                <div>
                    <p class="text-[9px] font-bold uppercase tracking-wider text-gray-500 mb-0.5">{{ __("ui.net_roi") }}</p>
                    <p class="text-xs font-bold {{ $project->net_roi >= 0 ? 'text-emerald-600' : 'text-red-500' }}">{{ $project->net_roi >= 0 ? '+' : '' }}{{ number_format($project->net_roi) }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

</div>
@endsection
