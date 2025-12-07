<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\PersonalAccount;
use App\Models\PersonalIncome;
use App\Models\PersonalExpense;
use App\Models\PersonalLoan;

class PersonalFinanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create a test user
        $user = User::where('email', 'user@example.com')->first();
        
        if (!$user) {
            $user = User::create([
                'name' => 'Bula, Chand Salvesh',
                'email' => 'user@example.com',
                'password' => bcrypt('password'),
                'role' => 'user',
                'status' => 'active',
            ]);
        }

        // Create personal accounts
        $expenseAccount = PersonalAccount::firstOrCreate(
            ['user_id' => $user->id, 'account_type' => 'expense'],
            [
                'account_number' => '10984661',
                'account_name' => 'Expense Account',
                'balance' => 5000,
                'currency' => 'FJD',
                'status' => 'active',
            ]
        );

        $savingAccount = PersonalAccount::firstOrCreate(
            ['user_id' => $user->id, 'account_type' => 'saving'],
            [
                'account_number' => '13674771',
                'account_name' => 'Saving Account',
                'balance' => 15000,
                'currency' => 'FJD',
                'status' => 'active',
            ]
        );

        // Create sample incomes
        $incomeCategories = ['salary', 'freelance', 'investment', 'bonus'];
        for ($i = 0; $i < 5; $i++) {
            PersonalIncome::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'income_date' => now()->subDays(rand(1, 30))->toDateString(),
                    'invoice_number' => 'INV-' . rand(1000, 9999),
                ],
                [
                    'account_id' => $savingAccount->id,
                    'category' => $incomeCategories[array_rand($incomeCategories)],
                    'amount' => rand(1000, 5000),
                    'currency' => 'FJD',
                    'description' => 'Sample income entry',
                    'status' => 'completed',
                ]
            );
        }

        // Create sample expenses
        $expenseCategories = ['food', 'transport', 'utilities', 'entertainment', 'shopping', 'healthcare'];
        for ($i = 0; $i < 8; $i++) {
            PersonalExpense::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'expense_date' => now()->subDays(rand(1, 30))->toDateString(),
                    'invoice_number' => 'EXP-' . rand(1000, 9999),
                ],
                [
                    'account_id' => $expenseAccount->id,
                    'category' => $expenseCategories[array_rand($expenseCategories)],
                    'amount' => rand(50, 500),
                    'currency' => 'FJD',
                    'description' => 'Sample expense entry',
                    'status' => 'completed',
                ]
            );
        }

        // Create sample loans
        PersonalLoan::firstOrCreate(
            ['user_id' => $user->id, 'loan_type' => 'issued'],
            [
                'principal_amount' => 5000,
                'interest_rate' => 5.5,
                'start_date' => now()->subMonths(6)->toDateString(),
                'end_date' => now()->addMonths(6)->toDateString(),
                'amount_paid' => 2500,
                'status' => 'active',
                'notes' => 'Personal loan issued',
            ]
        );
    }
}
