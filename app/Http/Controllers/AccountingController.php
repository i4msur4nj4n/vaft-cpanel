<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Category;
use App\Models\Project;
use App\Models\Account;
use App\Models\BankEntry;
use App\Models\JournalEntry;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AccountingController extends Controller
{
    public function index()
    {
        $invoices = \App\Models\Invoice::orderBy('created_at', 'desc')->get();
        $bankEntries = BankEntry::orderBy('booking_date', 'desc')->get();
        $journalEntries = JournalEntry::orderBy('booking_date', 'desc')->get();
        $vendorBills = \App\Models\VendorBill::orderBy('created_at', 'desc')->get();
        return view('accounting.index', compact('invoices', 'bankEntries', 'journalEntries', 'vendorBills'));
    }

    public function storeInvoice(Request $request)
    {
        $validated = $request->validate([
            'invoice_number' => 'required|string|unique:invoices',
            'client_name' => 'required|string|max:255',
            'client_email' => 'nullable|email',
            'issue_date' => 'required|date',
            'due_date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string|max:500',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        $totalAmount = collect($request->items)->sum(fn($item) => $item['quantity'] * $item['price']);

        $invoice = \App\Models\Invoice::create([
            'invoice_number' => $validated['invoice_number'],
            'client_name' => $validated['client_name'],
            'client_email' => $validated['client_email'] ?? null,
            'issue_date' => $validated['issue_date'],
            'due_date' => $validated['due_date'],
            'amount' => $totalAmount,
            'notes' => $validated['notes'] ?? null,
        ]);

        foreach ($request->items as $item) {
            $invoice->items()->create([
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        AuditLog::record('CREATE', 'Created invoice ' . $invoice->invoice_number . ' for ' . $validated['client_name']);
        return redirect('/accounting?tab=invoicing')->with('success', 'Invoice generated successfully!');
    }

    public function toggleStatus(\App\Models\Invoice $invoice)
    {
        $invoice->update(['status' => $invoice->status === 'paid' ? 'unpaid' : 'paid']);
        AuditLog::record('UPDATE', 'Toggled invoice ' . $invoice->invoice_number . ' status to ' . $invoice->status);
        return redirect('/accounting')->with('success', 'Invoice status updated!');
    }

    public function destroyInvoice(\App\Models\Invoice $invoice)
    {
        $invoice->delete();
        AuditLog::record('DELETE', 'Deleted invoice ' . $invoice->invoice_number);
        return redirect('/accounting')->with('success', 'Invoice deleted!');
    }

    public function storeAccount(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:20',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'class' => 'required|in:asset,liability,equity,revenue,expense',
            'normal_balance' => 'required|in:debit,credit',
        ]);
        Account::create($validated);
        AuditLog::record('CREATE', 'Created chart account: ' . $validated['code'] . ' - ' . $validated['title']);
        return redirect('/accounting?tab=chart')->with('success', 'Account created!');
    }

    public function destroyAccount(Account $account)
    {
        $account->delete();
        AuditLog::record('DELETE', 'Deleted chart account: ' . $account->code . ' - ' . $account->title);
        return redirect('/accounting?tab=chart')->with('success', 'Account deleted!');
    }

    // === BANK ENTRIES ===
    public function storeBankEntry(Request $request)
    {
        $validated = $request->validate([
            'booking_date' => 'required|date',
            'description' => 'required|string|max:500',
            'amount' => 'required|numeric|min:0.01',
            'flow_type' => 'required|in:deposit,withdrawal',
        ]);
        $validated['status'] = 'unmatched';
        BankEntry::create($validated);
        AuditLog::record('CREATE', 'Added bank entry: ' . $validated['description'] . ' (' . $validated['amount'] . ' BDT)');
        return redirect('/accounting?tab=bank')->with('success', 'Bank entry added!');
    }

    public function updateBankEntry(Request $request, BankEntry $bankEntry)
    {
        $validated = $request->validate([
            'booking_date' => 'required|date',
            'description' => 'required|string|max:500',
            'amount' => 'required|numeric|min:0.01',
            'flow_type' => 'required|in:deposit,withdrawal',
        ]);
        $bankEntry->update($validated);
        AuditLog::record('UPDATE', 'Updated bank entry: ' . $bankEntry->description);
        return redirect('/accounting?tab=bank')->with('success', 'Bank entry updated!');
    }

    public function destroyBankEntry(BankEntry $bankEntry)
    {
        $bankEntry->delete();
        AuditLog::record('DELETE', 'Deleted bank entry: ' . $bankEntry->description);
        return redirect('/accounting?tab=bank')->with('success', 'Bank entry deleted!');
    }

    // === JOURNAL ENTRIES ===
    public function storeJournalEntry(Request $request)
    {
        $validated = $request->validate([
            'booking_date' => 'required|date',
            'reference' => 'nullable|string|max:100',
            'memo' => 'required|string|max:500',
            'debit_account' => 'required|string|max:50',
            'debit_amount' => 'required|numeric|min:0.01',
            'credit_account' => 'required|string|max:50',
            'credit_amount' => 'required|numeric|min:0.01',
        ]);
        JournalEntry::create($validated);
        AuditLog::record('CREATE', 'Posted journal entry: ' . $validated['description']);
        return redirect('/accounting?tab=ledger')->with('success', 'Journal entry posted!');
    }

    public function destroyJournalEntry(JournalEntry $journalEntry)
    {
        $journalEntry->delete();
        AuditLog::record('DELETE', 'Deleted journal entry #' . $journalEntry->id);
        return redirect('/accounting?tab=ledger')->with('success', 'Journal entry deleted!');
    }

    // === EXPORTS ===
    public function exportCsv()
    {
        $accounts = Account::orderBy('code')->get();
        $filename = 'chart-of-accounts-' . date('Y-m-d') . '.csv';
        $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => "attachment; filename=\"$filename\""];
        $callback = function() use ($accounts) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Code', 'Account Name', 'Description', 'Classification', 'Normal Balance']);
            foreach ($accounts as $a) { fputcsv($file, [$a->code, $a->title, $a->description, ucfirst($a->class), ucfirst($a->normal_balance)]); }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function exportBankCsv()
    {
        $entries = BankEntry::orderBy('booking_date', 'desc')->get();
        $filename = 'bank-reconciliation-' . date('Y-m-d') . '.csv';
        $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => "attachment; filename=\"$filename\""];
        $callback = function() use ($entries) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Date', 'Description', 'Flow Type', 'Amount (BDT)', 'Status', 'Reference']);
            foreach ($entries as $e) {
                $amt = $e->flow_type === 'withdrawal' ? -$e->amount : $e->amount;
                fputcsv($file, [$e->booking_date->format('d-M-Y'), $e->description, ucfirst($e->flow_type), $amt, strtoupper($e->status), $e->reference ?? 'N/A']);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function exportComplianceJson()
    {
        $transactions = Transaction::with(['category', 'user', 'project'])->orderBy('date', 'desc')->get();
        $accounts = Account::orderBy('code')->get();
        $invoices = \App\Models\Invoice::all();
        $journalEntries = JournalEntry::all();
        $bankEntries = BankEntry::all();

        $totalIncome = $transactions->where('type', 'income')->sum('amount');
        $totalExpense = $transactions->where('type', 'expense')->sum('amount');

        $report = [
            'report_type' => 'Full Compliance Trace',
            'generated_at' => now()->toISOString(),
            'period' => ['from' => '2026-01-01', 'to' => date('Y-m-d')],
            'summary' => ['total_income' => $totalIncome, 'total_expense' => $totalExpense, 'net_profit' => $totalIncome - $totalExpense],
            'chart_of_accounts' => $accounts->toArray(),
            'journal_entries' => $journalEntries->toArray(),
            'bank_entries' => $bankEntries->toArray(),
            'transactions' => $transactions->map(fn($t) => ['date' => $t->date->format('Y-m-d'), 'type' => $t->type, 'amount' => $t->amount, 'category' => $t->category->name_en ?? null, 'notes' => $t->notes])->toArray(),
            'invoices' => $invoices->toArray(),
        ];

        return response()->json($report)->header('Content-Disposition', 'attachment; filename="compliance-trace-' . date('Y-m-d') . '.json"');
    }

    public function exportPdf()
    {
        $transactions = Transaction::with(['category'])->orderBy('date', 'desc')->get();
        $totalIncome = $transactions->where('type', 'income')->sum('amount');
        $totalExpense = $transactions->where('type', 'expense')->sum('amount');
        $html = '<html><head><title>Auditor Report</title><style>body{font-family:sans-serif;padding:40px;font-size:12px}h1{color:#059669}table{width:100%;border-collapse:collapse;margin-top:20px}th,td{border:1px solid #e5e7eb;padding:8px;text-align:left}th{background:#f9fafb;font-weight:bold;text-transform:uppercase;font-size:10px}.total{font-weight:bold;background:#ecfdf5}</style></head><body>';
        $html .= '<h1>Village Agro Farm - Compliance Report</h1><p>Generated: ' . date('d M Y H:i') . '</p>';
        $html .= '<h2>P&L</h2><table><tr><th>Metric</th><th>Amount</th></tr><tr><td>Revenue</td><td>৳ ' . number_format($totalIncome) . '</td></tr><tr><td>Expenses</td><td>৳ ' . number_format($totalExpense) . '</td></tr><tr class="total"><td>Net Profit</td><td>৳ ' . number_format($totalIncome - $totalExpense) . '</td></tr></table>';
        $html .= '</body></html>';
        return response($html)->header('Content-Type', 'text/html');
    }

    public function exportInvoicesCsv()
    {
        $invoices = \App\Models\Invoice::orderBy('created_at', 'desc')->get();
        $filename = 'invoices-' . date('Y-m-d') . '.csv';
        $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => "attachment; filename=\"$filename\""];
        $callback = function() use ($invoices) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Invoice Number', 'Client Name', 'Client Email', 'Issue Date', 'Due Date', 'Amount (BDT)', 'Status']);
            foreach ($invoices as $inv) {
                fputcsv($file, [$inv->invoice_number, $inv->client_name, $inv->client_email ?? 'N/A', $inv->issue_date->format('d-M-Y'), $inv->due_date->format('d-M-Y'), $inv->amount, strtoupper($inv->status)]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function editInvoice(\App\Models\Invoice $invoice)
    {
        $invoice->load('items');
        return response()->json($invoice);
    }

    public function updateInvoice(Request $request, \App\Models\Invoice $invoice)
    {
        $validated = $request->validate([
            'invoice_number' => 'required|string|unique:invoices,invoice_number,' . $invoice->id,
            'client_name' => 'required|string|max:255',
            'client_email' => 'nullable|email',
            'issue_date' => 'required|date',
            'due_date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string|max:500',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        $totalAmount = collect($request->items)->sum(fn($item) => $item['quantity'] * $item['price']);

        $invoice->update([
            'invoice_number' => $validated['invoice_number'],
            'client_name' => $validated['client_name'],
            'client_email' => $validated['client_email'] ?? null,
            'issue_date' => $validated['issue_date'],
            'due_date' => $validated['due_date'],
            'amount' => $totalAmount,
            'notes' => $validated['notes'] ?? null,
        ]);

        $invoice->items()->delete();
        foreach ($request->items as $item) {
            $invoice->items()->create([
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        AuditLog::record('UPDATE', 'Updated invoice ' . $invoice->invoice_number);
        return redirect('/accounting?tab=invoicing')->with('success', 'Invoice updated!');
    }

    // === VENDOR BILLS (A/P) ===
    public function vendorBillsJson(\App\Models\VendorBill $vendorBill = null)
    {
        return response()->json(\App\Models\VendorBill::orderBy('created_at', 'desc')->get());
    }

    public function storeVendorBill(Request $request)
    {
        $validated = $request->validate([
            'bill_code' => 'required|string|unique:vendor_bills',
            'vendor_name' => 'required|string|max:255',
            'notes' => 'nullable|string|max:500',
            'bill_date' => 'required|date',
            'due_date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
        ]);
        \App\Models\VendorBill::create($validated);
        AuditLog::record('CREATE', 'Registered vendor bill: ' . $validated['bill_number'] . ' from ' . $validated['vendor_name']);
        return redirect('/accounting?tab=payable')->with('success', 'Vendor bill registered!');
    }

    public function editVendorBill(\App\Models\VendorBill $vendorBill)
    {
        return response()->json($vendorBill);
    }

    public function updateVendorBill(Request $request, \App\Models\VendorBill $vendorBill)
    {
        $validated = $request->validate([
            'bill_code' => 'required|string|unique:vendor_bills,bill_code,' . $vendorBill->id,
            'vendor_name' => 'required|string|max:255',
            'notes' => 'nullable|string|max:500',
            'bill_date' => 'required|date',
            'due_date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
        ]);
        $vendorBill->update($validated);
        AuditLog::record('UPDATE', 'Updated vendor bill: ' . $vendorBill->bill_number);
        return redirect('/accounting?tab=payable')->with('success', 'Vendor bill updated!');
    }

    public function toggleVendorBill(\App\Models\VendorBill $vendorBill)
    {
        $vendorBill->update(['status' => $vendorBill->status === 'paid' ? 'outstanding' : 'paid']);
        AuditLog::record('UPDATE', 'Settled Bill ' . $vendorBill->bill_number . ' status to ' . $vendorBill->status);
        return redirect('/accounting?tab=payable')->with('success', 'Bill status updated!');
    }

    public function destroyVendorBill(\App\Models\VendorBill $vendorBill)
    {
        $vendorBill->delete();
        AuditLog::record('DELETE', 'Deleted vendor bill: ' . $vendorBill->bill_number);
        return redirect('/accounting?tab=payable')->with('success', 'Vendor bill deleted!');
    }

    public function exportVendorBillsCsv()
    {
        $bills = \App\Models\VendorBill::orderBy('bill_date', 'desc')->get();
        $filename = 'vendor-bills-' . date('Y-m-d') . '.csv';
        $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => "attachment; filename=\"$filename\""];
        $callback = function() use ($bills) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Bill Code', 'Vendor Name', 'Notes', 'Bill Date', 'Due Date', 'Amount (BDT)', 'Status']);
            foreach ($bills as $b) {
                fputcsv($file, [$b->bill_code, $b->vendor_name, $b->notes ?? '', $b->bill_date->format('d-M-Y'), $b->due_date->format('d-M-Y'), $b->amount, strtoupper($b->status)]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function editJournalEntry(JournalEntry $journalEntry)
    {
        return response()->json($journalEntry);
    }

    public function updateJournalEntry(Request $request, JournalEntry $journalEntry)
    {
        $validated = $request->validate([
            'booking_date' => 'required|date',
            'reference' => 'nullable|string|max:100',
            'memo' => 'required|string|max:500',
            'debit_account' => 'required|string|max:50',
            'debit_amount' => 'required|numeric|min:0',
            'credit_account' => 'required|string|max:50',
            'credit_amount' => 'required|numeric|min:0',
        ]);
        $journalEntry->update($validated);
        AuditLog::record('UPDATE', 'Updated journal entry #' . $journalEntry->id);
        return redirect('/accounting?tab=ledger')->with('success', 'Journal entry updated!');
    }

    public function findMatchForBank(BankEntry $bankEntry)
    {
        // Return unpaid invoices and journal entries for matching
        $invoices = \App\Models\Invoice::where('status', 'unpaid')->orderBy('due_date', 'desc')->get();
        $journalEntries = JournalEntry::orderBy('booking_date', 'desc')->limit(10)->get();

        return response()->json([
            'bank_entry' => $bankEntry,
            'invoices' => $invoices,
            'journal_entries' => $journalEntries,
        ]);
    }

    public function reconcileBankEntry(Request $request, BankEntry $bankEntry)
    {
        $validated = $request->validate([
            'matched_type' => 'required|in:invoice,journal',
            'matched_id' => 'required|integer',
        ]);

        $reference = null;
        if ($validated['matched_type'] === 'invoice') {
            $invoice = \App\Models\Invoice::findOrFail($validated['matched_id']);
            $reference = $invoice->invoice_number;
        } else {
            $je = JournalEntry::findOrFail($validated['matched_id']);
            $reference = $je->reference ?? 'JE-' . $je->id;
        }

        $bankEntry->update([
            'status' => 'reconciled',
            'matched_type' => $validated['matched_type'],
            'matched_id' => $validated['matched_id'],
            'reference' => $reference,
        ]);

        AuditLog::record('UPDATE', 'Successfully matched bank log: [' . $bankEntry->description . '] with voucher ' . ($bankEntry->matched_voucher ?? 'N/A'));
        return redirect('/accounting?tab=bank')->with('success', 'Bank entry reconciled!');
    }

    public function updateAccount(Request $request, Account $account)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:20',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'class' => 'required|in:asset,liability,equity,revenue,expense',
            'normal_balance' => 'required|in:debit,credit',
        ]);
        $account->update($validated);
        AuditLog::record('UPDATE', 'Updated chart account: ' . $account->code . ' - ' . $account->title);
        return redirect('/accounting?tab=chart')->with('success', 'Account updated!');
    }
}
