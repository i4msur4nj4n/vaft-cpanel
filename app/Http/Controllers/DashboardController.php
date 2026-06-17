<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalIncome = Transaction::where('type', 'income')->sum('amount');
        $totalExpense = Transaction::where('type', 'expense')->sum('amount');
        $netBalance = $totalIncome - $totalExpense;

        $categories = Category::withSum(['transactions as income_total' => fn($q) => $q->where('type', 'income')], 'amount')
            ->withSum(['transactions as expense_total' => fn($q) => $q->where('type', 'expense')], 'amount')
            ->get();

        $recentTransactions = Transaction::with(['category', 'user'])
            ->orderBy('date', 'desc')
            ->limit(10)
            ->get();

        $categoryData = $categories->map(function ($cat) use ($totalIncome, $totalExpense) {
            $total = ($cat->income_total ?? 0) + ($cat->expense_total ?? 0);
            $grandTotal = $totalIncome + $totalExpense;
            $isIncome = ($cat->income_total ?? 0) > ($cat->expense_total ?? 0);
            return [
                'name' => $cat->display_name,
                'name_en' => $cat->name_en,
                'name_bn' => $cat->name_bn,
                'icon' => $cat->icon,
                'amount' => $total,
                'percentage' => $grandTotal > 0 ? round(($total / $grandTotal) * 100) : 0,
                'is_income' => $isIncome,
            ];
        })->filter(fn($c) => $c['amount'] > 0)->values();

        return view('dashboard.index', compact(
            'totalIncome', 'totalExpense', 'netBalance',
            'categoryData', 'recentTransactions'
        ));
    }
}
