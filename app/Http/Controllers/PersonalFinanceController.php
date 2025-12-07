<?php

namespace App\Http\Controllers;

use App\Models\PersonalAccount;
use App\Models\PersonalIncome;
use App\Models\PersonalExpense;
use App\Models\PersonalLoan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PersonalFinanceController extends Controller
{
    /**
     * Show the personal finance dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        
        $totalIncome = $user->personalIncomes()->sum('amount');
        $totalExpenses = $user->personalExpenses()->sum('amount');
        $cashOnHand = $totalIncome - $totalExpenses;
        
        $expenseAccount = $user->personalAccounts()->where('account_type', 'expense')->first();
        $savingAccount = $user->personalAccounts()->where('account_type', 'saving')->first();
        
        $expenses = $user->personalExpenses()->orderBy('expense_date', 'desc')->get();
        $incomes = $user->personalIncomes()->orderBy('income_date', 'desc')->get();
        $loans = $user->personalLoans()->where('status', 'active')->get();
        
        return view('personal-finance.dashboard', [
            'user' => $user,
            'totalIncome' => $totalIncome,
            'totalExpenses' => $totalExpenses,
            'cashOnHand' => $cashOnHand,
            'expenseAccount' => $expenseAccount,
            'savingAccount' => $savingAccount,
            'expenses' => $expenses,
            'incomes' => $incomes,
            'loans' => $loans,
        ]);
    }

    /**
     * Store a new income entry.
     */
    public function storeIncome(Request $request)
    {
        $validated = $request->validate([
            'income_date' => 'required|date',
            'invoice_number' => 'nullable|string',
            'category' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string',
            'account_id' => 'nullable|exists:personal_accounts,id',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'completed';

        PersonalIncome::create($validated);

        return response()->json(['success' => true, 'message' => 'Income added successfully']);
    }

    /**
     * Store a new expense entry.
     */
    public function storeExpense(Request $request)
    {
        $validated = $request->validate([
            'expense_date' => 'required|date',
            'invoice_number' => 'nullable|string',
            'category' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string',
            'account_id' => 'nullable|exists:personal_accounts,id',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'completed';

        PersonalExpense::create($validated);

        return response()->json(['success' => true, 'message' => 'Expense added successfully']);
    }

    /**
     * Update an income entry.
     */
    public function updateIncome(Request $request, PersonalIncome $income)
    {
        $this->authorize('update', $income);

        $validated = $request->validate([
            'income_date' => 'required|date',
            'invoice_number' => 'nullable|string',
            'category' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string',
            'account_id' => 'nullable|exists:personal_accounts,id',
        ]);

        $income->update($validated);

        return response()->json(['success' => true, 'message' => 'Income updated successfully']);
    }

    /**
     * Update an expense entry.
     */
    public function updateExpense(Request $request, PersonalExpense $expense)
    {
        $this->authorize('update', $expense);

        $validated = $request->validate([
            'expense_date' => 'required|date',
            'invoice_number' => 'nullable|string',
            'category' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string',
            'account_id' => 'nullable|exists:personal_accounts,id',
        ]);

        $expense->update($validated);

        return response()->json(['success' => true, 'message' => 'Expense updated successfully']);
    }

    /**
     * Delete an income entry.
     */
    public function destroyIncome(PersonalIncome $income)
    {
        $this->authorize('delete', $income);
        $income->delete();

        return response()->json(['success' => true, 'message' => 'Income deleted successfully']);
    }

    /**
     * Delete an expense entry.
     */
    public function destroyExpense(PersonalExpense $expense)
    {
        $this->authorize('delete', $expense);
        $expense->delete();

        return response()->json(['success' => true, 'message' => 'Expense deleted successfully']);
    }

    /**
     * Get income data for AJAX.
     */
    public function getIncomes()
    {
        $incomes = Auth::user()->personalIncomes()->orderBy('income_date', 'desc')->get();
        return response()->json($incomes);
    }

    /**
     * Get expense data for AJAX.
     */
    public function getExpenses()
    {
        $expenses = Auth::user()->personalExpenses()->orderBy('expense_date', 'desc')->get();
        return response()->json($expenses);
    }

    /**
     * Get loans data for AJAX.
     */
    public function getLoans()
    {
        $loans = Auth::user()->personalLoans()->where('status', 'active')->get();
        return response()->json($loans);
    }

    /**
     * Push savings (transfer from income to savings account).
     */
    public function pushSavings(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
        ]);

        $user = Auth::user();
        $savingAccount = $user->personalAccounts()->where('account_type', 'saving')->first();

        if (!$savingAccount) {
            return response()->json(['success' => false, 'message' => 'Saving account not found'], 404);
        }

        // Create a savings transaction
        PersonalIncome::create([
            'user_id' => $user->id,
            'account_id' => $savingAccount->id,
            'income_date' => now()->toDateString(),
            'category' => 'savings_transfer',
            'amount' => $validated['amount'],
            'description' => 'Savings transfer',
            'status' => 'completed',
        ]);

        return response()->json(['success' => true, 'message' => 'Savings pushed successfully']);
    }

    /**
     * Hide/Show amounts.
     */
    public function toggleAmountVisibility(Request $request)
    {
        $user = Auth::user();
        $visibility = $request->input('visibility', true);

        // Store in session or user preferences
        session(['show_amounts' => $visibility]);

        return response()->json(['success' => true, 'visibility' => $visibility]);
    }

    /**
     * Get chart data for income vs expenses.
     */
    public function getChartData()
    {
        $user = Auth::user();
        
        // Get last 12 months data
        $months = [];
        $incomeData = [];
        $expenseData = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $month = $date->format('M Y');
            $months[] = $month;
            
            $income = $user->personalIncomes()
                ->whereYear('income_date', $date->year)
                ->whereMonth('income_date', $date->month)
                ->sum('amount');
            
            $expense = $user->personalExpenses()
                ->whereYear('expense_date', $date->year)
                ->whereMonth('expense_date', $date->month)
                ->sum('amount');
            
            $incomeData[] = $income;
            $expenseData[] = $expense;
        }
        
        return response()->json([
            'months' => $months,
            'income' => $incomeData,
            'expenses' => $expenseData,
        ]);
    }

    /**
     * Get category breakdown data.
     */
    public function getCategoryData()
    {
        $user = Auth::user();
        
        $expensesByCategory = $user->personalExpenses()
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->get();
        
        $incomeByCategory = $user->personalIncomes()
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->get();
        
        return response()->json([
            'expenses' => $expensesByCategory,
            'income' => $incomeByCategory,
        ]);
    }

    /**
     * Get account details.
     */
    public function getAccountDetails()
    {
        $user = Auth::user();
        
        $accounts = $user->personalAccounts()->get();
        
        return response()->json($accounts);
    }

    /**
     * Show transactions page.
     */
    public function transactions()
    {
        $user = Auth::user();
        
        $incomes = $user->personalIncomes()->get();
        $expenses = $user->personalExpenses()->get();
        
        $allTransactions = [];
        
        foreach ($incomes as $income) {
            $allTransactions[] = [
                'id' => $income->id,
                'type' => 'income',
                'date' => $income->income_date->format('Y-m-d'),
                'category' => $income->category,
                'description' => $income->description ?? 'Income',
                'amount' => $income->amount,
                'status' => $income->status,
            ];
        }
        
        foreach ($expenses as $expense) {
            $allTransactions[] = [
                'id' => $expense->id,
                'type' => 'expense',
                'date' => $expense->expense_date->format('Y-m-d'),
                'category' => $expense->category,
                'description' => $expense->description ?? 'Expense',
                'amount' => $expense->amount,
                'status' => $expense->status,
            ];
        }
        
        usort($allTransactions, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });
        
        return view('personal-finance.transactions', [
            'allTransactions' => $allTransactions,
        ]);
    }

    /**
     * Show budgets page.
     */
    public function budgets()
    {
        return view('personal-finance.budgets');
    }

    /**
     * Show loans page.
     */
    public function loans()
    {
        $user = Auth::user();
        $loans = $user->personalLoans()->get();
        
        return view('personal-finance.loans', [
            'loans' => $loans,
        ]);
    }

    /**
     * Show reports page.
     */
    public function reports()
    {
        return view('personal-finance.reports');
    }
}
