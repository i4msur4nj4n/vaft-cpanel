<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\AuditLog;
use App\Models\Category;
use App\Models\Project;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function create()
    {
        $categories = Category::all();
        $projects = Project::where('status', 'active')->get();
        return view('transactions.create', compact('categories', 'projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:income,expense',
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'category_id' => 'required|exists:categories,id',
            'project_id' => 'nullable|exists:projects,id',
            'notes' => 'nullable|string|max:500',
        ]);
        $validated['user_id'] = auth()->id();
        Transaction::create($validated);
        $cat = Category::find($validated["category_id"]); AuditLog::record("CREATE", "Created tx of " . $validated["amount"] . " BDT for " . ($cat->name_en ?? "Unknown") . " | " . ($cat->name_bn ?? ""));
        return redirect('/dashboard')->with('success', 'Transaction recorded successfully!');
    }

    public function history(Request $request)
    {
        $query = Transaction::with(['category', 'user', 'project']);

        // Apply filters
        if ($request->filled('from_date')) {
            $query->where('date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->where('date', '<=', $request->to_date);
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('notes', 'LIKE', '%' . $keyword . '%')
                  ->orWhereHas('category', function($cq) use ($keyword) {
                      $cq->where('name_en', 'LIKE', '%' . $keyword . '%')
                         ->orWhere('name_bn', 'LIKE', '%' . $keyword . '%');
                  })
                  ->orWhereHas('project', function($pq) use ($keyword) {
                      $pq->where('name', 'LIKE', '%' . $keyword . '%');
                  });
            });
        }

        $transactions = $query->orderBy('date', 'desc')->get();
        $categories = Category::all();

        return view('transactions.history', compact('transactions', 'categories'));
    }

    public function analytics(Request $request)
    {
        $query = Transaction::with(['category', 'user', 'project']);

        // Apply filters
        if ($request->filled('from_date')) {
            $query->where('date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->where('date', '<=', $request->to_date);
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('notes', 'LIKE', '%' . $keyword . '%')
                  ->orWhereHas('category', function($cq) use ($keyword) {
                      $cq->where('name_en', 'LIKE', '%' . $keyword . '%')
                         ->orWhere('name_bn', 'LIKE', '%' . $keyword . '%');
                  })
                  ->orWhereHas('project', function($pq) use ($keyword) {
                      $pq->where('name', 'LIKE', '%' . $keyword . '%');
                  });
            });
        }

        $transactions = $query->orderBy('date', 'desc')->get();
        $totalIncome = $transactions->where('type', 'income')->sum('amount');
        $totalExpense = $transactions->where('type', 'expense')->sum('amount');
        $netBalance = $totalIncome - $totalExpense;
        $categories = Category::all();
        $projects = Project::all();

        return view('transactions.analytics', compact(
            'transactions', 'totalIncome', 'totalExpense', 'netBalance', 'categories', 'projects'
        ));
    }

    public function exportAnalyticsCsv(Request $request)
    {
        $query = Transaction::with(['category', 'user', 'project']);

        if ($request->filled('from_date')) {
            $query->where('date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->where('date', '<=', $request->to_date);
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('notes', 'LIKE', '%' . $keyword . '%')
                  ->orWhereHas('category', function($cq) use ($keyword) {
                      $cq->where('name_en', 'LIKE', '%' . $keyword . '%')
                         ->orWhere('name_bn', 'LIKE', '%' . $keyword . '%');
                  })
                  ->orWhereHas('project', function($pq) use ($keyword) {
                      $pq->where('name', 'LIKE', '%' . $keyword . '%');
                  });
            });
        }

        $transactions = $query->orderBy('date', 'desc')->get();
        $filename = 'analytics-export-' . date('Y-m-d') . '.csv';
        $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => "attachment; filename=\"$filename\""];

        $callback = function() use ($transactions) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Date', 'Category', 'Project', 'Type', 'Notes', 'User', 'Amount (BDT)']);
            foreach ($transactions as $txn) {
                fputcsv($file, [
                    $txn->date->format('d-M-Y'),
                    $txn->category->name_en ?? '',
                    $txn->project->name ?? '',
                    ucfirst($txn->type),
                    $txn->notes,
                    $txn->user->name ?? '',
                    ($txn->type === 'expense' ? '-' : '') . $txn->amount,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
