<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Project;
use App\Models\Account;
use App\Models\Setting;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class ControlPanelController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'categories');
        $categories = Category::all();
        $projects = Project::all();
        $accounts = Account::orderBy('code')->get();

        return view('control.index', compact('tab', 'categories', 'projects', 'accounts'));
    }

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_bn' => 'required|string|max:255',
            'icon' => 'nullable|string|max:10',
        ]);

        Category::create($validated);
        AuditLog::record('CREATE', 'Created category: ' . $validated['name_en'] . ' | ' . $validated['name_bn']);
        return redirect('/control-panel?tab=categories')->with('success', 'Category created!');
    }

    public function updateCategory(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_bn' => 'required|string|max:255',
            'icon' => 'nullable|string|max:10',
        ]);

        $category->update($validated);
        AuditLog::record('UPDATE', 'Updated category: ' . $validated['name_en'] . ' | ' . $validated['name_bn']);
        return redirect('/control-panel?tab=categories')->with('success', 'Category updated!');
    }

    public function destroyCategory(Category $category)
    {
        $name = $category->name_en;
        $category->delete();
        AuditLog::record('DELETE', 'Deleted category: ' . $name);
        return redirect('/control-panel?tab=categories')->with('success', 'Category deleted!');
    }

    public function storeProject(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_bn' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:10',
            'description' => 'nullable|string',
            'description_bn' => 'nullable|string',
        ]);

        $validated['slug'] = strtolower(str_replace(' ', '-', $validated['name']));
        $validated['status'] = 'active';
        $validated['capital'] = 0;
        $validated['returns'] = 0;

        Project::create($validated);
        AuditLog::record('CREATE', 'Created project: ' . $validated['name']);
        return redirect('/control-panel?tab=projects')->with('success', 'Project created!');
    }

    public function destroyProject(Project $project)
    {
        $name = $project->name;
        $project->delete();
        AuditLog::record('DELETE', 'Deleted project: ' . $name);
        return redirect('/control-panel?tab=projects')->with('success', 'Project deleted!');
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
        AuditLog::record('CREATE', 'Created chart account: ' . $validated['code'] . ' - ' . $validated['title'] . ' (' . $validated['class'] . ')');
        return redirect('/control-panel?tab=accounts')->with('success', 'Account created!');
    }

    public function destroyAccount(Account $account)
    {
        $desc = $account->code . ' - ' . $account->title;
        $account->delete();
        AuditLog::record('DELETE', 'Deleted chart account: ' . $desc);
        return redirect('/control-panel?tab=accounts')->with('success', 'Account deleted!');
    }

    public function saveBranding(Request $request)
    {
        Setting::set('app_title_en', $request->input('app_title_en', 'Village Agro Farm'));
        Setting::set('app_title_bn', $request->input('app_title_bn', 'ভিলেজ এগ্রো ফার্ম'));
        Setting::set('interface_size', $request->input('interface_size', 'medium'));

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = 'brand-logo.' . $file->getClientOriginalExtension();
            $file->move(public_path(), $filename);
            Setting::set('app_logo', '/' . $filename);
        }

        AuditLog::record('UPDATE', 'Updated system branding settings (title/size/logo)');
        return redirect('/control-panel?tab=branding')->with('success', 'System branding updated successfully!');
    }
}
