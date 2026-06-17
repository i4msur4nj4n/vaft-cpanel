<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\AccountingController;
use App\Http\Controllers\ControlPanelController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\SecurityLogController;

Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/lang/{locale}', function($locale) { if(in_array($locale, ['en', 'bn'])) { session(['locale' => $locale]); } return back(); })->name('lang.switch');
Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::get('/transactions/create', [TransactionController::class, 'create']);
    Route::post('/transactions', [TransactionController::class, 'store']);
    Route::get('/transactions/history', [TransactionController::class, 'history']);
    Route::get('/transactions/analytics', [TransactionController::class, 'analytics']);
    Route::get('/transactions/analytics/export-csv', [TransactionController::class, 'exportAnalyticsCsv']);

    Route::get('/projects', [ProjectController::class, 'index']);
    Route::post('/projects', [ProjectController::class, 'store']);
    Route::put('/projects/{project}', [ProjectController::class, 'update']);
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy']);

    Route::get('/accounting', [AccountingController::class, 'index']);
    Route::post('/accounting/invoices', [AccountingController::class, 'storeInvoice']);
    Route::patch('/accounting/invoices/{invoice}/toggle', [AccountingController::class, 'toggleStatus']);
    Route::get('/accounting/invoices/{invoice}/edit', [AccountingController::class, 'editInvoice']);
    Route::put('/accounting/invoices/{invoice}', [AccountingController::class, 'updateInvoice']);
    Route::delete('/accounting/invoices/{invoice}', [AccountingController::class, 'destroyInvoice']);
    Route::post('/accounting/accounts', [AccountingController::class, 'storeAccount']);
    Route::delete('/accounting/accounts/{account}', [AccountingController::class, 'destroyAccount']);
    Route::put('/accounting/accounts/{account}', [AccountingController::class, 'updateAccount']);
    Route::get('/accounting/export-csv', [AccountingController::class, 'exportCsv']);
    Route::get('/accounting/export-compliance-json', [AccountingController::class, 'exportComplianceJson']);
    Route::get('/accounting/export-pdf', [AccountingController::class, 'exportPdf']);
    Route::get('/accounting/export-bank-csv', [AccountingController::class, 'exportBankCsv']);
    Route::get('/accounting/export-invoices-csv', [AccountingController::class, 'exportInvoicesCsv']);
    Route::get('/accounting/export-invoices-csv', [AccountingController::class, 'exportInvoicesCsv']);

    // Bank entries CRUD
    Route::post('/accounting/bank-entry', [AccountingController::class, 'storeBankEntry']);
    Route::put('/accounting/bank-entry/{bankEntry}', [AccountingController::class, 'updateBankEntry']);
    Route::delete('/accounting/bank-entry/{bankEntry}', [AccountingController::class, 'destroyBankEntry']);
    Route::get('/accounting/bank-entry/{bankEntry}/find-match', [AccountingController::class, 'findMatchForBank']);
    Route::post('/accounting/bank-entry/{bankEntry}/reconcile', [AccountingController::class, 'reconcileBankEntry']);

    // Journal entries CRUD
    Route::post('/accounting/journal-entry', [AccountingController::class, 'storeJournalEntry']);
    Route::delete('/accounting/journal-entry/{journalEntry}', [AccountingController::class, 'destroyJournalEntry']);
    Route::get('/accounting/journal-entry/{journalEntry}/edit', [AccountingController::class, 'editJournalEntry']);
    Route::put('/accounting/journal-entry/{journalEntry}', [AccountingController::class, 'updateJournalEntry']);

    // Vendor Bills (A/P)
    Route::get('/accounting/vendor-bills', [AccountingController::class, 'vendorBillsJson']);
    Route::post('/accounting/vendor-bills', [AccountingController::class, 'storeVendorBill']);
    Route::get('/accounting/vendor-bills/{vendorBill}/edit', [AccountingController::class, 'editVendorBill']);
    Route::put('/accounting/vendor-bills/{vendorBill}', [AccountingController::class, 'updateVendorBill']);
    Route::patch('/accounting/vendor-bills/{vendorBill}/toggle', [AccountingController::class, 'toggleVendorBill']);
    Route::delete('/accounting/vendor-bills/{vendorBill}', [AccountingController::class, 'destroyVendorBill']);
    Route::get('/accounting/export-vendor-bills-csv', [AccountingController::class, 'exportVendorBillsCsv']);

    Route::get('/control-panel', [ControlPanelController::class, 'index']);
    Route::post('/control-panel/categories', [ControlPanelController::class, 'storeCategory']);
    Route::put('/control-panel/categories/{category}', [ControlPanelController::class, 'updateCategory']);
    Route::delete('/control-panel/categories/{category}', [ControlPanelController::class, 'destroyCategory']);
    Route::post('/control-panel/projects', [ControlPanelController::class, 'storeProject']);
    Route::delete('/control-panel/projects/{project}', [ControlPanelController::class, 'destroyProject']);
    Route::post('/control-panel/accounts', [ControlPanelController::class, 'storeAccount']);
    Route::delete('/control-panel/accounts/{account}', [ControlPanelController::class, 'destroyAccount']);
    Route::post('/control-panel/branding', [ControlPanelController::class, 'saveBranding']);

    Route::middleware('admin')->group(function () {
        Route::get('/admin-panel', [AdminController::class, 'index']);
        Route::patch('/admin-panel/users/{user}/toggle-role', [AdminController::class, 'toggleRole']);
        Route::delete('/admin-panel/users/{user}', [AdminController::class, 'destroyUser']);
        Route::put('/admin-panel/users/{user}', [AdminController::class, 'updateUser']);
        Route::get('/admin-panel/users/{user}/permissions', [AdminController::class, 'getPermissions']);
        Route::post('/admin-panel/users/{user}/permissions', [AdminController::class, 'savePermissions']);
        Route::get('/admin-panel/subscriptions', [SubscriptionController::class, 'index']);
        Route::post('/admin-panel/subscriptions/plans', [SubscriptionController::class, 'storePlan']);
        Route::put('/admin-panel/subscriptions/plans/{plan}', [SubscriptionController::class, 'updatePlan']);
        Route::delete('/admin-panel/subscriptions/plans/{plan}', [SubscriptionController::class, 'destroyPlan']);
        Route::post('/admin-panel/subscriptions/gateways', [SubscriptionController::class, 'storeGateway']);
        Route::put('/admin-panel/subscriptions/gateways/{gateway}', [SubscriptionController::class, 'updateGateway']);
        Route::delete('/admin-panel/subscriptions/gateways/{gateway}', [SubscriptionController::class, 'destroyGateway']);
        Route::put('/admin-panel/subscriptions/users/{user}', [SubscriptionController::class, 'updateUserSubscription']);
        Route::get('/admin-panel/security-log', [SecurityLogController::class, 'index']);
        Route::get('/admin-panel/security-log/export-csv', [SecurityLogController::class, 'exportCsv']);
    });
});
