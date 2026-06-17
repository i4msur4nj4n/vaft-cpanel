@extends('layouts.app')
@section('content')
<div x-data="subscriptionManager()" class="space-y-6">
    {{-- Tabs --}}
    <div class="flex border-b border-gray-150 dark:border-slate-800 gap-2 overflow-x-auto">
        <a href="/admin-panel" class="px-4 py-3 text-xs font-bold tracking-tight border-b-2 border-transparent text-gray-500 hover:text-gray-700 whitespace-nowrap">{{ __("ui.user_control") }}</a>
        <a href="/admin-panel/subscriptions" class="px-4 py-3 text-xs font-bold tracking-tight border-b-2 border-emerald-600 text-emerald-700 dark:text-emerald-400 whitespace-nowrap">{{ __("ui.manage_subscriptions") }}</a>
        <a href="/admin-panel/security-log" class="px-4 py-3 text-xs font-bold tracking-tight border-b-2 border-transparent text-gray-500 hover:text-gray-700 whitespace-nowrap">{{ __("ui.security_log") }}</a>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-gradient-to-br from-emerald-500 to-emerald-700 rounded-2xl p-5 text-white shadow-lg">
            <p class="text-xs font-semibold opacity-80 uppercase tracking-wider">{{ __("ui.total_subscription_sales") }}</p>
            <p class="text-2xl font-black mt-1">৳{{ number_format($totalSales) }} <span class="text-sm font-medium opacity-70">BDT</span></p>
        </div>
        <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 border border-gray-100 dark:border-slate-800 shadow-sm">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __("ui.active_subscribers") }}</p>
            <p class="text-2xl font-black text-gray-900 dark:text-white mt-1">{{ $activeCount }}</p>
        </div>
        <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 border border-gray-100 dark:border-slate-800 shadow-sm">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __("ui.pending_approval") }}</p>
            <p class="text-2xl font-black text-amber-600 mt-1">{{ $pendingCount }}</p>
        </div>
        <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 border border-gray-100 dark:border-slate-800 shadow-sm">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __("ui.trial_users") }}</p>
            <p class="text-2xl font-black text-gray-900 dark:text-white mt-1">{{ $trialCount }}</p>
        </div>
    </div>

    {{-- User Subscription Records --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-100 dark:border-slate-800">
            <h2 class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-wide">{{ __("ui.user_subscription_records") }}</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 dark:bg-slate-950/50 border-b border-gray-100 dark:border-slate-800 text-gray-500 dark:text-gray-400 uppercase tracking-wider font-semibold">
                        <th class="py-3 px-5">Subscriber Name</th>
                        <th class="py-3 px-5">Email</th>
                        <th class="py-3 px-5">Tier Plan</th>
                        <th class="py-3 px-5">Status</th>
                        <th class="py-3 px-5">Amount (BDT)</th>
                        <th class="py-3 px-5">Expiry Date</th>
                        <th class="py-3 px-5">bKash/TxRef</th>
                        <th class="py-3 px-5 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-800 text-gray-700 dark:text-gray-300">
                    @forelse($subscriptions as $sub)
                    <tr class="hover:bg-gray-50/40 dark:hover:bg-slate-800/30 transition-colors">
                        <td class="py-3 px-5 font-bold text-gray-900 dark:text-white">{{ $sub->user->name ?? '—' }}</td>
                        <td class="py-3 px-5 text-gray-500">{{ $sub->user->email ?? '—' }}</td>
                        <td class="py-3 px-5">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-400 border border-indigo-100 dark:border-indigo-900/50">
                                {{ ($lang === "bn" ? ($sub->plan->name_bn ?: $sub->plan->name_en) : $sub->plan->name_en) ?? '—' }}
                            </span>
                        </td>
                        <td class="py-3 px-5">
                            @if($sub->status === 'active')
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/50">Active</span>
                            @elseif($sub->status === 'pending')
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400 border border-amber-100 dark:border-amber-900/50">Pending</span>
                            @elseif($sub->status === 'expired')
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-400 border border-rose-100 dark:border-rose-900/50">Expired</span>
                            @else
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase bg-gray-50 dark:bg-gray-950/40 text-gray-700 dark:text-gray-400 border border-gray-100 dark:border-gray-900/50">Cancelled</span>
                            @endif
                        </td>
                        <td class="py-3 px-5 font-bold">৳{{ number_format($sub->amount_paid) }}</td>
                        <td class="py-3 px-5 text-gray-500">{{ $sub->expires_at ? $sub->expires_at->format('M d, Y') : '—' }}</td>
                        <td class="py-3 px-5 font-mono text-gray-500">{{ $sub->trx_ref ?? '—' }}</td>
                        <td class="py-3 px-5 text-center">
                            <button @click="openEditUser({{ json_encode(['id'=>$sub->user_id,'name'=>$sub->user->name??'','plan_id'=>$sub->plan_id,'status'=>$sub->status,'amount_paid'=>$sub->amount_paid,'expires_at'=>$sub->expires_at?->format('Y-m-d'),'trx_ref'=>$sub->trx_ref]) }})" class="inline-flex items-center justify-center h-8 w-8 rounded-lg border border-gray-200 dark:border-slate-700 text-gray-500 hover:text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 transition-all cursor-pointer">
                                ✏️
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="py-8 text-center text-gray-400">No subscription records yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Manage Subscription Plans --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-100 dark:border-slate-800 flex items-center justify-between">
            <h2 class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-wide">{{ __("ui.manage_plans") }}</h2>
            <button @click="openCreatePlan()" class="inline-flex items-center gap-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 text-xs font-bold transition-all cursor-pointer shadow-sm">
                {{ __("ui.add_new_plan") }}
            </button>
        </div>
        <div class="p-5 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($plans as $plan)
            <div class="rounded-xl border border-gray-100 dark:border-slate-800 p-5 space-y-3 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <h3 class="font-black text-gray-900 dark:text-white text-sm">{{ ($lang === "bn" ? ($plan->name_bn ?: $plan->name_en) : $plan->name_en) }}</h3>
                    <div class="flex gap-1">
                        <button @click="openEditPlan({{ json_encode($plan) }})" class="h-7 w-7 rounded-lg border border-gray-200 dark:border-slate-700 flex items-center justify-center text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 transition-all cursor-pointer text-xs">✏️</button>
                        <form method="POST" action="/admin-panel/subscriptions/plans/{{ $plan->id }}" onsubmit="return confirm('Delete this plan?')">
                            @csrf @method('DELETE')
                            <button class="h-7 w-7 rounded-lg border border-gray-200 dark:border-slate-700 flex items-center justify-center text-gray-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/20 transition-all cursor-pointer text-xs">🗑️</button>
                        </form>
                    </div>
                </div>
                @if($plan->name_bn)
                <p class="text-xs text-gray-400">{{ $plan->name_bn }}</p>
                @endif
                <p class="text-[10px] text-gray-400 uppercase font-mono">ID: {{ $plan->slug }}</p>
                <div class="flex items-baseline gap-1">
                    <span class="text-xl font-black text-emerald-600">৳{{ number_format($plan->price) }}</span>
                    <span class="text-xs text-gray-500">/{{ $plan->period === 'month' ? 'month' : 'year' }}</span>
                </div>
                @if($plan->features_en)
                <ul class="space-y-1">
                    @foreach($plan->features_en as $feature)
                    <li class="flex items-start gap-2 text-xs text-gray-600 dark:text-gray-400">
                        <span class="text-emerald-500 mt-0.5">✓</span>
                        <span>{{ $feature }}</span>
                    </li>
                    @endforeach
                </ul>
                @endif
            </div>
            @empty
            <p class="text-gray-400 text-sm col-span-3">No plans created yet.</p>
            @endforelse
        </div>
    </div>

    {{-- Manage Payment Gateways --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-100 dark:border-slate-800 flex items-center justify-between">
            <h2 class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-wide">{{ __("ui.manage_gateways") }}</h2>
            <button @click="openCreateGateway()" class="inline-flex items-center gap-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 text-xs font-bold transition-all cursor-pointer shadow-sm">
                {{ __("ui.add_gateway") }}
            </button>
        </div>
        <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-4">
            @forelse($gateways as $gw)
            <div class="rounded-xl border border-gray-100 dark:border-slate-800 p-5 space-y-3 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/50">{{ $lang === "bn" ? ($gw->name_bn ?: $gw->name) : $gw->name }}</span>
                        @if($gw->name_bn)<span class="ml-1 text-xs text-gray-400">{{ $gw->name_bn }}</span>@endif
                        <p class="text-[10px] text-gray-400 font-mono mt-1">ID: {{ $gw->slug }}</p>
                    </div>
                    <div class="flex gap-1">
                        <button @click="openEditGateway({{ json_encode($gw) }})" class="h-7 w-7 rounded-lg border border-gray-200 dark:border-slate-700 flex items-center justify-center text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 transition-all cursor-pointer text-xs">✏️</button>
                        <form method="POST" action="/admin-panel/subscriptions/gateways/{{ $gw->id }}" onsubmit="return confirm('Delete this gateway?')">
                            @csrf @method('DELETE')
                            <button class="h-7 w-7 rounded-lg border border-gray-200 dark:border-slate-700 flex items-center justify-center text-gray-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/20 transition-all cursor-pointer text-xs">🗑️</button>
                        </form>
                    </div>
                </div>
                <div class="rounded-lg border border-dashed border-gray-200 dark:border-slate-700 p-3 bg-gray-50/50 dark:bg-slate-950/30">
                    <p class="text-xs text-gray-500 uppercase font-semibold tracking-wider">Account Details</p>
                    <p class="text-sm font-bold text-gray-900 dark:text-white mt-1 font-mono">{{ $gw->account_number }}</p>
                </div>
                @if($gw->instructions)
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold tracking-wider">Payment Instruction Guidelines</p>
                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1 leading-relaxed">{{ $gw->instructions }}</p>
                </div>
                @endif
                @if(!$gw->is_active)
                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-bold uppercase bg-rose-50 text-rose-600 border border-rose-100">Inactive</span>
                @endif
            </div>
            @empty
            <p class="text-gray-400 text-sm col-span-2">No gateways configured yet.</p>
            @endforelse
        </div>
    </div>

    {{-- Create/Edit Plan Modal --}}
    <div x-show="showPlanModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background:rgba(0,0,0,0.5)">
        <div @click.away="showPlanModal=false" class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 p-5 flex items-center justify-between">
                <h3 class="text-white font-black text-base flex items-center gap-2">
                    <span>✦</span>
                    <span x-text="editingPlan ? 'Modify Subscription Plan' : 'Create Subscription Plan'"></span>
                </h3>
                <button @click="showPlanModal=false" class="text-white/80 hover:text-white text-xl cursor-pointer">✕</button>
            </div>
            <form :action="editingPlan ? '/admin-panel/subscriptions/plans/' + editingPlan.id : '/admin-panel/subscriptions/plans'" method="POST" class="p-6 space-y-5">
                @csrf
                <template x-if="editingPlan"><input type="hidden" name="_method" value="PUT"></template>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Plan Name (English)</label>
                        <input type="text" name="name_en" x-model="planForm.name_en" placeholder="e.g. Basic Plan" class="w-full rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none" required>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Plan Name (Bangla)</label>
                        <input type="text" name="name_bn" x-model="planForm.name_bn" placeholder="যেমন: বেসিক প্ল্যান" class="w-full rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Price in BDT (৳)</label>
                        <input type="number" name="price" x-model="planForm.price" min="0" class="w-full rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none" required>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Billing Interval</label>
                        <select name="period" x-model="planForm.period" class="w-full rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none cursor-pointer">
                            <option value="month">Month / মাসিক</option>
                            <option value="year">Year / বার্ষিক</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Features List (English - Newline Separated)</label>
                        <textarea name="features_en" x-model="planForm.features_en" rows="4" placeholder="Access to accounting ledger&#10;Download CSV reports" class="w-full rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none resize-y"></textarea>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Features List (Bangla - Newline Separated)</label>
                        <textarea name="features_bn" x-model="planForm.features_bn" rows="4" placeholder="মূল হিসাব খাতার অ্যাক্সেস&#10;সিএসভি রিপোর্ট ডাউনলোড" class="w-full rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none resize-y"></textarea>
                    </div>
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="showPlanModal=false" class="px-5 py-2.5 rounded-xl border border-gray-200 dark:border-slate-700 text-sm font-bold text-gray-600 hover:bg-gray-50 dark:hover:bg-slate-800 transition-all cursor-pointer">{{ __("ui.cancel") }}</button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold transition-all cursor-pointer shadow-sm" x-text="editingPlan ? 'Save Changes' : 'Create Plan'"></button>
                </div>
            </form>
        </div>
    </div>

    {{-- Create/Edit Gateway Modal --}}
    <div x-show="showGatewayModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background:rgba(0,0,0,0.5)">
        <div @click.away="showGatewayModal=false" class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 p-5 flex items-center justify-between">
                <h3 class="text-white font-black text-base flex items-center gap-2">
                    <span>💳</span>
                    <span x-text="editingGateway ? 'Modify Payment Gateway' : 'Create Payment Gateway'"></span>
                </h3>
                <button @click="showGatewayModal=false" class="text-white/80 hover:text-white text-xl cursor-pointer">✕</button>
            </div>
            <form :action="editingGateway ? '/admin-panel/subscriptions/gateways/' + editingGateway.id : '/admin-panel/subscriptions/gateways'" method="POST" class="p-6 space-y-5">
                @csrf
                <template x-if="editingGateway"><input type="hidden" name="_method" value="PUT"></template>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Gateway Name (English)</label>
                        <input type="text" name="name" x-model="gatewayForm.name" placeholder="e.g. bKash, Rocket, City Bank" class="w-full rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none" required>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Gateway Name (Bangla)</label>
                        <input type="text" name="name_bn" x-model="gatewayForm.name_bn" placeholder="যেমন: বিকাশ, নগদ, রকেট" class="w-full rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Account Number / Details</label>
                    <input type="text" name="account_number" x-model="gatewayForm.account_number" placeholder="e.g. +880 1712 345678 (Personal), Bank AC info..." class="w-full rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none" required>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Instructions (English)</label>
                        <textarea name="instructions" x-model="gatewayForm.instructions" rows="3" placeholder="Choose &quot;Send Money&quot; or &quot;Cash Out&quot; to this Personal wallet, then submit the TrxID below." class="w-full rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none resize-y"></textarea>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Instructions (Bangla)</label>
                        <textarea name="instructions_bn" x-model="gatewayForm.instructions_bn" rows="3" placeholder="এই বিকাশ পার্সোনাল নাম্বারে &quot;সেন্ড মানি&quot; করুন, তারপর নিচে ট্রানজেকশন আইডি (TrxID) দিন।" class="w-full rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none resize-y"></textarea>
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Status</label>
                    <select name="is_active" x-model="gatewayForm.is_active" class="w-full rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none cursor-pointer">
                        <option value="1">Active / সক্রিয়</option>
                        <option value="0">Inactive / নিষ্ক্রিয়</option>
                    </select>
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="showGatewayModal=false" class="px-5 py-2.5 rounded-xl border border-gray-200 dark:border-slate-700 text-sm font-bold text-gray-600 hover:bg-gray-50 dark:hover:bg-slate-800 transition-all cursor-pointer">{{ __("ui.cancel") }}</button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold transition-all cursor-pointer shadow-sm" x-text="editingGateway ? 'Save Changes' : 'Add Gateway'"></button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit User Subscription Modal --}}
    <div x-show="showUserModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background:rgba(0,0,0,0.5)">
        <div @click.away="showUserModal=false" class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden">
            <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 p-5 flex items-center justify-between">
                <h3 class="text-white font-black text-base flex items-center gap-2">
                    <span>⚙️</span>
                    <span>Configure User Subscription</span>
                </h3>
                <button @click="showUserModal=false" class="text-white/80 hover:text-white text-xl cursor-pointer">✕</button>
            </div>
            <form :action="'/admin-panel/subscriptions/users/' + userForm.id" method="POST" class="p-6 space-y-5">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <p class="text-sm font-bold text-gray-700 dark:text-gray-300">Editing: <span class="text-emerald-600" x-text="userForm.name"></span></p>
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Subscription Tier Plan</label>
                    <select name="plan_id" x-model="userForm.plan_id" class="w-full rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none cursor-pointer" required>
                        @foreach($plans as $plan)
                        <option value="{{ $plan->id }}">{{ ($lang === "bn" ? ($plan->name_bn ?: $plan->name_en) : $plan->name_en) }} (৳{{ number_format($plan->price) }}/{{ $plan->period }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Billing Status</label>
                    <select name="status" x-model="userForm.status" class="w-full rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none cursor-pointer" required>
                        <option value="active">Active</option>
                        <option value="expired">Expired</option>
                        <option value="pending">Pending</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Total Amount Collected (BDT)</label>
                    <input type="number" name="amount_paid" x-model="userForm.amount_paid" min="0" class="w-full rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none" required>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Access Expiration Date</label>
                    <input type="date" name="expires_at" x-model="userForm.expires_at" class="w-full rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none" required>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">bKash/Nagad Transaction Reference (TrxID)</label>
                    <input type="text" name="trx_ref" x-model="userForm.trx_ref" placeholder="e.g. BK1245TX" class="w-full rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="showUserModal=false" class="px-5 py-2.5 rounded-xl border border-gray-200 dark:border-slate-700 text-sm font-bold text-gray-600 hover:bg-gray-50 dark:hover:bg-slate-800 transition-all cursor-pointer">{{ __("ui.cancel") }}</button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold transition-all cursor-pointer shadow-sm">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3/dist/cdn.min.js" defer></script>
<script>
function subscriptionManager() {
    return {
        showPlanModal: false,
        showGatewayModal: false,
        showUserModal: false,
        editingPlan: null,
        editingGateway: null,
        planForm: { name_en: '', name_bn: '', price: 0, period: 'month', features_en: '', features_bn: '' },
        gatewayForm: { name: '', name_bn: '', account_number: '', instructions: '', instructions_bn: '', is_active: '1' },
        userForm: { id: null, name: '', plan_id: '', status: 'active', amount_paid: 0, expires_at: '', trx_ref: '' },

        openCreatePlan() {
            this.editingPlan = null;
            this.planForm = { name_en: '', name_bn: '', price: 0, period: 'month', features_en: '', features_bn: '' };
            this.showPlanModal = true;
        },
        openEditPlan(plan) {
            this.editingPlan = plan;
            this.planForm = {
                name_en: plan.name_en,
                name_bn: plan.name_bn || '',
                price: plan.price,
                period: plan.period,
                features_en: (plan.features_en || []).join('\n'),
                features_bn: (plan.features_bn || []).join('\n'),
            };
            this.showPlanModal = true;
        },
        openCreateGateway() {
            this.editingGateway = null;
            this.gatewayForm = { name: '', name_bn: '', account_number: '', instructions: '', instructions_bn: '', is_active: '1' };
            this.showGatewayModal = true;
        },
        openEditGateway(gw) {
            this.editingGateway = gw;
            this.gatewayForm = {
                name: gw.name,
                name_bn: gw.name_bn || '',
                account_number: gw.account_number,
                instructions: gw.instructions || '',
                instructions_bn: gw.instructions_bn || '',
                is_active: gw.is_active ? '1' : '0'
            };
            this.showGatewayModal = true;
        },
        openEditUser(data) {
            this.userForm = data;
            this.showUserModal = true;
        }
    }
}
</script>
@endsection
