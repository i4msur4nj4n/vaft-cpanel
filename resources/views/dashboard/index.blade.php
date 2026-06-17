@extends('layouts.app')

@section('content')
<div class="space-y-6">

    {{-- WELCOME BANNER --}}
    <div class="rounded-xl bg-gradient-to-r from-orange-50/50 to-emerald-50/50 border border-orange-100 p-4 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="bg-orange-100 p-3 rounded-xl">
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-700">View bilingual documentation & transaction history in real time.</p>
                <p class="text-xs text-gray-500 uppercase tracking-wider mt-1">{{ __('ui.subtitle') }}</p>
            </div>
        </div>
        <a href="/transactions/create" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-orange-500 via-amber-500 to-emerald-600 text-white text-sm font-semibold shadow-md hover:shadow-lg transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Add Transaction
        </a>
    </div>

    {{-- KPI CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        {{-- Net Balance --}}
        <div class="rounded-2xl bg-gradient-to-br from-slate-900 to-slate-800 border border-slate-700 shadow-md p-5 text-white">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xs font-medium text-slate-300">{{ __("ui.net_balance") }}</h3>
                <div class="bg-slate-700/40 p-2.5 rounded-xl">
                    <svg class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2.25 2.25 0 00-2.25-2.25H15a3 3 0 110-6h.008A2.25 2.25 0 0017.25 6H21M3 12a2.25 2.25 0 002.25 2.25H9a3 3 0 110 6h-.008A2.25 2.25 0 006.75 18H3m18-6H3m18 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-white">৳ {{ number_format($netBalance) }}</p>
            <div class="flex items-center gap-2 mt-3">
                <span class="w-2 h-2 bg-emerald-400 rounded-full"></span>
                <span class="text-xs text-slate-400">{{ __("ui.net_balance") }}</span>
            </div>
        </div>

        {{-- Total Income --}}
        <div class="rounded-2xl bg-white border border-gray-100 shadow-sm p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xs font-medium text-gray-500">{{ __("ui.total_income") }}</h3>
                <div class="bg-emerald-50 p-2.5 rounded-xl">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H7M17 7v10"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-950">৳ {{ number_format($totalIncome) }}</p>
            <div class="flex items-center gap-2 mt-3">
                <span class="w-2 h-2 bg-emerald-400 rounded-full"></span>
                <span class="text-xs text-gray-400">{{ __("ui.total_income") }}</span>
            </div>
        </div>

        {{-- Total Expense --}}
        <div class="rounded-2xl bg-white border border-gray-100 shadow-sm p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xs font-medium text-gray-500">{{ __("ui.total_expense") }}</h3>
                <div class="bg-rose-50 p-2.5 rounded-xl">
                    <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 7L7 17M7 17h10M7 17V7"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-950">৳ {{ number_format($totalExpense) }}</p>
            <div class="flex items-center gap-2 mt-3">
                <span class="w-2 h-2 bg-rose-400 rounded-full"></span>
                <span class="text-xs text-gray-400">{{ __("ui.total_expense") }}</span>
            </div>
        </div>
    </div>

    {{-- CATEGORY + CHART ROW --}}
    <div class="grid lg:grid-cols-12 gap-5">
        {{-- Category Proportion Distribution --}}
        <div class="lg:col-span-7 rounded-2xl bg-white border border-gray-100 shadow-sm p-5">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Category Proportion Distribution</h3>
                <span class="px-3 py-1 rounded-full bg-slate-100 text-xs font-medium text-slate-600">Summary Metrics</span>
            </div>
            <div class="space-y-4">
                @foreach($categoryData as $cat)
                    @php
                        $isIncome = in_array($cat['name'] ?? '', ['Salary', 'Business', 'Investments']) || (isset($cat['type']) && $cat['type'] === 'income');
                        $barGradient = $isIncome ? 'from-emerald-500 to-teal-400' : 'from-rose-500 to-amber-500';
                        $catNameEn = $cat['name_en'] ?? $cat['name'] ?? '';
                        $catNameBn = $cat['name_bn'] ?? '';
                        $displayName = $catNameBn ? "$catNameEn | $catNameBn" : $catNameEn;
                    @endphp
                    <div class="flex items-center gap-3">
                        <span class="w-24 text-xs font-medium text-gray-600 truncate">{{ $displayName }}</span>
                        <div class="flex-1 bg-gray-100 rounded-full h-3.5 overflow-hidden">
                            <div class="h-full rounded-full bg-gradient-to-r {{ $barGradient }}" style="width: {{ $cat['percentage'] }}%"></div>
                        </div>
                        <span class="w-20 text-xs font-semibold text-gray-700 text-right">{{ number_format($cat['percentage'], 1) }}%</span>
                        <span class="w-28 text-xs font-medium text-gray-500 text-right">৳ {{ number_format($cat['amount']) }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Category Analytics Donut --}}
        <div class="lg:col-span-5 rounded-2xl bg-white border border-gray-100 shadow-sm p-5">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Category Analytics</h3>
            </div>
            <div class="flex justify-center">
                @php
                    $circumference = 2 * pi() * 70;
                    $offset = 0;
                    $colors = ['hsl(350,70%,45%)', 'hsl(142,70%,50%)', 'hsl(350,70%,65%)', 'hsl(30,70%,50%)', 'hsl(200,70%,50%)', 'hsl(280,70%,50%)', 'hsl(142,70%,40%)'];
                @endphp
                <svg width="180" height="180" viewBox="0 0 180 180" id="donut-chart">
                    <circle cx="90" cy="90" r="70" fill="none" stroke="#f1f5f9" stroke-width="24"/>
                    @foreach($categoryData as $index => $cat)
                        @php
                            $dashLength = ($cat['percentage'] / 100) * $circumference;
                            $dashGap = $circumference - $dashLength;
                            $color = $colors[$index % count($colors)];
                        @endphp
                        <circle
                            cx="90"
                            cy="90"
                            r="70"
                            fill="none"
                            stroke="{{ $color }}"
                            stroke-width="24"
                            stroke-dasharray="{{ $dashLength }} {{ $dashGap }}"
                            stroke-dashoffset="{{ -$offset }}"
                            transform="rotate(-90 90 90)"
                            class="transition-all duration-300 cursor-pointer donut-segment"
                            data-name="{{ $cat['name_en'] ?? $cat['name'] }}"
                            data-name-bn="{{ $cat['name_bn'] ?? '' }}"
                            data-amount="{{ number_format($cat['amount']) }}"
                            data-percentage="{{ $cat['percentage'] }}"
                            data-type="{{ ($cat['is_income'] ?? false) ? 'INCOME' : 'EXPENSE' }}"
                            data-type-bn="{{ ($cat['is_income'] ?? false) ? 'আয়' : 'ব্যয়' }}"
                            onmouseenter="showSegment(this)"
                            onmouseleave="hideSegment()"
                        />
                        @php
                            $offset += $dashLength;
                        @endphp
                    @endforeach
                </svg>
            </div>
            <div class="text-center mt-4 space-y-1 h-12" id="donut-tooltip">
                <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 tracking-wider" id="donut-type">Hover segments for proportion details</p>
                <p class="text-sm font-bold text-gray-700 dark:text-gray-300" id="donut-detail">{{ count($categoryData) }} Active category segments</p>
            </div>
            <script>
            function showSegment(el) {
                const type = el.dataset.type;
                const typeBn = el.dataset.typeBn;
                const name = el.dataset.name;
                const nameBn = el.dataset.nameBn;
                const amount = el.dataset.amount;
                const pct = el.dataset.percentage;
                document.getElementById('donut-type').textContent = type + ' (' + typeBn + ')';
                document.getElementById('donut-detail').textContent = name + ' | ' + nameBn + ': ৳ ' + amount + ' (' + pct + '%)';
                // Highlight hovered segment
                document.querySelectorAll('.donut-segment').forEach(s => s.setAttribute('stroke-width', '24'));
                el.setAttribute('stroke-width', '28');
            }
            function hideSegment() {
                document.getElementById('donut-type').textContent = 'Hover segments for proportion details';
                document.getElementById('donut-detail').textContent = '{{ count($categoryData) }} Active category segments';
                document.querySelectorAll('.donut-segment').forEach(s => s.setAttribute('stroke-width', '24'));
            }
            </script>
        </div>
    </div>

    {{-- RECENT TRANSACTIONS TABLE --}}
    <div class="rounded-2xl bg-white border border-gray-100 shadow-md p-5">
        <div class="flex items-center gap-3 mb-6">
            <div class="bg-gray-100 p-2 rounded-lg">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900">Recent Transaction Streams</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider pb-3 px-3">Select Date</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider pb-3 px-3">Category Selector</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider pb-3 px-3">Transaction Type</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider pb-3 px-3">Short Notes / Description</th>
                        <th class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wider pb-3 px-3">Amount (BDT)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($recentTransactions as $transaction)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="py-3.5 px-3 text-xs font-medium text-gray-700">
                                {{ strtoupper(\Carbon\Carbon::parse($transaction->date)->format('d-M-y')) }}
                            </td>
                            <td class="py-3.5 px-3">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/>
                                    </svg>
                                    <span class="text-sm text-gray-700">{{ $transaction->category->name_en ?? '' }} | {{ $transaction->category->name_bn ?? '' }}</span>
                                </div>
                            </td>
                            <td class="py-3.5 px-3">
                                @if($transaction->type === 'income')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700">{{ __("ui.income") }}</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-rose-50 text-rose-700">{{ __("ui.expense") }}</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-3 text-sm text-gray-600 max-w-xs truncate">{{ $transaction->notes }}</td>
                            <td class="py-3.5 px-3 text-sm font-semibold text-right">
                                @if($transaction->type === 'income')
                                    <span class="text-emerald-600">+৳ {{ number_format($transaction->amount) }}</span>
                                @else
                                    <span class="text-rose-600">-৳ {{ number_format($transaction->amount) }}</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
