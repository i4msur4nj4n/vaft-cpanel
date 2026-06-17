<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Category;
use App\Models\Project;
use App\Models\Transaction;
use App\Models\Invoice;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Users
        $admin = User::create([
            'name' => 'Admin Rahman',
            'email' => 'admin@agro.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        $user = User::create([
            'name' => 'Sajib Ahmed',
            'email' => 'user@agro.com',
            'password' => Hash::make('user123'),
            'role' => 'user',
        ]);

        // Categories
        $categories = [
            ['name_en' => 'Salary', 'name_bn' => 'বেতন', 'icon' => '💰', 'description' => 'Monthly salary and wages'],
            ['name_en' => 'Business', 'name_bn' => 'ব্যবসা', 'icon' => '💼', 'description' => 'Business income and expenses'],
            ['name_en' => 'Investments', 'name_bn' => 'বিনিয়োগ', 'icon' => '📈', 'description' => 'Investment returns'],
            ['name_en' => 'Food', 'name_bn' => 'খাবার', 'icon' => '🍚', 'description' => 'Food and dining'],
            ['name_en' => 'Housing', 'name_bn' => 'বাসস্থান', 'icon' => '🏠', 'description' => 'Rent and housing'],
            ['name_en' => 'Utilities', 'name_bn' => 'ইউটিলিটি', 'icon' => '⚡', 'description' => 'Electricity, water, gas'],
            ['name_en' => 'Transport', 'name_bn' => 'যাতায়াত', 'icon' => '🚗', 'description' => 'Transportation costs'],
            ['name_en' => 'Entertainment', 'name_bn' => 'বিনোদন', 'icon' => '🎬', 'description' => 'Entertainment and leisure'],
            ['name_en' => 'Medical', 'name_bn' => 'চিকিৎসা', 'icon' => '🏥', 'description' => 'Healthcare expenses'],
            ['name_en' => 'Education', 'name_bn' => 'শিক্ষা', 'icon' => '📚', 'description' => 'Education and training'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }

        // Projects
        $project1 = Project::create([
            'name' => 'Poultry Farm Expansion',
            'description' => 'Expanding chicken farm capacity by 500 birds',
            'status' => 'active',
            'capital' => 150000,
            'returns' => 45000,
        ]);

        $project2 = Project::create([
            'name' => 'Fish Cultivation',
            'description' => 'Tilapia and Rui fish farming in 2 ponds',
            'status' => 'active',
            'capital' => 80000,
            'returns' => 25000,
        ]);

        // Transactions
        $transactions = [
            ['user_id' => $admin->id, 'category_id' => 1, 'type' => 'income', 'amount' => 45000, 'date' => '2026-06-01', 'notes' => 'Monthly salary deposit June 2026'],
            ['user_id' => $admin->id, 'category_id' => 2, 'type' => 'income', 'amount' => 8000, 'date' => '2026-06-06', 'notes' => 'Freelance UI design service contract'],
            ['user_id' => $admin->id, 'category_id' => 4, 'type' => 'expense', 'amount' => 1200, 'date' => '2026-06-07', 'notes' => 'Family weekend dinner at local diner'],
            ['user_id' => $admin->id, 'category_id' => 9, 'type' => 'expense', 'amount' => 4800, 'date' => '2026-06-06', 'notes' => 'Doctor consultation & medicine buying'],
            ['user_id' => $admin->id, 'category_id' => 6, 'type' => 'expense', 'amount' => 2500, 'date' => '2026-06-05', 'notes' => 'Electricity billing monthly payment'],
            ['user_id' => $admin->id, 'category_id' => 7, 'type' => 'expense', 'amount' => 1500, 'date' => '2026-06-04', 'notes' => 'Uber & Rickshaw commutes'],
            ['user_id' => $admin->id, 'category_id' => 5, 'type' => 'expense', 'amount' => 12000, 'date' => '2026-06-02', 'notes' => 'Monthly house rent payment'],
            ['user_id' => $admin->id, 'category_id' => 4, 'type' => 'expense', 'amount' => 3200, 'date' => '2026-06-03', 'notes' => 'Weekly grocery shopping'],
            ['user_id' => $user->id, 'category_id' => 1, 'type' => 'income', 'amount' => 35000, 'date' => '2026-06-01', 'notes' => 'Monthly salary deposit'],
            ['user_id' => $user->id, 'category_id' => 8, 'type' => 'expense', 'amount' => 800, 'date' => '2026-06-05', 'notes' => 'Netflix & Spotify subscription'],
        ];

        foreach ($transactions as $txn) {
            Transaction::create($txn);
        }

        // Invoices
        Invoice::create([
            'invoice_number' => 'INV-2026-001',
            'client_name' => 'Rahman Poultry Ltd',
            'client_email' => 'rahman@poultry.com',
            'issue_date' => '2026-06-01',
            'due_date' => '2026-06-30',
            'amount' => 25000,
            'status' => 'paid',
        ]);

        Invoice::create([
            'invoice_number' => 'INV-2026-002',
            'client_name' => 'Green Valley Farms',
            'client_email' => 'info@greenvalley.com',
            'issue_date' => '2026-06-05',
            'due_date' => '2026-07-05',
            'amount' => 18500,
            'status' => 'unpaid',
        ]);

        Invoice::create([
            'invoice_number' => 'INV-2026-003',
            'client_name' => 'Dhaka Fish Market',
            'client_email' => 'orders@dhakafish.com',
            'issue_date' => '2026-06-10',
            'due_date' => '2026-07-10',
            'amount' => 12000,
            'status' => 'unpaid',
        ]);
    }
}
