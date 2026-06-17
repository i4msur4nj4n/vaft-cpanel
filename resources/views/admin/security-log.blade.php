@extends('layouts.app')
@section('content')
<div class="space-y-6">
    {{-- Tabs --}}
    <div class="flex border-b border-gray-150 dark:border-slate-800 gap-2 overflow-x-auto">
        <a href="/admin-panel" class="px-4 py-3 text-xs font-bold tracking-tight border-b-2 border-transparent text-gray-500 hover:text-gray-700 whitespace-nowrap">{{ __("ui.user_control") }}</a>
        <a href="/admin-panel/subscriptions" class="px-4 py-3 text-xs font-bold tracking-tight border-b-2 border-transparent text-gray-500 hover:text-gray-700 whitespace-nowrap">{{ __("ui.manage_subscriptions") }}</a>
        <a href="/admin-panel/security-log" class="px-4 py-3 text-xs font-bold tracking-tight border-b-2 border-emerald-600 text-emerald-700 dark:text-emerald-400 whitespace-nowrap">{{ __("ui.security_log") }}</a>
    </div>

    {{-- Info Banner --}}
    <div class="flex items-center gap-3 bg-gray-50 dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-2xl p-4">
        <span class="text-gray-400 text-lg">🕐</span>
        <p class="text-sm text-gray-600 dark:text-gray-400 font-medium">{{ __("ui.audit_trail_desc") }}</p>
    </div>

    {{-- Search, Date Filter & Export --}}
    <form method="GET" action="/admin-panel/security-log" class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm p-5">
        <div class="flex flex-wrap items-end gap-3">
            {{-- Search --}}
            <div class="flex-1 min-w-[200px]">
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">{{ __("ui.search_logs") }}</label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">🔍</span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by keyword, user, action..." class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none" oninput="clearTimeout(this._t);this._t=setTimeout(()=>this.form.submit(),400)">
                </div>
            </div>
            {{-- From Date --}}
            <div class="min-w-[150px]">
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">{{ __("ui.from_date") }}</label>
                <input type="date" name="from_date" value="{{ request('from_date') }}" class="w-full px-3 py-2.5 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
            </div>
            {{-- To Date --}}
            <div class="min-w-[150px]">
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">{{ __("ui.to_date") }}</label>
                <input type="date" name="to_date" value="{{ request('to_date') }}" class="w-full px-3 py-2.5 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
            </div>
            {{-- Buttons --}}
            <div class="flex gap-2">
                <button type="submit" class="inline-flex items-center gap-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2.5 text-xs font-bold transition-all cursor-pointer shadow-sm">
                    Filter
                </button>
                @if(request('search') || request('from_date') || request('to_date'))
                <a href="/admin-panel/security-log" class="inline-flex items-center gap-1.5 rounded-xl border border-gray-200 dark:border-slate-700 px-4 py-2.5 text-xs font-bold text-gray-600 hover:bg-gray-50 dark:hover:bg-slate-800 transition-all">
                    Clear
                </a>
                @endif
                <a href="/admin-panel/security-log/export-csv?{{ http_build_query(request()->query()) }}" class="inline-flex items-center gap-1.5 rounded-xl border border-gray-200 dark:border-slate-700 px-4 py-2.5 text-xs font-bold text-gray-600 hover:bg-gray-50 dark:hover:bg-slate-800 transition-all">
                    📥 Export CSV
                </a>
            </div>
        </div>
    </form>

    {{-- Audit Log Table --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-slate-800 text-gray-500 dark:text-gray-400 uppercase tracking-wider font-semibold">
                        <th class="py-4 px-6">{{ __("ui.event_timestamp") }}</th>
                        <th class="py-4 px-6">{{ __("ui.action") }}</th>
                        <th class="py-4 px-6">{{ __("ui.action_initiated_by") }}</th>
                        <th class="py-4 px-6">{{ __("ui.log_specifics") }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-800 text-gray-700 dark:text-gray-300">
                    @forelse($logs as $log)
                    <tr class="hover:bg-gray-50/40 dark:hover:bg-slate-800/30 transition-colors">
                        <td class="py-4 px-6 text-gray-500 font-mono text-[11px] whitespace-nowrap">{{ $log->created_at ? $log->created_at->toISOString() : '—' }}</td>
                        <td class="py-4 px-6">
                            @if($log->action === 'CREATE')
                            <span class="inline-flex items-center rounded-md px-2.5 py-1 text-[10px] font-black uppercase tracking-wider bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/50">Create</span>
                            @elseif($log->action === 'UPDATE')
                            <span class="inline-flex items-center rounded-md px-2.5 py-1 text-[10px] font-black uppercase tracking-wider bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400 border border-amber-100 dark:border-amber-900/50">Update</span>
                            @elseif($log->action === 'DELETE')
                            <span class="inline-flex items-center rounded-md px-2.5 py-1 text-[10px] font-black uppercase tracking-wider bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-400 border border-rose-100 dark:border-rose-900/50">Delete</span>
                            @endif
                        </td>
                        <td class="py-4 px-6">
                            <div>
                                <span class="font-bold text-gray-900 dark:text-white text-xs">{{ $log->user->name ?? 'System' }}</span>
                                <span class="block text-[10px] text-gray-400 font-mono">KeyPrefix: user-{{ $log->user_id ? Str::substr(md5($log->user_id), 0, 6) : '000000' }}</span>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-xs text-gray-600 dark:text-gray-400 max-w-md">{{ $log->description }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="py-12 text-center text-gray-400">{{ __("ui.no_logs") }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($logs->hasPages())
        <div class="p-4 border-t border-gray-100 dark:border-slate-800">
            {{ $logs->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
