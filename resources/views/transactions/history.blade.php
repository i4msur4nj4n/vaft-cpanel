@extends('layouts.app')

@section('content')
<div x-data="txnHistory()" class="space-y-6">

    {{-- Filters --}}
    <div class="rounded-2xl border border-gray-100 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm p-6">
        <div class="flex items-center gap-2 mb-5">
            <svg class="w-4 h-4 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
            <h2 class="text-sm font-bold uppercase tracking-wider text-gray-900 dark:text-white">{{ __('ui.filter') }}</h2>
        </div>

        <form method="GET" action="/transactions/history" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3">
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-500 mb-1">{{ __('ui.from_date') }}</label>
                <input type="date" name="from_date" value="{{ request('from_date') }}" class="w-full rounded-lg border border-gray-200 dark:border-slate-800 bg-gray-50/55 dark:bg-slate-950 text-xs py-2 px-3">
            </div>
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-500 mb-1">{{ __('ui.to_date') }}</label>
                <input type="date" name="to_date" value="{{ request('to_date') }}" class="w-full rounded-lg border border-gray-200 dark:border-slate-800 bg-gray-50/55 dark:bg-slate-950 text-xs py-2 px-3">
            </div>
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-500 mb-1">{{ __('ui.category') }}</label>
                <select name="category_id" class="w-full rounded-lg border border-gray-200 dark:border-slate-800 bg-gray-50/55 dark:bg-slate-950 text-xs py-2 px-3">
                    <option value="">All</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $lang === 'bn' ? ($cat->name_bn ?: $cat->name_en) : $cat->name_en }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-500 mb-1">{{ __('ui.type') }}</label>
                <select name="type" class="w-full rounded-lg border border-gray-200 dark:border-slate-800 bg-gray-50/55 dark:bg-slate-950 text-xs py-2 px-3">
                    <option value="">All</option>
                    <option value="income" {{ request('type') === 'income' ? 'selected' : '' }}>{{ __('ui.income') }}</option>
                    <option value="expense" {{ request('type') === 'expense' ? 'selected' : '' }}>{{ __('ui.expense') }}</option>
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-500 mb-1">{{ __('ui.search') }}</label>
                <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="..." class="w-full rounded-lg border border-gray-200 dark:border-slate-800 bg-gray-50/55 dark:bg-slate-950 text-xs py-2 px-3" oninput="clearTimeout(this._t);this._t=setTimeout(()=>this.form.submit(),500)">
            </div>
            <div class="lg:col-span-5 flex items-center gap-3 pt-2">
                <button type="submit" class="rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-4 py-2.5 transition">{{ __('ui.filter') }}</button>
                <a href="/transactions/history" class="rounded-xl border border-gray-200 dark:border-slate-700 text-xs font-bold text-gray-600 px-4 py-2.5 hover:bg-gray-50 dark:hover:bg-slate-800 transition">{{ __('ui.clear') }}</a>
            </div>
        </form>
    </div>

    {{-- Transaction Table --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead><tr class="border-b border-gray-100 dark:border-slate-800 text-gray-500 uppercase tracking-wider font-semibold">
                    <th class="py-4 px-5">{{ __('ui.date') }}</th>
                    <th class="py-4 px-5">{{ __('ui.category') }}</th>
                    <th class="py-4 px-5">{{ __('ui.type') }}</th>
                    <th class="py-4 px-5">{{ __('ui.notes') }}</th>
                    <th class="py-4 px-5">{{ __('ui.user') }}</th>
                    <th class="py-4 px-5 text-right">{{ __('ui.amount') }} (BDT)</th>
                    <th class="py-4 px-5 text-center">{{ __('ui.actions') }}</th>
                </tr></thead>
                <tbody class="divide-y divide-gray-50 dark:divide-slate-800/50">
                    @forelse($transactions as $txn)
                    <tr class="hover:bg-gray-50/40 dark:hover:bg-slate-800/30 transition-colors">
                        <td class="py-4 px-5 font-medium text-gray-500">{{ $txn->date->format('d/m/Y') }}</td>
                        <td class="py-4 px-5 font-bold text-gray-900 dark:text-white">{{ $lang === 'bn' ? ($txn->category->name_bn ?: $txn->category->name_en) : $txn->category->name_en }}</td>
                        <td class="py-4 px-5">
                            @if($txn->type === 'income')
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400">{{ __('ui.income') }}</span>
                            @else
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-400">{{ __('ui.expense') }}</span>
                            @endif
                        </td>
                        <td class="py-4 px-5 text-gray-600 dark:text-gray-400 max-w-[200px] truncate">{{ $txn->notes }}</td>
                        <td class="py-4 px-5 text-gray-600">{{ $txn->user->name ?? '—' }}</td>
                        <td class="py-4 px-5 text-right font-bold {{ $txn->type === 'income' ? 'text-emerald-600' : 'text-rose-600' }}">{{ $txn->type === 'income' ? '+' : '-' }} ৳{{ number_format($txn->amount) }}</td>
                        <td class="py-4 px-5 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button @click="openEdit({{ json_encode(['id'=>$txn->id,'type'=>$txn->type,'date'=>$txn->date->format('Y-m-d'),'amount'=>$txn->amount,'category_id'=>$txn->category_id,'project_id'=>$txn->project_id,'notes'=>$txn->notes]) }})" class="h-7 w-7 rounded-lg border border-gray-200 dark:border-slate-700 flex items-center justify-center text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 transition-all cursor-pointer">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z"/></svg>
                                </button>
                                <button @click="openDelete({{ $txn->id }})" class="h-7 w-7 rounded-lg border border-gray-200 dark:border-slate-700 flex items-center justify-center text-gray-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/20 transition-all cursor-pointer">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="py-12 text-center text-gray-400">{{ __('ui.no_records') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Edit Transaction Modal --}}
    <div x-show="showEditModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background:rgba(0,0,0,0.5)">
        <div @click.away="showEditModal=false" class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden">
            <div class="bg-gradient-to-r from-emerald-600 to-teal-700 p-5">
                <h3 class="text-white font-black text-base">{{ __("ui.update_entry") }}</h3>
                <p class="text-white/70 text-xs mt-1">{{ __("ui.record_desc") }}</p>
            </div>
            <form :action="'/transactions/' + editForm.id" method="POST" class="p-6 space-y-5">
                @csrf
                <input type="hidden" name="_method" value="PUT">

                {{-- Transaction Type --}}
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-500 mb-2">TRANSACTION TYPE</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label :class="editForm.type === 'expense' ? 'border-rose-400 bg-rose-50/50 dark:bg-rose-950/20' : 'border-gray-200 dark:border-slate-700'" class="flex items-center justify-center gap-2 rounded-xl border py-3 cursor-pointer transition-all">
                            <input type="radio" name="type" value="expense" x-model="editForm.type" class="hidden">
                            <span class="w-2.5 h-2.5 rounded-full" :class="editForm.type === 'expense' ? 'bg-rose-500' : 'bg-gray-300'"></span>
                            <span class="text-sm font-bold" :class="editForm.type === 'expense' ? 'text-rose-600' : 'text-gray-500'">Expense (ব্যয়)</span>
                        </label>
                        <label :class="editForm.type === 'income' ? 'border-emerald-400 bg-emerald-50/50 dark:bg-emerald-950/20' : 'border-gray-200 dark:border-slate-700'" class="flex items-center justify-center gap-2 rounded-xl border py-3 cursor-pointer transition-all">
                            <input type="radio" name="type" value="income" x-model="editForm.type" class="hidden">
                            <span class="w-2.5 h-2.5 rounded-full" :class="editForm.type === 'income' ? 'bg-emerald-500' : 'bg-gray-300'"></span>
                            <span class="text-sm font-bold" :class="editForm.type === 'income' ? 'text-emerald-600' : 'text-gray-500'">Income (আয়)</span>
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-500 mb-1.5">SELECT DATE</label>
                        <input type="date" name="date" x-model="editForm.date" class="w-full rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-500 outline-none" required>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-500 mb-1.5">AMOUNT (BDT)</label>
                        <input type="number" name="amount" x-model="editForm.amount" step="0.01" min="0.01" class="w-full rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-500 outline-none" required>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-500 mb-1.5">CATEGORY SELECTOR</label>
                        <select name="category_id" x-model="editForm.category_id" class="w-full rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-500 outline-none cursor-pointer" required>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $lang === 'bn' ? ($cat->name_bn ?: $cat->name_en) : $cat->name_en }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-500 mb-1.5">INVESTMENT PROJECT</label>
                        <select name="project_id" x-model="editForm.project_id" class="w-full rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-500 outline-none cursor-pointer">
                            <option value="">General (No Project)</option>
                            @foreach(\App\Models\Project::where('status','active')->get() as $proj)
                            <option value="{{ $proj->id }}">{{ $proj->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-500 mb-1.5">SHORT NOTES / DESCRIPTION</label>
                    <textarea name="notes" x-model="editForm.notes" rows="2" class="w-full rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-950 px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-500 outline-none resize-y"></textarea>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="showEditModal=false" class="px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-700 text-xs font-bold text-gray-600 hover:bg-gray-50 dark:hover:bg-slate-800 transition-all cursor-pointer">Cancel</button>
                    <button type="submit" class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-all cursor-pointer shadow-sm">{{ __("ui.save_transaction") }}</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background:rgba(0,0,0,0.5)">
        <div @click.away="showDeleteModal=false" class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden">
            <div class="p-6 text-center space-y-4">
                <div class="mx-auto w-14 h-14 rounded-full bg-rose-50 dark:bg-rose-950/30 flex items-center justify-center">
                    <svg class="w-7 h-7 text-rose-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                </div>
                <h3 class="text-lg font-black text-gray-900 dark:text-white">{{ __("ui.delete_txn_title") }}</h3>
                <p class="text-sm text-gray-500">{{ __("ui.delete_txn_desc") }}</p>
                <div class="flex gap-3 justify-center pt-2">
                    <button @click="showDeleteModal=false" class="px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-700 text-xs font-bold text-gray-600 hover:bg-gray-50 dark:hover:bg-slate-800 transition-all cursor-pointer">Cancel</button>
                    <form :action="'/transactions/' + deleteId" method="POST">
                        @csrf
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="px-4 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold transition-all cursor-pointer shadow-sm">{{ __("ui.delete") }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3/dist/cdn.min.js" defer></script>
<script>
function txnHistory() {
    return {
        showEditModal: false,
        showDeleteModal: false,
        deleteId: null,
        editForm: { id: null, type: 'expense', date: '', amount: 0, category_id: '', project_id: '', notes: '' },

        openEdit(txn) {
            this.editForm = txn;
            this.showEditModal = true;
        },
        openDelete(id) {
            this.deleteId = id;
            this.showDeleteModal = true;
        }
    }
}
</script>
@endsection
