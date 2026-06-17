@extends('layouts.app')
@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div>
        <div class="flex items-center gap-3 mb-2">
            <span class="text-lg">📖</span>
            <h2 class="text-lg font-extrabold text-gray-900 dark:text-white tracking-tight">Advanced Enterprise Accounting Suite</h2>
        </div>
        <p class="text-xs text-gray-500 dark:text-gray-400 max-w-3xl">Bilingual double-entry ledger, accounts receivable invoice generation, accounts payable management, live bank statement reconciliation and auditor reports.</p>
    </div>

    {{-- Tabs --}}
    @php $activeTab = request('tab', 'invoicing'); @endphp
    <div class="flex border-b border-gray-200 dark:border-slate-800 gap-0 overflow-x-auto">
        <a href="/accounting?tab=invoicing" class="px-5 py-3 text-[11px] font-bold uppercase tracking-wider border-b-2 whitespace-nowrap transition-all {{ $activeTab === 'invoicing' ? 'border-emerald-600 text-emerald-700 dark:text-emerald-400' : 'border-transparent text-gray-500 hover:text-gray-700' }}">{{ __("ui.sales_invoicing") }}</a>
        <a href="/accounting?tab=payable" class="px-5 py-3 text-[11px] font-bold uppercase tracking-wider border-b-2 whitespace-nowrap transition-all {{ $activeTab === 'payable' ? 'border-emerald-600 text-emerald-700 dark:text-emerald-400' : 'border-transparent text-gray-500 hover:text-gray-700' }}">{{ __("ui.accounts_payable") }}</a>
        <a href="/accounting?tab=ledger" class="px-5 py-3 text-[11px] font-bold uppercase tracking-wider border-b-2 whitespace-nowrap transition-all {{ $activeTab === 'ledger' ? 'border-emerald-600 text-emerald-700 dark:text-emerald-400' : 'border-transparent text-gray-500 hover:text-gray-700' }}">{{ __("ui.general_ledger") }}</a>
        <a href="/accounting?tab=bank" class="px-5 py-3 text-[11px] font-bold uppercase tracking-wider border-b-2 whitespace-nowrap transition-all {{ $activeTab === 'bank' ? 'border-emerald-600 text-emerald-700 dark:text-emerald-400' : 'border-transparent text-gray-500 hover:text-gray-700' }}">{{ __("ui.bank_reconciliation") }}</a>
        <a href="/accounting?tab=auditor" class="px-5 py-3 text-[11px] font-bold uppercase tracking-wider border-b-2 whitespace-nowrap transition-all {{ $activeTab === 'auditor' ? 'border-emerald-600 text-emerald-700 dark:text-emerald-400' : 'border-transparent text-gray-500 hover:text-gray-700' }}">Auditor & Tax Reports</a>
        <a href="/accounting?tab=chart" class="px-5 py-3 text-[11px] font-bold uppercase tracking-wider border-b-2 whitespace-nowrap transition-all {{ $activeTab === 'chart' ? 'border-emerald-600 text-emerald-700 dark:text-emerald-400' : 'border-transparent text-gray-500 hover:text-gray-700' }}">{{ __("ui.chart_of_accounts") }}</a>
    </div>

    @if($activeTab === 'invoicing')
    {{-- SALES INVOICING TAB --}}
    <div class="flex items-center justify-between gap-4">
        <div class="relative flex-1 max-w-md">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
            </span>
            <input type="text" id="invoiceSearch" placeholder="Filter invoices by code or client name..." class="block w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-xs focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none">
        </div>
        <div class="flex items-center gap-3">
            <a href="/accounting/export-invoices-csv" class="px-4 py-2.5 rounded-lg bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs transition-all no-underline">{{ __("ui.export_csv_file") }}</a>
            <button onclick="document.getElementById('invoiceModal').classList.remove('hidden')" class="px-4 py-2.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition-all">+ Generate Invoice</button>
        </div>
    </div>
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead><tr class="border-b border-gray-100 dark:border-slate-800 text-gray-500 uppercase tracking-wider font-semibold"><th class="py-4 px-6">Invoice ID</th><th class="py-4 px-6">Client / Farm Customer</th><th class="py-4 px-6">Issue/Due Dates</th><th class="py-4 px-6">Gross Amount</th><th class="py-4 px-6 text-center">Payment Status</th><th class="py-4 px-6 text-center">Audit Actions</th></tr></thead>
                <tbody id="invoiceTableBody" class="divide-y divide-gray-100 dark:divide-slate-800 text-gray-700 dark:text-gray-300">
                    @foreach($invoices as $invoice)
                    <tr class="hover:bg-gray-50/40 dark:hover:bg-slate-800/30 transition-colors">
                        <td class="py-4 px-6 font-bold text-gray-900 dark:text-white">{{ $invoice->invoice_number }}</td>
                        <td class="py-4 px-6">{{ $invoice->client_name }}</td>
                        <td class="py-4 px-6"><p class="text-emerald-600 font-medium">{{ $invoice->issue_date->format('d M Y') }}</p><p class="text-rose-500 text-[11px]">{{ $invoice->due_date->format('d M Y') }}</p></td>
                        <td class="py-4 px-6 font-bold text-gray-900 dark:text-white">৳ {{ number_format($invoice->amount) }}</td>
                        <td class="py-4 px-6 text-center">@if($invoice->status === 'paid')<span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase text-emerald-700 bg-emerald-50"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Paid</span>@else<span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase text-rose-700 bg-rose-50"><span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Unpaid</span>@endif</td>
                        <td class="py-4 px-6 text-center"><div class="flex items-center justify-center gap-3"><span class="text-[10px] font-bold text-gray-500">Print / Detail</span><form method="POST" action="/accounting/invoices/{{ $invoice->id }}/toggle" class="inline">@csrf @method('PATCH')<button type="submit" class="text-[10px] font-bold text-emerald-600 hover:text-emerald-800 cursor-pointer">{{ $invoice->status === 'paid' ? 'Mark Unpaid' : 'Mark Paid' }}</button></form><button onclick="editInvoice({{ $invoice->id }})" class="text-gray-400 hover:text-emerald-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z"/></svg></button><form method="POST" action="/accounting/invoices/{{ $invoice->id }}" onsubmit="return confirm('Delete?')" class="inline">@csrf @method('DELETE')<button class="text-gray-400 hover:text-rose-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button></form></div></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


    {{-- Invoice Generation Modal --}}
    <div id="invoiceModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-gray-200 dark:border-slate-700 w-full max-w-2xl mx-4 overflow-hidden max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-slate-700">
                <div class="flex items-center gap-2"><span class="text-emerald-600">📋</span><h3 class="font-extrabold text-sm text-gray-900 dark:text-white uppercase tracking-wide">Compile New Accounts Receivable Invoice</h3></div>
                <button onclick="document.getElementById('invoiceModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-700 dark:hover:text-white"><svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg></button>
            </div>
            <form method="POST" action="/accounting/invoices" class="p-6 space-y-5" id="invoiceForm">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-wider mb-1.5">Invoice Number</label><input type="text" name="invoice_number" id="invNumber" required class="block w-full py-2.5 px-3 rounded-lg border border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none"></div>
                    <div><label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-wider mb-1.5">Client / Customer Name</label><input type="text" name="client_name" placeholder="e.g. Dhaka Agro Supplies Inc" required class="block w-full py-2.5 px-3 rounded-lg border border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none"></div>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div><label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-wider mb-1.5">Client Contact Email</label><input type="email" name="client_email" class="block w-full py-2.5 px-3 rounded-lg border border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none"></div>
                    <div><label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-wider mb-1.5">Issue Date</label><input type="date" name="issue_date" value="{{ date('Y-m-d') }}" required class="block w-full py-2.5 px-3 rounded-lg border border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none"></div>
                    <div><label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-wider mb-1.5">Due Date</label><input type="date" name="due_date" value="{{ date('Y-m-d', strtotime('+30 days')) }}" required class="block w-full py-2.5 px-3 rounded-lg border border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none"></div>
                </div>
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <label class="text-[10px] font-extrabold text-gray-500 uppercase tracking-wider">Itemized Ledger Breakdown Columns</label>
                        <button type="button" onclick="addInvoiceLine()" class="text-xs font-bold text-emerald-600 border border-emerald-600 rounded-lg px-3 py-1 hover:bg-emerald-50 transition">+ Add Invoice Row Line</button>
                    </div>
                    <div id="invoiceLines" class="space-y-2"></div>
                </div>
                <div><label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-wider mb-1.5">Invoice Internal Memo Notes</label><textarea name="notes" rows="2" placeholder="Payment settlement bank accounts details guidelines..." class="block w-full py-2.5 px-3 rounded-lg border border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none resize-y"></textarea></div>
                <div class="rounded-xl border-2 border-dashed border-gray-200 dark:border-slate-700 p-4 flex items-center justify-between">
                    <span class="text-[10px] font-extrabold text-gray-500 uppercase tracking-wider">Cumulative Invoice Amount Total:</span>
                    <span class="text-sm font-extrabold text-emerald-600" id="invoiceTotal">৳ 0 BDT</span>
                </div>
                <div class="flex justify-center gap-3 pt-2">
                    <button type="button" onclick="document.getElementById('invoiceModal').classList.add('hidden')" class="px-6 py-2.5 rounded-lg border border-gray-200 dark:border-slate-700 text-sm font-bold text-gray-600 hover:bg-gray-50 transition-all">Cancel</button>
                    <button type="submit" class="px-6 py-2.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm transition-all">Confirm \& Post Invoice</button>
                </div>
            </form>
        </div>
    </div>
    <script>
    let lineIndex = 0;
    function addInvoiceLine() {
        const container = document.getElementById('invoiceLines');
        const row = document.createElement('div');
        row.className = 'flex items-center gap-2';
        row.innerHTML = '<input type="text" name="items['+lineIndex+'][description]" placeholder="e.g. Organic Mashroom Crop" required class="flex-1 py-2 px-3 rounded-lg border border-gray-200 dark:border-slate-800 bg-gray-50/55 dark:bg-slate-950 text-xs"><input type="number" name="items['+lineIndex+'][quantity]" value="1" min="1" required class="w-16 py-2 px-2 rounded-lg border border-gray-200 dark:border-slate-800 bg-gray-50/55 dark:bg-slate-950 text-xs text-center" oninput="calcInvoiceTotal()"><input type="number" name="items['+lineIndex+'][price]" value="0" min="0" step="0.01" required class="w-24 py-2 px-2 rounded-lg border border-gray-200 dark:border-slate-800 bg-gray-50/55 dark:bg-slate-950 text-xs text-center" oninput="calcInvoiceTotal()"><span class="text-xs font-bold text-gray-500 w-20 text-right line-subtotal">৳ 0</span><button type="button" onclick="this.parentElement.remove();calcInvoiceTotal()" class="text-rose-500 hover:text-rose-700"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>';
        container.appendChild(row);
        lineIndex++;
        calcInvoiceTotal();
    }
    function calcInvoiceTotal() {
        let total = 0;
        document.querySelectorAll('#invoiceLines > div').forEach(row => {
            const qty = parseFloat(row.querySelector('input[name*="quantity"]').value) || 0;
            const price = parseFloat(row.querySelector('input[name*="price"]').value) || 0;
            const subtotal = qty * price;
            row.querySelector('.line-subtotal').textContent = '৳ ' + subtotal.toLocaleString();
            total += subtotal;
        });
        document.getElementById('invoiceTotal').textContent = '৳ ' + total.toLocaleString() + ' BDT';
    }
    // Auto-generate invoice number and add first line on modal open
    document.querySelector('[onclick*="invoiceModal"]').addEventListener('click', function() {
        if (!document.getElementById('invNumber').value) {
            document.getElementById('invNumber').value = 'INV-' + new Date().getFullYear() + '-' + Math.floor(1000 + Math.random() * 9000);
        }
        if (document.getElementById('invoiceLines').children.length === 0) addInvoiceLine();
    });
    </script>
    <script>
    document.getElementById('invoiceSearch').addEventListener('input', function() {
        const q = this.value.toLowerCase();
        document.querySelectorAll('#invoiceTableBody tr').forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(q) ? '' : 'none';
        });
    });
    </script>

    {{-- Edit Invoice Modal --}}
    <div id="editInvoiceModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-gray-200 dark:border-slate-700 w-full max-w-2xl mx-4 overflow-hidden max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-slate-700">
                <div class="flex items-center gap-2"><span class="text-emerald-600">📋</span><h3 class="font-extrabold text-sm text-gray-900 dark:text-white uppercase tracking-wide">Edit Customer Invoice</h3></div>
                <button onclick="document.getElementById('editInvoiceModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-700 dark:hover:text-white"><svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg></button>
            </div>
            <form method="POST" id="editInvoiceForm" class="p-6 space-y-5">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-wider mb-1.5">Invoice Number</label><input type="text" name="invoice_number" id="editInvNumber" required class="block w-full py-2.5 px-3 rounded-lg border border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none"></div>
                    <div><label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-wider mb-1.5">Client / Customer Name</label><input type="text" name="client_name" id="editInvClient" required class="block w-full py-2.5 px-3 rounded-lg border border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none"></div>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div><label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-wider mb-1.5">Client Contact Email</label><input type="email" name="client_email" id="editInvEmail" class="block w-full py-2.5 px-3 rounded-lg border border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none"></div>
                    <div><label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-wider mb-1.5">Issue Date</label><input type="date" name="issue_date" id="editInvIssue" required class="block w-full py-2.5 px-3 rounded-lg border border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none"></div>
                    <div><label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-wider mb-1.5">Due Date</label><input type="date" name="due_date" id="editInvDue" required class="block w-full py-2.5 px-3 rounded-lg border border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none"></div>
                </div>
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <label class="text-[10px] font-extrabold text-gray-500 uppercase tracking-wider">Itemized Ledger Breakdown Columns</label>
                        <button type="button" onclick="addEditLine()" class="text-xs font-bold text-emerald-600 border border-emerald-600 rounded-lg px-3 py-1 hover:bg-emerald-50 transition">+ Add Invoice Row Line</button>
                    </div>
                    <div id="editInvoiceLines" class="space-y-2"></div>
                </div>
                <div><label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-wider mb-1.5">Invoice Internal Memo Notes</label><textarea name="notes" id="editInvNotes" rows="2" class="block w-full py-2.5 px-3 rounded-lg border border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none resize-y"></textarea></div>
                <div class="rounded-xl border-2 border-dashed border-gray-200 dark:border-slate-700 p-4 flex items-center justify-between">
                    <span class="text-[10px] font-extrabold text-gray-500 uppercase tracking-wider">Cumulative Invoice Amount Total:</span>
                    <span class="text-sm font-extrabold text-emerald-600" id="editInvoiceTotal">৳ 0 BDT</span>
                </div>
                <div class="flex justify-center gap-3 pt-2">
                    <button type="button" onclick="document.getElementById('editInvoiceModal').classList.add('hidden')" class="px-6 py-2.5 rounded-lg border border-gray-200 dark:border-slate-700 text-sm font-bold text-gray-600 hover:bg-gray-50 transition-all">Cancel</button>
                    <button type="submit" class="px-6 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm transition-all">Save Invoice</button>
                </div>
            </form>
        </div>
    </div>
    <script>
    let editLineIndex = 0;
    function addEditLine(desc, qty, price) {
        const container = document.getElementById('editInvoiceLines');
        const row = document.createElement('div');
        row.className = 'flex items-center gap-2';
        row.innerHTML = '<input type="text" name="items['+editLineIndex+'][description]" value="'+(desc||'')+'" placeholder="e.g. Organic Crop" required class="flex-1 py-2 px-3 rounded-lg border border-gray-200 dark:border-slate-800 bg-gray-50/55 dark:bg-slate-950 text-xs"><input type="number" name="items['+editLineIndex+'][quantity]" value="'+(qty||1)+'" min="1" required class="w-16 py-2 px-2 rounded-lg border border-gray-200 dark:border-slate-800 bg-gray-50/55 dark:bg-slate-950 text-xs text-center" oninput="calcEditTotal()"><input type="number" name="items['+editLineIndex+'][price]" value="'+(price||0)+'" min="0" step="0.01" required class="w-24 py-2 px-2 rounded-lg border border-gray-200 dark:border-slate-800 bg-gray-50/55 dark:bg-slate-950 text-xs text-center" oninput="calcEditTotal()"><span class="text-xs font-bold text-gray-500 w-20 text-right edit-line-subtotal">৳ '+((qty||0)*(price||0)).toLocaleString()+'</span><button type="button" onclick="this.parentElement.remove();calcEditTotal()" class="text-rose-500 hover:text-rose-700"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>';
        container.appendChild(row);
        editLineIndex++;
        calcEditTotal();
    }
    function calcEditTotal() {
        let total = 0;
        document.querySelectorAll('#editInvoiceLines > div').forEach(row => {
            const qty = parseFloat(row.querySelector('input[name*="quantity"]').value) || 0;
            const price = parseFloat(row.querySelector('input[name*="price"]').value) || 0;
            const subtotal = qty * price;
            row.querySelector('.edit-line-subtotal').textContent = '৳ ' + subtotal.toLocaleString();
            total += subtotal;
        });
        document.getElementById('editInvoiceTotal').textContent = '৳ ' + total.toLocaleString() + ' BDT';
    }
    function editInvoice(id) {
        fetch('/accounting/invoices/' + id + '/edit')
            .then(r => r.json())
            .then(inv => {
                document.getElementById('editInvoiceForm').action = '/accounting/invoices/' + id;
                document.getElementById('editInvNumber').value = inv.invoice_number;
                document.getElementById('editInvClient').value = inv.client_name;
                document.getElementById('editInvEmail').value = inv.client_email || '';
                document.getElementById('editInvIssue').value = inv.issue_date.split('T')[0];
                document.getElementById('editInvDue').value = inv.due_date.split('T')[0];
                document.getElementById('editInvNotes').value = inv.notes || '';
                document.getElementById('editInvoiceLines').innerHTML = '';
                editLineIndex = 0;
                if (inv.items && inv.items.length) {
                    inv.items.forEach(item => addEditLine(item.description, item.quantity, item.price));
                } else {
                    addEditLine();
                }
                document.getElementById('editInvoiceModal').classList.remove('hidden');
            });
    }
    </script>

    @elseif($activeTab === 'payable')
    {{-- ACCOUNTS PAYABLE --}}
    <div class="flex items-center justify-between gap-4">
        <div class="relative flex-1 max-w-lg">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
            </span>
            <input type="text" id="apSearch" placeholder="Filter bills by code or vendor credit name..." class="block w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-xs focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none">
        </div>
        <div class="flex items-center gap-3">
            <a href="/accounting/export-vendor-bills-csv" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-lg bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs transition-all no-underline">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                Export CSV
            </a>
            <button onclick="openApModal('create')" class="px-4 py-2.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition-all">+ Create Vendor Bill</button>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead><tr class="border-b border-gray-100 dark:border-slate-800 text-gray-500 uppercase tracking-wider font-semibold"><th class="py-4 px-6">Bill Code</th><th class="py-4 px-6">Supplier / Vendor Name</th><th class="py-4 px-6">Issue & Term Dates</th><th class="py-4 px-6">Liability Balance</th><th class="py-4 px-6 text-center">Status</th><th class="py-4 px-6 text-center">Actions</th></tr></thead>
                <tbody id="apTableBody" class="divide-y divide-gray-100 dark:divide-slate-800 text-gray-700 dark:text-gray-300">
                    @foreach($vendorBills as $bill)
                    <tr class="hover:bg-gray-50/40 dark:hover:bg-slate-800/30 transition-colors">
                        <td class="py-4 px-6 font-bold text-gray-900 dark:text-white">{{ $bill->bill_code }}</td>
                        <td class="py-4 px-6"><p class="font-bold text-gray-900 dark:text-white">{{ $bill->vendor_name }}</p><p class="text-[11px] text-gray-400">{{ $bill->notes }}</p></td>
                        <td class="py-4 px-6"><p class="text-emerald-600 font-medium">Bill: {{ $bill->bill_date->format('d-M-y') }}</p><p class="text-rose-500 text-[11px]">Due: {{ $bill->due_date->format('d-M-y') }}</p></td>
                        <td class="py-4 px-6 font-bold text-gray-900 dark:text-white">৳ {{ number_format($bill->amount) }}</td>
                        <td class="py-4 px-6 text-center">
                            @if($bill->status === 'paid')
                            <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase text-emerald-700 bg-emerald-50"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Paid / Closed</span>
                            @else
                            <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase text-rose-700 bg-rose-50"><span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Outstanding</span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <form method="POST" action="/accounting/vendor-bills/{{ $bill->id }}/toggle" class="inline">@csrf @method('PATCH')
                                    @if($bill->status === 'paid')
                                    <button type="submit" class="text-[10px] font-bold text-amber-600 hover:text-amber-800 border border-amber-200 rounded-md px-2 py-0.5">Mark Unpaid</button>
                                    @else
                                    <button type="submit" class="text-[10px] font-bold text-emerald-600 hover:text-emerald-800 border border-emerald-200 rounded-md px-2 py-0.5">Settle Payment</button>
                                    @endif
                                </form>
                                <button onclick="editApBill({{ $bill->id }})" class="text-gray-400 hover:text-emerald-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z"/></svg></button>
                                <form method="POST" action="/accounting/vendor-bills/{{ $bill->id }}" onsubmit="return confirm('Delete this bill?')" class="inline">@csrf @method('DELETE')<button class="text-gray-400 hover:text-rose-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button></form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- A/P Create/Edit Modal --}}
    <div id="apModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-gray-200 dark:border-slate-700 w-full max-w-lg mx-4 overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-slate-700">
                <div class="flex items-center gap-2"><span class="text-emerald-600 text-sm font-bold">$</span><h3 id="apModalTitle" class="font-extrabold text-sm text-gray-900 dark:text-white uppercase tracking-wide">Post Accounts Payable Bill</h3></div>
                <button onclick="closeApModal()" class="text-gray-400 hover:text-gray-700 dark:hover:text-white"><svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg></button>
            </div>
            <form id="apForm" method="POST" action="/accounting/vendor-bills" class="p-6 space-y-5">
                @csrf
                <input type="hidden" id="apMethod" name="_method" value="POST">
                <div><label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-wider mb-1.5">Vendor Invoice Code Billing Target ID</label><input type="text" name="bill_code" id="apBillCode" required class="block w-full py-2.5 px-3 rounded-lg border border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none"></div>
                <div><label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-wider mb-1.5">Vendor Name (Agriculture Suppliers / Utilities)</label><input type="text" name="vendor_name" id="apVendor" placeholder="e.g. AgroSeeds Organic Wholesale" required class="block w-full py-2.5 px-3 rounded-lg border border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none"></div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-wider mb-1.5">Bill Date</label><input type="date" name="bill_date" id="apBillDate" required class="block w-full py-2.5 px-3 rounded-lg border border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none"></div>
                    <div><label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-wider mb-1.5">Due Date</label><input type="date" name="due_date" id="apDueDate" required class="block w-full py-2.5 px-3 rounded-lg border border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none"></div>
                </div>
                <div><label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-wider mb-1.5">Payable Amount Outstanding (BDT)</label><input type="number" name="amount" id="apAmount" step="0.01" min="0.01" placeholder="e.g. 18500" required class="block w-full py-2.5 px-3 rounded-lg border border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none"></div>
                <div><label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-wider mb-1.5">Short Notes</label><textarea name="notes" id="apNotes" rows="2" class="block w-full py-2.5 px-3 rounded-lg border border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none resize-y"></textarea></div>
                <div class="flex justify-center gap-3 pt-2">
                    <button type="button" onclick="closeApModal()" class="px-6 py-2.5 rounded-lg border border-gray-200 dark:border-slate-700 text-sm font-bold text-gray-600 hover:bg-gray-50 transition-all">Cancel</button>
                    <button type="submit" id="apSubmitBtn" class="px-6 py-2.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm transition-all">Register vendor Liability</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function openApModal(mode) {
        document.getElementById('apModal').classList.remove('hidden');
        if (mode === 'create') {
            document.getElementById('apModalTitle').textContent = 'Post Accounts Payable Bill';
            document.getElementById('apSubmitBtn').textContent = 'Register vendor Liability';
            document.getElementById('apForm').action = '/accounting/vendor-bills';
            document.getElementById('apMethod').value = 'POST';
            document.getElementById('apBillCode').value = 'BIL-' + new Date().getFullYear() + '-' + Math.floor(1000 + Math.random() * 9000);
            document.getElementById('apVendor').value = '';
            document.getElementById('apBillDate').value = new Date().toISOString().split('T')[0];
            const due = new Date(); due.setDate(due.getDate() + 30);
            document.getElementById('apDueDate').value = due.toISOString().split('T')[0];
            document.getElementById('apAmount').value = '';
            document.getElementById('apNotes').value = '';
        }
    }
    function closeApModal() { document.getElementById('apModal').classList.add('hidden'); }

    function editApBill(id) {
        fetch('/accounting/vendor-bills/' + id + '/edit')
            .then(r => r.json())
            .then(bill => {
                document.getElementById('apModalTitle').textContent = 'Edit Vendor Bill';
                document.getElementById('apSubmitBtn').textContent = 'Save Changes';
                document.getElementById('apForm').action = '/accounting/vendor-bills/' + id;
                document.getElementById('apMethod').value = 'PUT';
                document.getElementById('apBillCode').value = bill.bill_code;
                document.getElementById('apVendor').value = bill.vendor_name;
                document.getElementById('apBillDate').value = bill.bill_date.split('T')[0];
                document.getElementById('apDueDate').value = bill.due_date.split('T')[0];
                document.getElementById('apAmount').value = bill.amount;
                document.getElementById('apNotes').value = bill.notes || '';
                document.getElementById('apModal').classList.remove('hidden');
            });
    }

    // Search filter
    document.getElementById('apSearch').addEventListener('input', function() {
        const q = this.value.toLowerCase();
        document.querySelectorAll('#apTableBody tr').forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
        });
    });
    </script>


    @elseif($activeTab === 'ledger')
    {{-- GENERAL LEDGER --}}
    <div class="space-y-5">
        {{-- Search + Actions --}}
        <div class="flex items-center justify-between gap-4">
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                </span>
                <input type="text" id="glSearch" placeholder="Filter G/L journal rows by Account, Code or Memo Description..." class="block w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-xs focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none">
            </div>
            <div class="flex items-center gap-3">
                <a href="/accounting/export-csv" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition-all no-underline">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                    Export CSV
                </a>
                <button onclick="document.getElementById('glModal').classList.remove('hidden')" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-lg bg-gray-900 dark:bg-white dark:text-gray-900 text-white font-bold text-xs hover:bg-gray-800 transition-all">+ Post Journal Entry</button>
            </div>
        </div>

        {{-- GL Table --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead><tr class="border-b border-gray-100 dark:border-slate-800 text-gray-500 dark:text-gray-400 uppercase tracking-wider font-semibold"><th class="py-4 px-5">Post Date</th><th class="py-4 px-5">Account Code</th><th class="py-4 px-5">Particulars / Memo</th><th class="py-4 px-5">Reference Key</th><th class="py-4 px-5 text-right">Debit (Dr.)</th><th class="py-4 px-5 text-right">Credit (Cr.)</th><th class="py-4 px-5 text-center">Actions</th></tr></thead>
                    <tbody id="glTableBody" class="divide-y divide-gray-100 dark:divide-slate-800 text-gray-700 dark:text-gray-300">
                        @php $totalDebit = 0; $totalCredit = 0; @endphp
                        @foreach($journalEntries as $je)
                        @php $totalDebit += $je->debit_amount; $totalCredit += $je->credit_amount; @endphp
                        <tr class="hover:bg-gray-50/40 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="py-4 px-5 text-gray-500">{{ strtoupper($je->booking_date->format('d-M-y')) }}</td>
                            <td class="py-4 px-5 font-bold text-emerald-700 dark:text-emerald-400">{{ $je->debit_account }}</td>
                            <td class="py-4 px-5"><p class="font-bold text-gray-900 dark:text-white">{{ $je->memo }}</p></td>
                            <td class="py-4 px-5 text-gray-500">{{ $je->reference ?? '—' }}</td>
                            <td class="py-4 px-5 text-right font-bold">{{ $je->debit_amount > 0 ? '৳ ' . number_format($je->debit_amount) : '–' }}</td>
                            <td class="py-4 px-5 text-right font-bold">{{ $je->credit_amount > 0 ? '৳ ' . number_format($je->credit_amount) : '–' }}</td>
                            <td class="py-4 px-5 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button onclick="editGlEntry({{ $je->id }})" class="text-gray-400 hover:text-emerald-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z"/></svg></button>
                                    <form method="POST" action="/accounting/journal-entry/{{ $je->id }}" onsubmit="return confirm('Delete?')" class="inline">@csrf @method('DELETE')<button class="text-gray-400 hover:text-rose-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button></form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="border-t-2 border-gray-200 dark:border-slate-700">
                            <td colspan="4" class="py-4 px-5 text-right text-[11px] font-extrabold text-gray-500 uppercase tracking-wider">TOTAL TRIAL BALANCES:</td>
                            <td class="py-4 px-5 text-right font-extrabold text-rose-600">৳ {{ number_format($totalDebit) }}</td>
                            <td class="py-4 px-5 text-right font-extrabold text-rose-600">৳ {{ number_format($totalCredit) }}</td>
                            <td class="py-4 px-5"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    {{-- GL Post Journal Entry Modal --}}
    <div id="glModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-gray-200 dark:border-slate-700 w-full max-w-xl mx-4 overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-slate-700">
                <div class="flex items-center gap-2"><span class="text-emerald-600">📖</span><h3 class="font-extrabold text-sm text-gray-900 dark:text-white uppercase tracking-wide">Post Double-Entry Journal Entry</h3></div>
                <button onclick="document.getElementById('glModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-700 dark:hover:text-white"><svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg></button>
            </div>
            <form method="POST" action="/accounting/journal-entry" class="p-6 space-y-5">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-wider mb-1.5">BOOKING DATE</label><input type="date" name="booking_date" value="{{ date('Y-m-d') }}" required class="block w-full py-2.5 px-3 rounded-lg border border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none"></div>
                    <div><label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-wider mb-1.5">REFERENCE (INVOICE / JV-NO)</label><input type="text" name="reference" placeholder="JV-2026-001" class="block w-full py-2.5 px-3 rounded-lg border border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none"></div>
                </div>
                <div><label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-wider mb-1.5">TRANSACTION MEMO DESCRIPTION</label><input type="text" name="memo" placeholder="Manual bank transfer for agricultural seed tools crop expansion..." required class="block w-full py-2.5 px-3 rounded-lg border border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none"></div>
                <div class="border border-gray-200 dark:border-slate-700 rounded-xl p-4 space-y-3">
                    <p class="text-[11px] font-extrabold text-emerald-700 dark:text-emerald-400 uppercase">1. DEBIT ENTRANCE (ASSET / EXPENSE DEBIT)</p>
                    <div class="grid grid-cols-5 gap-3">
                        <input type="text" name="debit_account" placeholder="e.g. AR-11000" required class="col-span-3 py-2.5 px-3 rounded-lg border border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-xs focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none">
                        <input type="number" name="debit_amount" placeholder="Debit ৳" step="0.01" min="0" required class="col-span-2 py-2.5 px-3 rounded-lg border border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-xs focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none">
                    </div>
                </div>
                <div class="border border-gray-200 dark:border-slate-700 rounded-xl p-4 space-y-3">
                    <p class="text-[11px] font-extrabold text-rose-600 uppercase">2. CREDIT ENTRANCE (REVENUE / LIABILITY CREDIT)</p>
                    <div class="grid grid-cols-5 gap-3">
                        <input type="text" name="credit_account" placeholder="e.g. REV-41000" required class="col-span-3 py-2.5 px-3 rounded-lg border border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-xs focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none">
                        <input type="number" name="credit_amount" placeholder="Credit ৳" step="0.01" min="0" required class="col-span-2 py-2.5 px-3 rounded-lg border border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-xs focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none">
                    </div>
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="document.getElementById('glModal').classList.add('hidden')" class="px-5 py-2.5 rounded-lg border border-gray-200 dark:border-slate-700 text-sm font-bold text-gray-600 hover:bg-gray-50 transition-all">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm transition-all">Post balanced entry</button>
                </div>
            </form>
        </div>
    </div>

    {{-- GL Edit Modal --}}
    <div id="glEditModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-gray-200 dark:border-slate-700 w-full max-w-xl mx-4 overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-slate-700">
                <div class="flex items-center gap-2"><span class="text-emerald-600">📖</span><h3 class="font-extrabold text-sm text-gray-900 dark:text-white uppercase tracking-wide">Edit General Ledger Line</h3></div>
                <button onclick="document.getElementById('glEditModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-700 dark:hover:text-white"><svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg></button>
            </div>
            <form id="glEditForm" method="POST" class="p-6 space-y-5">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-wider mb-1.5">ACCOUNTING DATE</label><input type="date" name="booking_date" id="glEditDate" required class="block w-full py-2.5 px-3 rounded-lg border border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none"></div>
                    <div><label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-wider mb-1.5">REFERENCE ID CODE</label><input type="text" name="reference" id="glEditRef" class="block w-full py-2.5 px-3 rounded-lg border border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none"></div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-wider mb-1.5">ACCOUNT CODE</label><input type="text" name="debit_account" id="glEditDebitAcct" required class="block w-full py-2.5 px-3 rounded-lg border border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none"></div>
                    <div><label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-wider mb-1.5">CREDIT ACCOUNT</label><input type="text" name="credit_account" id="glEditCreditAcct" required class="block w-full py-2.5 px-3 rounded-lg border border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none"></div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-wider mb-1.5">DEBIT VALUE DR.</label><input type="number" name="debit_amount" id="glEditDebit" step="0.01" min="0" required class="block w-full py-2.5 px-3 rounded-lg border border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none"></div>
                    <div><label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-wider mb-1.5">CREDIT VALUE CR.</label><input type="number" name="credit_amount" id="glEditCredit" step="0.01" min="0" required class="block w-full py-2.5 px-3 rounded-lg border border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none"></div>
                </div>
                <div><label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-wider mb-1.5">PARTICULARS MEMO / NARRATION</label><textarea name="memo" id="glEditMemo" rows="2" required class="block w-full py-2.5 px-3 rounded-lg border border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none resize-y"></textarea></div>
                <div class="flex justify-center gap-3 pt-2">
                    <button type="button" onclick="document.getElementById('glEditModal').classList.add('hidden')" class="px-6 py-2.5 rounded-lg border border-gray-200 dark:border-slate-700 text-sm font-bold text-gray-600 hover:bg-gray-50 transition-all">Cancel</button>
                    <button type="submit" class="px-6 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm transition-all">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function editGlEntry(id) {
        fetch('/accounting/journal-entry/' + id + '/edit')
            .then(r => r.json())
            .then(je => {
                document.getElementById('glEditForm').action = '/accounting/journal-entry/' + id;
                document.getElementById('glEditDate').value = je.booking_date.split('T')[0];
                document.getElementById('glEditRef').value = je.reference || '';
                document.getElementById('glEditDebitAcct').value = je.debit_account;
                document.getElementById('glEditCreditAcct').value = je.credit_account;
                document.getElementById('glEditDebit').value = je.debit_amount;
                document.getElementById('glEditCredit').value = je.credit_amount;
                document.getElementById('glEditMemo').value = je.memo || '';
                document.getElementById('glEditModal').classList.remove('hidden');
            });
    }
    // Search
    document.getElementById('glSearch').addEventListener('input', function() {
        const q = this.value.toLowerCase();
        document.querySelectorAll('#glTableBody tr').forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
        });
    });
    </script>


    @elseif($activeTab === 'bank')
    {{-- BANK RECONCILIATION (DB-backed) --}}
    <div class="space-y-6">
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 p-6 shadow-sm flex items-center justify-between">
            <div>
                <h3 class="font-extrabold text-sm text-gray-900 dark:text-white">Interactive Bank Matching & Clearing</h3>
                <p class="text-xs text-gray-500 mt-0.5">Match uploaded bank settlement rows with internal accounts receivable invoices, bills or manual double-entry journals to clear balances.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="/accounting/export-bank-csv" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-gray-200 dark:border-slate-700 text-[11px] font-bold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-800 transition-all no-underline"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg> Export CSV</a>
                <button onclick="openBankModal('add',0,'','','','deposit')" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-gray-900 dark:bg-white dark:text-gray-900 text-white font-bold text-[11px] hover:bg-gray-800 transition-all">+ Add Bank Row</button>
            </div>
        </div>

        {{-- Vault Balance --}}
        @php
            $deposits = $bankEntries->where('flow_type', 'deposit')->sum('amount');
            $withdrawals = $bankEntries->where('flow_type', 'withdrawal')->sum('amount');
            $netFlow = $deposits - $withdrawals;
        @endphp
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-gray-100 dark:border-slate-800 p-4 shadow-sm inline-block">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">DYNAMIC VAULT STATE</p>
            <p class="text-xs text-gray-500 mt-1">Net Flow Balance:</p>
            <p class="text-sm font-extrabold {{ $netFlow >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">৳ {{ number_format($netFlow) }}</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <div class="lg:col-span-7 space-y-4">
                <p class="text-[10px] font-extrabold text-gray-500 uppercase tracking-wider">LIVE BANK STATEMENT RECEIPTS</p>

                @foreach($bankEntries as $entry)
                <div class="bg-white dark:bg-slate-900 rounded-xl {{ $entry->status === 'unmatched' ? 'border-2 border-amber-200 dark:border-amber-800/50' : 'border border-gray-200 dark:border-slate-800' }} p-3 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="text-[10px] font-bold text-gray-400">{{ strtoupper($entry->booking_date->format('d-M-y')) }}</span>
                            @if($entry->flow_type === 'deposit')
                            <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded">Inflow (Dr)</span>
                            @else
                            <span class="text-[10px] font-bold text-rose-600 bg-rose-50 px-1.5 py-0.5 rounded">Outflow (Cr)</span>
                            @endif
                            @if($entry->status === 'reconciled')
                            <span class="text-[10px] font-bold text-emerald-700 bg-emerald-100 px-1.5 py-0.5 rounded">RECONCILED</span>
                            @else
                            <span class="text-[10px] font-bold text-amber-700 bg-amber-100 px-1.5 py-0.5 rounded">UNMATCHED</span>
                            @endif
                        </div>
                        <span class="font-bold text-xs text-gray-900 dark:text-white">৳ {{ $entry->flow_type === 'withdrawal' ? '-' : '' }}{{ number_format($entry->amount) }}</span>
                    </div>
                    <p class="font-bold text-xs text-gray-900 dark:text-white mt-2">{{ $entry->description }}</p>
                    <div class="flex items-center justify-between mt-2">
                        <span class="text-[11px] text-gray-400">Ref: {{ $entry->reference ?? 'N/A' }}</span>
                        <div class="flex items-center gap-2">
                            <button onclick="openBankModal('edit',{{ $entry->id }},'{{ $entry->booking_date->format('Y-m-d') }}','{{ addslashes($entry->description) }}','{{ $entry->amount }}','{{ $entry->flow_type }}')" class="text-gray-400 hover:text-emerald-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z"/></svg></button>
                            <form method="POST" action="/accounting/bank-entry/{{ $entry->id }}" onsubmit="return confirm('Delete this entry?')" class="inline">@csrf @method('DELETE')<button class="text-gray-400 hover:text-rose-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg></button></form>
                            @if($entry->status === 'unmatched')
                            <button onclick="findMatch({{ $entry->id }})" class="px-2.5 py-1 rounded-md bg-rose-600 text-white text-[10px] font-bold hover:bg-rose-700">Find Match</button>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Match Panel (right side) --}}
            <div class="lg:col-span-5">
                <div id="matchPanelEmpty" class="bg-white dark:bg-slate-900 rounded-2xl border border-dashed border-gray-300 dark:border-slate-700 p-8 shadow-sm text-center sticky top-[100px]">
                    <div class="flex justify-center mb-4"><svg class="w-12 h-12 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg></div>
                    <h4 class="font-bold text-xs text-gray-900 dark:text-white mb-1">No transaction selected</h4>
                    <p class="text-xs text-gray-500 max-w-xs mx-auto">Select a bank statement receipt by clicking "Find Match" to reconcile with our internal farm ledger.</p>
                </div>
                <div id="matchPanelActive" class="hidden bg-white dark:bg-slate-900 rounded-2xl border border-gray-200 dark:border-slate-700 shadow-sm sticky top-[100px] overflow-hidden">
                    <div class="flex items-center justify-between px-5 py-3 border-b border-gray-200 dark:border-slate-700">
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">MATCHING SETTLEMENT FOR:</p>
                            <p class="font-extrabold text-xs text-gray-900 dark:text-white mt-0.5" id="matchTitle"></p>
                        </div>
                        <button onclick="closeMatchPanel()" class="text-gray-400 hover:text-gray-700"><svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg></button>
                    </div>
                    <div class="px-5 py-3 border-b border-gray-100 dark:border-slate-800 flex items-center justify-between">
                        <span class="text-xs font-bold text-gray-500">Target Balance Value:</span>
                        <span class="font-extrabold text-emerald-600" id="matchAmount"></span>
                    </div>
                    <div class="p-5 space-y-5 max-h-[60vh] overflow-y-auto">
                        {{-- Unpaid Invoices Section --}}
                        <div>
                            <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-wider mb-3">UNPAID INVOICES (RECEIVABLE ACCOUNTS)</p>
                            <div id="matchInvoices" class="space-y-2"></div>
                        </div>
                        {{-- Journal Entries Section --}}
                        <div>
                            <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-wider mb-3">GENERAL MANUAL LEDGER ENTRIES</p>
                            <div id="matchJournals" class="space-y-2"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Bank Modal --}}
    <div id="bankModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-gray-200 dark:border-slate-700 w-full max-w-lg mx-4 overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-slate-700">
                <div class="flex items-center gap-2"><span class="text-emerald-600">🏦</span><h3 id="bankModalTitle" class="font-extrabold text-sm text-gray-900 dark:text-white uppercase tracking-wide">CREATE SIMULATED BANK ENTRY</h3></div>
                <button onclick="closeBankModal()" class="text-gray-400 hover:text-gray-700 dark:hover:text-white"><svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg></button>
            </div>
            <form id="bankForm" method="POST" action="/accounting/bank-entry" class="p-6 space-y-5">
                @csrf
                <input type="hidden" id="bankMethod" name="_method" value="POST">
                <div><label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-wider mb-1.5">BOOKING DATE</label><input type="date" id="bankDate" name="booking_date" required class="block w-full py-2.5 px-3 rounded-lg border border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none"></div>
                <div><label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-wider mb-1.5">TRANSACTION DESCRIPTION / BANK MEMO</label><input type="text" id="bankDesc" name="description" required class="block w-full py-2.5 px-3 rounded-lg border border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none"></div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-wider mb-1.5">AMOUNT (BDT)</label><input type="number" id="bankAmount" name="amount" step="0.01" min="0.01" required class="block w-full py-2.5 px-3 rounded-lg border border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none"></div>
                    <div><label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-wider mb-1.5">CASH TYPE FLOW</label><select id="bankFlow" name="flow_type" class="block w-full py-2.5 px-3 rounded-lg border border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none"><option value="deposit">Deposit (টাকা জমা হয়েছে)</option><option value="withdrawal">Withdrawal (টাকা কাটা হয়েছে)</option></select></div>
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="closeBankModal()" class="px-5 py-2.5 rounded-lg border border-gray-200 dark:border-slate-700 text-sm font-bold text-gray-600 hover:bg-gray-50 transition-all">Cancel</button>
                    <button type="submit" id="bankSubmitBtn" class="px-5 py-2.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm transition-all">Inject bank record</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function openBankModal(mode, id, date, desc, amount, flow) {
        const form = document.getElementById('bankForm');
        document.getElementById('bankModal').classList.remove('hidden');
        document.getElementById('bankDate').value = date || new Date().toISOString().split('T')[0];
        document.getElementById('bankDesc').value = desc || '';
        document.getElementById('bankAmount').value = amount || '';
        document.getElementById('bankFlow').value = flow || 'deposit';
        if (mode === 'edit') {
            document.getElementById('bankModalTitle').textContent = 'EDIT BANK TRANSACTION ROW';
            document.getElementById('bankSubmitBtn').textContent = 'Save Changes';
            form.action = '/accounting/bank-entry/' + id;
            document.getElementById('bankMethod').value = 'PUT';
        } else {
            document.getElementById('bankModalTitle').textContent = 'CREATE SIMULATED BANK ENTRY';
            document.getElementById('bankSubmitBtn').textContent = 'Inject bank record';
            form.action = '/accounting/bank-entry';
            document.getElementById('bankMethod').value = 'POST';
        }
    }
    function closeBankModal() { document.getElementById('bankModal').classList.add('hidden'); }

    let currentBankEntryId = null;

    function findMatch(bankId) {
        currentBankEntryId = bankId;
        fetch('/accounting/bank-entry/' + bankId + '/find-match')
            .then(r => r.json())
            .then(data => {
                const be = data.bank_entry;
                document.getElementById('matchTitle').textContent = be.description;
                document.getElementById('matchAmount').textContent = '৳ ' + Number(be.amount).toLocaleString() + ' (' + (be.flow_type === 'deposit' ? 'Deposit' : 'Withdrawal') + ')';

                // Render invoices
                const invContainer = document.getElementById('matchInvoices');
                invContainer.innerHTML = '';
                if (data.invoices.length === 0) {
                    invContainer.innerHTML = '<p class="text-xs text-gray-400 italic">No unpaid invoices found.</p>';
                } else {
                    data.invoices.forEach(inv => {
                        invContainer.innerHTML += '<div class="flex items-center justify-between p-3 rounded-lg border border-gray-200 dark:border-slate-700"><div><p class="font-bold text-xs text-gray-900 dark:text-white">' + inv.invoice_number + ' - ' + inv.client_name + '</p><p class="text-[11px] text-gray-400">Due: ' + inv.due_date.split('T')[0] + ' | Value: ৳ ' + Number(inv.amount).toLocaleString() + '</p></div><button onclick="reconcileMatch(\'invoice\',' + inv.id + ')" class="px-3 py-1 rounded-md bg-emerald-600 text-white text-[10px] font-bold hover:bg-emerald-700">Match</button></div>';
                    });
                }

                // Render journal entries
                const jeContainer = document.getElementById('matchJournals');
                jeContainer.innerHTML = '';
                if (data.journal_entries.length === 0) {
                    jeContainer.innerHTML = '<p class="text-xs text-gray-400 italic">No journal entries found.</p>';
                } else {
                    data.journal_entries.forEach(je => {
                        jeContainer.innerHTML += '<div class="flex items-center justify-between p-3 rounded-lg border border-gray-200 dark:border-slate-700"><div><p class="font-bold text-xs text-gray-900 dark:text-white">' + (je.debit_account) + '</p><p class="text-[11px] text-gray-400">' + (je.memo || '') + '</p><p class="text-[11px] text-gray-400">Amt: ৳ ' + Number(je.debit_amount).toLocaleString() + '</p></div><button onclick="reconcileMatch(\'journal\',' + je.id + ')" class="px-3 py-1 rounded-md bg-gray-600 text-white text-[10px] font-bold hover:bg-gray-700">Relate</button></div>';
                    });
                }

                document.getElementById('matchPanelEmpty').classList.add('hidden');
                document.getElementById('matchPanelActive').classList.remove('hidden');
            });
    }

    function closeMatchPanel() {
        document.getElementById('matchPanelActive').classList.add('hidden');
        document.getElementById('matchPanelEmpty').classList.remove('hidden');
        currentBankEntryId = null;
    }

    function reconcileMatch(type, matchedId) {
        if (!currentBankEntryId) return;
        // Submit a form to reconcile
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/accounting/bank-entry/' + currentBankEntryId + '/reconcile';

        const csrf = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').content : document.querySelector('input[name="_token"]').value;
        form.innerHTML = '<input type="hidden" name="_token" value="' + csrf + '"><input type="hidden" name="matched_type" value="' + type + '"><input type="hidden" name="matched_id" value="' + matchedId + '">';
        document.body.appendChild(form);
        form.submit();
    }
    </script>

    @elseif($activeTab === 'auditor')
    {{-- AUDITOR & TAX REPORTS --}}
    @php
        $allTransactions = \App\Models\Transaction::all();
        $reportIncome = $allTransactions->where('type', 'income')->sum('amount');
        $reportExpense = $allTransactions->where('type', 'expense')->sum('amount');
        $accounts = \App\Models\Account::orderBy('code')->get();
        $totalAssets = $accounts->where('class', 'asset')->count() > 0 ? $reportIncome - $reportExpense + 500000 : 0;
    @endphp
    <div class="space-y-6">
        {{-- Module Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h3 class="font-extrabold text-sm text-gray-900 dark:text-white tracking-tight">COMPLIANCE AUDITOR REPORTS MODULE</h3>
                <p class="text-xs text-gray-500 mt-0.5">Comprehensive Trial Balance statements ledger audits generated in real time.</p>
            </div>
            <a href="/accounting/export-compliance-json" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs transition-all shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                Export Full compliance trace (JSON)
            </a>
        </div>

        {{-- Filters & Exporters --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 p-6 shadow-sm">
            <div class="flex items-center gap-2 mb-4">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                <h4 class="text-sm font-extrabold text-gray-900 dark:text-white uppercase tracking-wider">Report Filters & Exporters</h4>
            </div>
            <div class="flex items-end gap-4 flex-wrap">
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Report Period Preset:</label>
                    <select class="py-2.5 px-3 rounded-lg border border-gray-200 dark:border-slate-800 bg-gray-50/55 dark:bg-slate-950 text-xs focus:border-emerald-500 focus:outline-none">
                        <option>All 12 Months (Full Year 2026)</option>
                        <option>Q1 (Jan-Mar 2026)</option>
                        <option>Q2 (Apr-Jun 2026)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">From Date</label>
                    <input type="date" value="2026-01-01" class="py-2.5 px-3 rounded-lg border border-gray-200 dark:border-slate-800 bg-gray-50/55 dark:bg-slate-950 text-xs focus:border-emerald-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">To Date</label>
                    <input type="date" value="2026-12-31" class="py-2.5 px-3 rounded-lg border border-gray-200 dark:border-slate-800 bg-gray-50/55 dark:bg-slate-950 text-xs focus:border-emerald-500 focus:outline-none">
                </div>
                <a href="/accounting/export-csv" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-lg bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs transition-all">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                    Export CSV
                </a>
                <a href="/accounting/export-pdf" target="_blank" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-lg bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs transition-all">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/><polyline points="14 2 14 8 20 8"/></svg>
                    PDF / Print View
                </a>
            </div>
        </div>

        {{-- P&L and Balance Sheet --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Profit & Loss --}}
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 p-6 shadow-sm">
                <h4 class="font-extrabold text-xs text-gray-900 dark:text-white uppercase tracking-wider">Profit & Loss (Income Statement)</h4>
                <p class="text-[11px] text-gray-400 mb-5">For period: 2026-01-01 to 2026-12-31</p>
                <div class="space-y-3 text-xs">
                    <div class="flex justify-between"><span class="text-gray-700 dark:text-gray-300">Crop & Agriculture Revenues</span><span class="font-bold text-gray-900 dark:text-white">৳ {{ number_format($reportIncome) }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-700 dark:text-gray-300">Direct Accounts Receivable Gains</span><span class="font-bold text-gray-900 dark:text-white">৳ 0</span></div>
                    <div class="border-t border-gray-200 dark:border-slate-700 pt-3 flex justify-between"><span class="font-extrabold text-gray-900 dark:text-white">Gross Farm Revenue:</span><span class="font-extrabold text-gray-900 dark:text-white">৳ {{ number_format($reportIncome) }}</span></div>
                    <div class="pt-3"></div>
                    <div class="flex justify-between"><span class="text-gray-700 dark:text-gray-300">Direct Production Cost of Seeds & Fertilizers</span><span class="font-bold text-rose-600">− ৳ {{ number_format($reportExpense) }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-700 dark:text-gray-300">Logistics & Farm Transport Costs</span><span class="font-bold text-rose-600">− ৳ 0</span></div>
                    <div class="border-t border-gray-200 dark:border-slate-700 pt-3 flex justify-between"><span class="font-extrabold text-gray-900 dark:text-white">Total Operating Expenses:</span><span class="font-extrabold text-rose-600">− ৳ {{ number_format($reportExpense) }}</span></div>
                    <div class="border-t-2 border-emerald-500 pt-3 flex justify-between"><span class="font-extrabold text-emerald-700 dark:text-emerald-400">NET PROFIT:</span><span class="font-extrabold text-emerald-700 dark:text-emerald-400">৳ {{ number_format($reportIncome - $reportExpense) }}</span></div>
                </div>
            </div>

            {{-- Balance Sheet --}}
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 p-6 shadow-sm">
                <h4 class="font-extrabold text-xs text-gray-900 dark:text-white uppercase tracking-wider">Current Balance Sheet (As of Today)</h4>
                <p class="text-[11px] text-gray-400 mb-5">Accounting formula: Assets = Liabilities + Equity</p>
                <div class="space-y-3 text-xs">
                    <p class="font-extrabold text-rose-600 uppercase text-[11px] tracking-wider">ASSETS:</p>
                    <div class="flex justify-between"><span class="text-gray-700 dark:text-gray-300">Vault Cash & Equivalents</span><span class="font-bold text-gray-900 dark:text-white">৳ 125,000</span></div>
                    <div class="flex justify-between"><span class="text-gray-700 dark:text-gray-300">Commercial Savings bank balance</span><span class="font-bold text-gray-900 dark:text-white">৳ 480,000</span></div>
                    <div class="flex justify-between"><span class="text-gray-700 dark:text-gray-300">Clients Accounts Receivable (A/R)</span><span class="font-bold text-gray-900 dark:text-white">৳ 232,800</span></div>
                    <div class="border-t border-gray-200 dark:border-slate-700 pt-2 flex justify-between"><span class="font-extrabold text-rose-600">Aggregate Total Assets:</span><span class="font-extrabold text-rose-600">৳ 837,800</span></div>
                    <div class="pt-3"></div>
                    <p class="font-extrabold text-rose-600 uppercase text-[11px] tracking-wider">LIABILITIES & PAYABLES:</p>
                    <div class="flex justify-between"><span class="text-gray-700 dark:text-gray-300">Supplier Payables (Seeds/Fertilizer)</span><span class="font-bold text-gray-900 dark:text-white">৳ 45,000</span></div>
                    <div class="flex justify-between"><span class="text-gray-700 dark:text-gray-300">Short-term Farm Loans</span><span class="font-bold text-gray-900 dark:text-white">৳ 80,000</span></div>
                    <div class="border-t border-gray-200 dark:border-slate-700 pt-2 flex justify-between"><span class="font-extrabold text-rose-600">Total Liabilities:</span><span class="font-extrabold text-rose-600">৳ 125,000</span></div>
                    <div class="pt-3"></div>
                    <p class="font-extrabold text-emerald-600 uppercase text-[11px] tracking-wider">EQUITY:</p>
                    <div class="flex justify-between"><span class="text-gray-700 dark:text-gray-300">Owner's Capital Investment</span><span class="font-bold text-gray-900 dark:text-white">৳ 685,000</span></div>
                    <div class="flex justify-between"><span class="text-gray-700 dark:text-gray-300">Retained Earnings (Current Period)</span><span class="font-bold text-emerald-600">৳ {{ number_format($reportIncome - $reportExpense) }}</span></div>
                    <div class="border-t-2 border-emerald-500 pt-2 flex justify-between"><span class="font-extrabold text-emerald-700 dark:text-emerald-400">TOTAL LIABILITIES & EQUITY SUM:</span><span class="font-extrabold text-emerald-700 dark:text-emerald-400">৳ 837,800</span></div>
                </div>
            </div>
        </div>

        {{-- Accounts Receivable Aging Report --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-dashed border-gray-300 dark:border-slate-700 p-6 shadow-sm">
            <h4 class="font-extrabold text-xs text-gray-900 dark:text-white uppercase tracking-wider">Accounts Receivable Chronology Aging Report</h4>
            <p class="text-[11px] text-gray-400 mb-5">Identifies invoice risk brackets and collection cycles based on due dates.</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="rounded-xl border border-dashed border-gray-300 dark:border-slate-700 p-5 text-center">
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">ACTIVE CURRENT</p>
                    <p class="text-sm font-extrabold text-gray-900 dark:text-white">৳ 232,800</p>
                </div>
                <div class="rounded-xl border border-dashed border-gray-300 dark:border-slate-700 p-5 text-center">
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">1 - 30 DAYS DILATORY</p>
                    <p class="text-sm font-extrabold text-gray-900 dark:text-white">৳ 0</p>
                </div>
                <div class="rounded-xl border border-dashed border-gray-300 dark:border-slate-700 p-5 text-center">
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">31 - 60 DAYS DELINQUENT</p>
                    <p class="text-sm font-extrabold text-gray-900 dark:text-white">৳ 0</p>
                </div>
                <div class="rounded-xl border border-dashed border-gray-300 dark:border-slate-700 p-5 text-center">
                    <p class="text-[10px] font-bold text-rose-600 uppercase tracking-wider mb-2">61+ DAYS PAST DUE RISK</p>
                    <p class="text-sm font-extrabold text-rose-600">৳ 0</p>
                </div>
            </div>
        </div>
    </div>

    @elseif($activeTab === 'chart')
    {{-- CHART OF ACCOUNTS --}}
    @php $accounts = \App\Models\Account::orderBy('code')->get(); @endphp
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        {{-- Create/Edit Account Form --}}
        <div class="lg:col-span-4 bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 p-6 shadow-sm self-start">
            <div class="flex items-center gap-2 mb-1"><span class="text-emerald-600 font-bold">+</span><h3 id="chartFormTitle" class="font-extrabold text-sm text-gray-900 dark:text-white">Create Account Item</h3></div>
            <p class="text-[11px] text-gray-500 mb-5">Register new accounts to the ledger chart with custom debit/credit behavior.</p>
            <form id="chartForm" method="POST" action="/accounting/accounts" class="space-y-4">
                @csrf
                <input type="hidden" id="chartMethod" name="_method" value="POST">
                <div><label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-wider mb-1.5">ACCOUNT CODE / LEDGER ID</label><input type="text" name="code" id="chartCode" placeholder="e.g. 1010, 2040, 5010" required class="block w-full py-2.5 px-3 rounded-lg border border-gray-200 dark:border-slate-800 bg-gray-50/55 dark:bg-slate-950 text-xs focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none"></div>
                <div><label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-wider mb-1.5">ACCOUNT NAME</label><input type="text" name="title" id="chartTitle" placeholder="e.g. Petty Cash, Wholesale Crop Revenue" required class="block w-full py-2.5 px-3 rounded-lg border border-gray-200 dark:border-slate-800 bg-gray-50/55 dark:bg-slate-950 text-xs focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none"></div>
                <div><label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-wider mb-1.5">CLASSIFICATION GROUP</label><select name="class" id="chartClass" required class="block w-full py-2.5 px-3 rounded-lg border border-gray-200 dark:border-slate-800 bg-gray-50/55 dark:bg-slate-950 text-xs focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none"><option value="asset">Asset (সম্পদ)</option><option value="liability">Liability (দায়)</option><option value="equity">Equity (মূলধন)</option><option value="revenue">Revenue (রাজস্ব)</option><option value="expense">Expense (ব্যয়)</option></select></div>
                <div><label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-wider mb-1.5">NORMAL BALANCE TYPE</label><select name="normal_balance" id="chartBalance" required class="block w-full py-2.5 px-3 rounded-lg border border-gray-200 dark:border-slate-800 bg-gray-50/55 dark:bg-slate-950 text-xs focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none"><option value="debit">Debit (Dr)</option><option value="credit">Credit (Cr)</option></select></div>
                <div><label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-wider mb-1.5">DESCRIPTION (OPTIONAL)</label><input type="text" name="description" id="chartDesc" placeholder="Brief purpose of this sector ledger..." class="block w-full py-2.5 px-3 rounded-lg border border-gray-200 dark:border-slate-800 bg-gray-50/55 dark:bg-slate-950 text-xs focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none"></div>
                <div class="flex gap-2">
                    <button type="submit" id="chartSubmitBtn" class="flex-1 py-2.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition-all">✓ Register Account</button>
                    <button type="button" id="chartCancelBtn" onclick="resetChartForm()" class="hidden px-4 py-2.5 rounded-lg border border-gray-200 dark:border-slate-700 text-xs font-bold text-gray-600 hover:bg-gray-50 transition-all">Cancel</button>
                </div>
            </form>
        </div>

        {{-- Chart Table --}}
        <div class="lg:col-span-8 bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-5">
                <div class="flex items-center gap-2"><span class="text-emerald-600">🏦</span><h3 class="font-extrabold text-sm text-gray-900 dark:text-white">Chart of Accounts Ledger Suites</h3></div>
                <div class="flex items-center gap-3">
                    <a href="/accounting/export-csv" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-gray-200 dark:border-slate-700 text-[11px] font-bold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-800 transition-all no-underline"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg> Export CSV</a>
                    <span class="text-[11px] font-bold text-gray-400">{{ count($accounts) }} Total Accounts Registered</span>
                </div>
            </div>
            <table class="w-full text-left text-xs border-collapse">
                <thead><tr class="border-b border-gray-100 dark:border-slate-800 text-gray-500 uppercase tracking-wider font-semibold"><th class="py-3 px-4">Code</th><th class="py-3 px-4">Account Name</th><th class="py-3 px-4">Classification</th><th class="py-3 px-4">Balance Nature</th><th class="py-3 px-4 text-right">Actions</th></tr></thead>
                <tbody class="divide-y divide-gray-50 dark:divide-slate-800/50 text-gray-700 dark:text-gray-300">
                    @foreach($accounts as $account)
                    @php $classColors = ['asset'=>'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400','liability'=>'bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-400','equity'=>'bg-blue-50 text-blue-700 dark:bg-blue-950/40 dark:text-blue-400','revenue'=>'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400','expense'=>'bg-rose-50 text-rose-700 dark:bg-rose-950/40 dark:text-rose-400']; @endphp
                    <tr class="hover:bg-gray-50/40 dark:hover:bg-slate-800/30 transition-colors">
                        <td class="py-3.5 px-4 font-bold text-emerald-700 dark:text-emerald-400">{{ $account->code }}</td>
                        <td class="py-3.5 px-4"><p class="font-bold text-gray-900 dark:text-white">{{ $account->title }}</p><p class="text-[11px] text-gray-400">{{ $account->description }}</p></td>
                        <td class="py-3.5 px-4"><span class="inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $classColors[$account->class] ?? '' }}">{{ strtoupper($account->class) }}</span></td>
                        <td class="py-3.5 px-4 text-xs">{{ ucfirst($account->normal_balance) }}<br><span class="text-gray-400">({{ $account->normal_balance === 'debit' ? 'Dr' : 'Cr' }})</span></td>
                        <td class="py-3.5 px-4 text-right"><div class="flex items-center justify-end gap-2"><button onclick="editChartAccount({{ $account->id }}, '{{ $account->code }}', '{{ addslashes($account->title) }}', '{{ $account->class }}', '{{ $account->normal_balance }}', '{{ addslashes($account->description ?? '') }}')" class="text-gray-400 hover:text-emerald-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z"/></svg></button><form method="POST" action="/accounting/accounts/{{ $account->id }}" onsubmit="return confirm('Delete?')" class="inline">@csrf @method('DELETE')<button class="text-gray-400 hover:text-rose-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button></form></div></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
    function editChartAccount(id, code, title, cls, balance, desc) {
        document.getElementById('chartFormTitle').textContent = 'Edit Account Item';
        document.getElementById('chartSubmitBtn').textContent = '✓ Save Changes';
        document.getElementById('chartCancelBtn').classList.remove('hidden');
        document.getElementById('chartForm').action = '/accounting/accounts/' + id;
        document.getElementById('chartMethod').value = 'PUT';
        document.getElementById('chartCode').value = code;
        document.getElementById('chartTitle').value = title;
        document.getElementById('chartClass').value = cls;
        document.getElementById('chartBalance').value = balance;
        document.getElementById('chartDesc').value = desc;
        // Scroll to form
        document.getElementById('chartForm').scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    function resetChartForm() {
        document.getElementById('chartFormTitle').textContent = 'Create Account Item';
        document.getElementById('chartSubmitBtn').textContent = '✓ Register Account';
        document.getElementById('chartCancelBtn').classList.add('hidden');
        document.getElementById('chartForm').action = '/accounting/accounts';
        document.getElementById('chartMethod').value = 'POST';
        document.getElementById('chartCode').value = '';
        document.getElementById('chartTitle').value = '';
        document.getElementById('chartClass').value = 'asset';
        document.getElementById('chartBalance').value = 'debit';
        document.getElementById('chartDesc').value = '';
    }
    </script>
    @endif
</div>
@endsection
