<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPermission extends Model
{
    protected $fillable = ['user_id', 'module', 'can_view', 'can_create', 'can_edit', 'can_delete'];

    protected $casts = [
        'can_view' => 'boolean',
        'can_create' => 'boolean',
        'can_edit' => 'boolean',
        'can_delete' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function modules(): array
    {
        return [
            ['key' => 'dashboard', 'name' => 'Dashboard & Analytics [MAIN]', 'icon' => '📊', 'desc' => 'General statistics reports, flow visualizers, summary sheets', 'level' => 'main', 'perms' => ['view']],
            ['key' => 'transactions', 'name' => 'Ledger Transactions [MAIN]', 'icon' => '🎆', 'desc' => 'Add, modify or delete standard income and expense listings', 'level' => 'main', 'perms' => ['view', 'create', 'edit', 'delete']],
            ['key' => 'transactions.income', 'name' => 'Sub: Income Entries', 'icon' => '', 'desc' => 'Log project harvest sales, standard farm receipts', 'level' => 'sub', 'perms' => ['view', 'create', 'edit', 'delete']],
            ['key' => 'transactions.expense', 'name' => 'Sub: Expense Entries', 'icon' => '', 'desc' => 'Record utility billing, equipment leasing, seed expenditure', 'level' => 'sub', 'perms' => ['view', 'create', 'edit', 'delete']],
            ['key' => 'transactions.categories', 'name' => 'Sub: Category Management', 'icon' => '', 'desc' => 'Add or remove asset labels, cost classifications', 'level' => 'sub', 'perms' => ['view', 'create', 'edit', 'delete']],
            ['key' => 'projects', 'name' => 'Investment Projects [MAIN]', 'icon' => '🐄', 'desc' => 'Track agricultural investment schemes, crops under management', 'level' => 'main', 'perms' => ['view', 'create', 'edit', 'delete']],
            ['key' => 'projects.settings', 'name' => 'Sub: Project Settings', 'icon' => '', 'desc' => 'Define project metadata, target goals, launch new crop streams', 'level' => 'sub', 'perms' => ['view', 'create', 'edit', 'delete']],
            ['key' => 'projects.investments', 'name' => 'Sub: Investments Log', 'icon' => '', 'desc' => 'View transactional funding history and venture shareholder stakes', 'level' => 'sub', 'perms' => ['view', 'create', 'edit', 'delete']],
            ['key' => 'analytics', 'name' => 'Category & Category Analytics [MAIN]', 'icon' => '📈', 'desc' => 'View robust charts, percentages and data sheets', 'level' => 'main', 'perms' => ['view']],
            ['key' => 'audit_logs', 'name' => 'Process Audit logs [MAIN]', 'icon' => '🗂️', 'desc' => 'Security log feeds, data entry stream monitoring', 'level' => 'main', 'perms' => ['view']],
            ['key' => 'control_panel', 'name' => 'Control Panel [MAIN]', 'icon' => '🔧', 'desc' => 'Unified configure hub for Category, Projects, System Branding, and Account Suites', 'level' => 'main', 'perms' => ['view', 'create', 'edit', 'delete']],
            ['key' => 'control_panel.categories', 'name' => 'Sub: Categories Configure', 'icon' => '', 'desc' => 'Configure core transaction tagging categories', 'level' => 'sub', 'perms' => ['view', 'create', 'edit', 'delete']],
            ['key' => 'control_panel.projects', 'name' => 'Sub: Projects Configure', 'icon' => '', 'desc' => 'Manage active venture projects options', 'level' => 'sub', 'perms' => ['view', 'create', 'edit', 'delete']],
            ['key' => 'control_panel.branding', 'name' => 'Sub: System Branding', 'icon' => '', 'desc' => 'Configure app display names and logo vectors', 'level' => 'sub', 'perms' => ['view', 'edit']],
            ['key' => 'control_panel.accounts', 'name' => 'Sub: Account Suites', 'icon' => '', 'desc' => 'Define double-entry legal entity accounts', 'level' => 'sub', 'perms' => ['view', 'create', 'edit', 'delete']],
            ['key' => 'accounting', 'name' => 'Advanced Accounting Suite [MAIN]', 'icon' => '🏛️', 'desc' => 'Double-entry ledger matching, customer invoices, bank reconciliation', 'level' => 'main', 'perms' => ['view', 'create', 'edit', 'delete']],
            ['key' => 'accounting.invoices', 'name' => 'Sub: Sales Invoicing (A/R)', 'icon' => '', 'desc' => 'Create, edit and track client invoices and receivables', 'level' => 'sub', 'perms' => ['view', 'create', 'edit', 'delete']],
            ['key' => 'accounting.payable', 'name' => 'Sub: Accounts Payable (A/P)', 'icon' => '', 'desc' => 'Register and track vendor bills and payables', 'level' => 'sub', 'perms' => ['view', 'create', 'edit', 'delete']],
            ['key' => 'accounting.ledger', 'name' => 'Sub: General Ledger (G/L)', 'icon' => '', 'desc' => 'Post and manage double-entry journal records', 'level' => 'sub', 'perms' => ['view', 'create', 'edit', 'delete']],
            ['key' => 'accounting.bank', 'name' => 'Sub: Bank Reconciliation', 'icon' => '', 'desc' => 'Manage bank entries and match with vouchers', 'level' => 'sub', 'perms' => ['view', 'create', 'edit', 'delete']],
            ['key' => 'accounting.reports', 'name' => 'Sub: Financial & tax Reports', 'icon' => '', 'desc' => 'Export compliance data, CSV reports, and PDF summaries', 'level' => 'sub', 'perms' => ['view', 'create', 'edit', 'delete']],
            ['key' => 'accounting.chart', 'name' => 'Sub: Chart of Accounts', 'icon' => '', 'desc' => 'Define structural ledger codes and general ledger books', 'level' => 'sub', 'perms' => ['view', 'create', 'edit', 'delete']],
        ];
    }

    public static function forUser(int $userId): array
    {
        $perms = self::where('user_id', $userId)->get()->keyBy('module');
        $result = [];
        foreach (self::modules() as $mod) {
            $p = $perms->get($mod['key']);
            $available = $mod['perms'];
            $result[$mod['key']] = [
                'view' => in_array('view', $available) ? ($p ? $p->can_view : true) : null,
                'create' => in_array('create', $available) ? ($p ? $p->can_create : true) : null,
                'edit' => in_array('edit', $available) ? ($p ? $p->can_edit : true) : null,
                'delete' => in_array('delete', $available) ? ($p ? $p->can_delete : true) : null,
            ];
        }
        return $result;
    }
}
