<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use App\Models\Setting;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        View::composer('layouts.app', function ($view) {
            $netBalance = 0;
            if (Auth::check()) {
                $income = Transaction::where('type', 'income')->sum('amount');
                $expense = Transaction::where('type', 'expense')->sum('amount');
                $netBalance = $income - $expense;
            }
            $appTitleEn = Setting::get('app_title_en', 'Village Agro Farm');
            $appTitleBn = Setting::get('app_title_bn', 'ভিলেজ এগ্রো ফার্ম');
            $interfaceSize = Setting::get('interface_size', 'medium');
            $appLogo = Setting::get('app_logo', null);

            $view->with(compact('netBalance', 'appTitleEn', 'appTitleBn', 'interfaceSize', 'appLogo'));
        });
    }
}
