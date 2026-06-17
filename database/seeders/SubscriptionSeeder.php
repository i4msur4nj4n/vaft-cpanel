<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubscriptionPlan;
use App\Models\PaymentGateway;
use App\Models\UserSubscription;

class SubscriptionSeeder extends Seeder
{
    public function run(): void
    {
        $basic = SubscriptionPlan::create([
            'name_en' => 'Basic Plan',
            'name_bn' => 'বেসিক প্ল্যান',
            'slug' => 'basic-plan',
            'price' => 499,
            'period' => 'month',
            'features_en' => ['Access to core accounting ledger', 'Download reports in CSV formats', 'Standard multi-project tracking', '14 days trial included'],
            'features_bn' => ['মূল হিসাব খাতার সম্পূর্ণ অ্যাক্সেস', 'সিএসভি (CSV) আকারে রিপোর্ট ডাউনলোড', 'মাল্টি-প্রজেক্ট ট্র্যাকিং সুবিধা', '১৪ দিনের ট্রায়াল সুবিধা'],
        ]);

        $premium = SubscriptionPlan::create([
            'name_en' => 'Premium Professional',
            'name_bn' => 'প্রিমিয়াম প্রফেশনাল',
            'slug' => 'premium-professional',
            'price' => 1299,
            'period' => 'month',
            'features_en' => ['All Basic features included', 'Advanced analytics & charts', 'Invoice generation & management', 'Priority email support', 'Custom category management'],
            'features_bn' => ['সকল বেসিক সুবিধা অন্তর্ভুক্ত', 'উন্নত বিশ্লেষণ ও চার্ট', 'ইনভয়েস তৈরি ও ব্যবস্থাপনা', 'অগ্রাধিকার ইমেইল সাপোর্ট', 'কাস্টম ক্যাটাগরি ব্যবস্থাপনা'],
        ]);

        $enterprise = SubscriptionPlan::create([
            'name_en' => 'Enterprise Business',
            'name_bn' => 'এন্টারপ্রাইজ বিজনেস',
            'slug' => 'enterprise-business',
            'price' => 4999,
            'period' => 'year',
            'features_en' => ['All Premium features included', 'Unlimited users & projects', 'Bank reconciliation module', 'Vendor bill management', 'Dedicated account manager', 'Custom integrations'],
            'features_bn' => ['সকল প্রিমিয়াম সুবিধা অন্তর্ভুক্ত', 'সীমাহীন ব্যবহারকারী ও প্রজেক্ট', 'ব্যাংক রিকনসিলিয়েশন মডিউল', 'ভেন্ডর বিল ব্যবস্থাপনা', 'ডেডিকেটেড অ্যাকাউন্ট ম্যানেজার', 'কাস্টম ইন্টিগ্রেশন'],
        ]);

        // Gateways
        PaymentGateway::create(['name' => 'bKash', 'slug' => 'bkash', 'account_number' => '+880 1712 345678', 'instructions' => 'Send payment via bKash to the number above. Use "Send Money" option. After payment, note down the Transaction ID (TrxID) and submit it here.', 'is_active' => true]);
        PaymentGateway::create(['name' => 'Nagad', 'slug' => 'nagad', 'account_number' => '+880 1812 345678', 'instructions' => 'Send payment via Nagad to the number above. Use "Send Money" option. After payment, provide the Transaction Reference number.', 'is_active' => true]);
        PaymentGateway::create(['name' => 'Rocket', 'slug' => 'rocket', 'account_number' => '+880 1912 345678-0', 'instructions' => 'Send payment via Rocket DBBL to the number above (include dash-0). After sending, submit your Rocket Transaction ID.', 'is_active' => true]);
        PaymentGateway::create(['name' => 'Bank Transfer', 'slug' => 'bank-transfer', 'account_number' => 'City Bank AC: 11029348123', 'instructions' => 'Transfer to the bank account above. Branch: Dhanmondi, Dhaka. After transfer, upload the deposit slip or provide the reference number.', 'is_active' => true]);

        // User subscriptions
        UserSubscription::create(['user_id' => 1, 'plan_id' => $enterprise->id, 'status' => 'active', 'amount_paid' => 0, 'expires_at' => '2999-12-31', 'trx_ref' => null]);
        UserSubscription::create(['user_id' => 2, 'plan_id' => $basic->id, 'status' => 'active', 'amount_paid' => 499, 'expires_at' => '2026-12-31', 'trx_ref' => 'BK1245TX']);
    }
}
