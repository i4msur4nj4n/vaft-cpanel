<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubscriptionPlan;
use App\Models\PaymentGateway;
use App\Models\UserSubscription;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Support\Str;

class SubscriptionController extends Controller
{
    public function index()
    {
        $plans = SubscriptionPlan::orderBy('price')->get();
        $gateways = PaymentGateway::orderBy('name')->get();
        $subscriptions = UserSubscription::with(['user', 'plan'])->orderByDesc('created_at')->get();

        $totalSales = UserSubscription::sum('amount_paid');
        $activeCount = UserSubscription::where('status', 'active')->where('amount_paid', '>', 0)->count();
        $pendingCount = UserSubscription::where('status', 'pending')->count();
        $trialCount = UserSubscription::where('status', 'active')->where('amount_paid', 0)->count();

        return view('admin.subscriptions', compact(
            'plans', 'gateways', 'subscriptions',
            'totalSales', 'activeCount', 'pendingCount', 'trialCount'
        ));
    }

    public function storePlan(Request $request)
    {
        $validated = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_bn' => 'nullable|string|max:255',
            'price' => 'required|integer|min:0',
            'period' => 'required|in:month,year',
            'features_en' => 'nullable|string',
            'features_bn' => 'nullable|string',
        ]);

        SubscriptionPlan::create([
            'name_en' => $validated['name_en'],
            'name_bn' => $validated['name_bn'] ?? null,
            'slug' => Str::slug($validated['name_en']),
            'price' => $validated['price'],
            'period' => $validated['period'],
            'features_en' => array_values(array_filter(explode("\n", trim($validated['features_en'] ?? '')))),
            'features_bn' => array_values(array_filter(explode("\n", trim($validated['features_bn'] ?? '')))),
        ]);

        AuditLog::record('CREATE', 'Created subscription plan: ' . $validated['name_en']);
        return redirect('/admin-panel/subscriptions')->with('success', 'Subscription plan created!');
    }

    public function updatePlan(Request $request, SubscriptionPlan $plan)
    {
        $validated = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_bn' => 'nullable|string|max:255',
            'price' => 'required|integer|min:0',
            'period' => 'required|in:month,year',
            'features_en' => 'nullable|string',
            'features_bn' => 'nullable|string',
        ]);

        $plan->update([
            'name_en' => $validated['name_en'],
            'name_bn' => $validated['name_bn'] ?? null,
            'slug' => Str::slug($validated['name_en']),
            'price' => $validated['price'],
            'period' => $validated['period'],
            'features_en' => array_values(array_filter(explode("\n", trim($validated['features_en'] ?? '')))),
            'features_bn' => array_values(array_filter(explode("\n", trim($validated['features_bn'] ?? '')))),
        ]);

        AuditLog::record('UPDATE', 'Admin updated subscription plan details for: \'' . $validated['name_en'] . '\'');
        return redirect('/admin-panel/subscriptions')->with('success', 'Plan updated!');
    }

    public function destroyPlan(SubscriptionPlan $plan)
    {
        $name = $plan->name_en;
        $plan->delete();
        AuditLog::record('DELETE', 'Deleted subscription plan: ' . $name);
        return redirect('/admin-panel/subscriptions')->with('success', 'Plan deleted!');
    }

    public function storeGateway(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_bn' => 'nullable|string|max:255',
            'account_number' => 'required|string|max:255',
            'instructions' => 'nullable|string',
            'instructions_bn' => 'nullable|string',
            'is_active' => 'nullable',
        ]);

        PaymentGateway::create([
            'name' => $validated['name'],
            'name_bn' => $validated['name_bn'] ?? null,
            'slug' => Str::slug($validated['name']),
            'account_number' => $validated['account_number'],
            'instructions' => $validated['instructions'] ?? null,
            'instructions_bn' => $validated['instructions_bn'] ?? null,
            'is_active' => isset($validated['is_active']) ? $validated['is_active'] == '1' : true,
        ]);

        AuditLog::record('CREATE', 'Added payment gateway: ' . $validated['name']);
        return redirect('/admin-panel/subscriptions')->with('success', 'Payment gateway added!');
    }

    public function updateGateway(Request $request, PaymentGateway $gateway)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_bn' => 'nullable|string|max:255',
            'account_number' => 'required|string|max:255',
            'instructions' => 'nullable|string',
            'instructions_bn' => 'nullable|string',
            'is_active' => 'nullable',
        ]);

        $gateway->update([
            'name' => $validated['name'],
            'name_bn' => $validated['name_bn'] ?? null,
            'slug' => Str::slug($validated['name']),
            'account_number' => $validated['account_number'],
            'instructions' => $validated['instructions'] ?? null,
            'instructions_bn' => $validated['instructions_bn'] ?? null,
            'is_active' => isset($validated['is_active']) ? $validated['is_active'] == '1' : true,
        ]);

        AuditLog::record('UPDATE', 'Updated payment gateway: ' . $validated['name']);
        return redirect('/admin-panel/subscriptions')->with('success', 'Gateway updated!');
    }

    public function destroyGateway(PaymentGateway $gateway)
    {
        $name = $gateway->name;
        $gateway->delete();
        AuditLog::record('DELETE', 'Deleted payment gateway: ' . $name);
        return redirect('/admin-panel/subscriptions')->with('success', 'Gateway deleted!');
    }

    public function updateUserSubscription(Request $request, User $user)
    {
        $validated = $request->validate([
            'plan_id' => 'required|exists:subscription_plans,id',
            'status' => 'required|in:active,expired,pending,cancelled',
            'amount_paid' => 'required|integer|min:0',
            'expires_at' => 'required|date',
            'trx_ref' => 'nullable|string|max:255',
        ]);

        UserSubscription::updateOrCreate(
            ['user_id' => $user->id],
            $validated
        );

        AuditLog::record('UPDATE', 'Updated subscription for user: ' . $user->name);
        return redirect('/admin-panel/subscriptions')->with('success', 'User subscription updated!');
    }
}
