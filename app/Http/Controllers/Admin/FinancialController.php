<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FinancialTransaction;
use App\Models\FinancialBudget;
use App\Models\FinancialInvoice;
use App\Models\Project;
use App\Models\IncomeSource;
use App\Models\MonthlyIncomeBudget;
use App\Models\MonthlyExpenseBudget;
use App\Models\ExpenseTracking;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class FinancialController extends Controller
{
    /**
     * Display the financial dashboard.
     */
    public function index()
    {
        // Include both pending and completed transactions in calculations
        $totalIncome = FinancialTransaction::where('type', 'income')
            ->whereIn('status', ['pending', 'approved', 'completed'])
            ->sum('amount');

        $totalExpenses = FinancialTransaction::where('type', 'expense')
            ->whereIn('status', ['pending', 'approved', 'completed'])
            ->sum('amount');

        // Wallet data - include all non-rejected transactions
        $cashOnHand = FinancialTransaction::where('account', 'cash')
            ->whereIn('status', ['pending', 'approved', 'completed'])
            ->sum('amount');
        
        $anzExpenseAccount = FinancialTransaction::where('account', 'anz_expense')
            ->whereIn('status', ['pending', 'approved', 'completed'])
            ->sum('amount');
        
        $anzSavingsAccount = FinancialTransaction::where('account', 'anz_savings')
            ->whereIn('status', ['pending', 'approved', 'completed'])
            ->sum('amount');
        
        $totalInWallet = $cashOnHand + $anzExpenseAccount + $anzSavingsAccount;

        $pendingTransactions = FinancialTransaction::where('status', 'pending')->count();
        $totalBudgets = FinancialBudget::where('status', 'active')->count();
        $unpaidInvoices = FinancialInvoice::whereIn('status', ['sent', 'overdue'])->count();

        // Recent transactions separated by type - show all non-rejected
        $recentIncomeTransactions = FinancialTransaction::with('project', 'createdBy')
            ->where('type', 'income')
            ->whereNotIn('status', ['rejected'])
            ->latest('transaction_date')
            ->limit(20)
            ->get();

        $recentExpenseTransactions = FinancialTransaction::with('project', 'createdBy')
            ->where('type', 'expense')
            ->whereNotIn('status', ['rejected'])
            ->latest('transaction_date')
            ->limit(20)
            ->get();

        // Budget overview
        $budgets = FinancialBudget::where('status', 'active')
            ->with('project')
            ->limit(5)
            ->get();

        // Monthly income/expense chart data
        $currentYear = Carbon::now()->year;
        $monthlyData = $this->getMonthlyFinancialData($currentYear);

        // Income by category for chart
        $incomeByCategory = FinancialTransaction::where('type', 'income')
            ->where('status', 'completed')
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->get()
            ->toArray();

        // Expense by category for chart
        $expenseByCategory = FinancialTransaction::where('type', 'expense')
            ->where('status', 'completed')
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->get()
            ->toArray();

        // Get unique income and expense categories for dropdowns
        $incomeCategories = FinancialTransaction::where('type', 'income')
            ->distinct()
            ->pluck('category')
            ->toArray();

        $expenseCategories = FinancialTransaction::where('type', 'expense')
            ->distinct()
            ->pluck('category')
            ->toArray();

        // Invoice summary
        $invoiceSummary = [
            'total_invoices' => FinancialInvoice::count(),
            'paid_invoices' => FinancialInvoice::where('status', 'paid')->count(),
            'pending_invoices' => FinancialInvoice::whereIn('status', ['draft', 'sent'])->count(),
            'overdue_invoices' => FinancialInvoice::where('status', 'overdue')->count(),
        ];

        // Additional chart data
        $billsDistribution = $this->getBillsDistribution();
        $savingsVsExpenses = $this->getSavingsVsExpenses($currentYear);
        $householdExpenses = $this->getHouseholdExpenses($currentYear);
        $monthlySavingsDebts = $this->getMonthlySavingsDebts($currentYear);
        $savingsOverYear = $this->getSavingsOverYear($currentYear);
        $monthlyNeeds = $this->getMonthlyNeeds($currentYear);
        $savingsFunds = $this->getSavingsFunds();
        $bestSavingMonth = $this->getBestSavingMonth($currentYear);
        $biggestExpenseCategory = $this->getBiggestExpenseCategory();

        return view('admin.financial.index', [
            'totalIncome' => $totalIncome,
            'totalExpenses' => $totalExpenses,
            'netProfit' => $totalIncome - $totalExpenses,
            'totalInWallet' => $totalInWallet,
            'cashOnHand' => $cashOnHand,
            'anzExpenseAccount' => $anzExpenseAccount,
            'anzSavingsAccount' => $anzSavingsAccount,
            'pendingTransactions' => $pendingTransactions,
            'totalBudgets' => $totalBudgets,
            'unpaidInvoices' => $unpaidInvoices,
            'recentIncomeTransactions' => $recentIncomeTransactions,
            'recentExpenseTransactions' => $recentExpenseTransactions,
            'budgets' => $budgets,
            'monthlyData' => $monthlyData,
            'incomeByCategory' => $incomeByCategory,
            'expenseByCategory' => $expenseByCategory,
            'incomeCategories' => $incomeCategories,
            'expenseCategories' => $expenseCategories,
            'invoiceSummary' => $invoiceSummary,
            'billsDistribution' => $billsDistribution,
            'savingsVsExpenses' => $savingsVsExpenses,
            'householdExpenses' => $householdExpenses,
            'monthlySavingsDebts' => $monthlySavingsDebts,
            'savingsOverYear' => $savingsOverYear,
            'monthlyNeeds' => $monthlyNeeds,
            'savingsFunds' => $savingsFunds,
            'bestSavingMonth' => $bestSavingMonth,
            'biggestExpenseCategory' => $biggestExpenseCategory,
        ]);
    }

    /**
     * Display transactions list.
     */
    public function transactions()
    {
        $transactions = FinancialTransaction::with('project', 'createdBy', 'approvedBy')
            ->latest('transaction_date')
            ->paginate(20);

        $categories = ['salary', 'equipment', 'software', 'travel', 'utilities', 'marketing', 'client_payment', 'other'];
        $statuses = ['pending', 'approved', 'rejected', 'completed'];

        return view('admin.financial.transactions', [
            'transactions' => $transactions,
            'categories' => $categories,
            'statuses' => $statuses,
        ]);
    }

    /**
     * Show income management page with summary data.
     */
    public function showIncome()
    {
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;
        
        // Total income (all time)
        $totalIncome = FinancialTransaction::where('type', 'income')
            ->whereIn('status', ['pending', 'approved', 'completed'])
            ->sum('amount');
        
        // This month's income
        $thisMonthIncome = FinancialTransaction::where('type', 'income')
            ->whereIn('status', ['pending', 'approved', 'completed'])
            ->whereYear('transaction_date', $currentYear)
            ->whereMonth('transaction_date', $currentMonth)
            ->sum('amount');
        
        // Last month's income
        $lastMonth = Carbon::now()->subMonth();
        $lastMonthIncome = FinancialTransaction::where('type', 'income')
            ->whereIn('status', ['pending', 'approved', 'completed'])
            ->whereYear('transaction_date', $lastMonth->year)
            ->whereMonth('transaction_date', $lastMonth->month)
            ->sum('amount');
        
        // This year's income
        $thisYearIncome = FinancialTransaction::where('type', 'income')
            ->whereIn('status', ['pending', 'approved', 'completed'])
            ->whereYear('transaction_date', $currentYear)
            ->sum('amount');
        
        // Income sources count
        $incomeSourcesCount = FinancialTransaction::where('type', 'income')
            ->whereIn('status', ['pending', 'approved', 'completed'])
            ->count();
        
        // Average monthly income
        $avgMonthlyIncome = $thisYearIncome / max(1, $currentMonth);
        
        // Monthly change percentage
        $monthlyChange = $lastMonthIncome > 0 
            ? round((($thisMonthIncome - $lastMonthIncome) / $lastMonthIncome) * 100, 1) 
            : 0;
        
        // Income by category
        $incomeByCategory = FinancialTransaction::where('type', 'income')
            ->whereIn('status', ['pending', 'approved', 'completed'])
            ->whereYear('transaction_date', $currentYear)
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();
        
        // Monthly income data for chart
        $monthlyIncomeData = $this->getMonthlyIncomeData($currentYear);
        
        // Top income source
        $topIncomeSource = FinancialTransaction::where('type', 'income')
            ->whereIn('status', ['pending', 'approved', 'completed'])
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->orderByDesc('total')
            ->first();
        
        // Get all income transactions for display
        $incomeTransactions = FinancialTransaction::where('type', 'income')
            ->where('amount', '>', 0)
            ->whereIn('status', ['pending', 'approved', 'completed'])
            ->latest('transaction_date')
            ->get();
        
        return view('admin.financial.income', [
            'totalIncome' => $totalIncome,
            'thisMonthIncome' => $thisMonthIncome,
            'lastMonthIncome' => $lastMonthIncome,
            'thisYearIncome' => $thisYearIncome,
            'incomeSourcesCount' => $incomeSourcesCount,
            'avgMonthlyIncome' => $avgMonthlyIncome,
            'monthlyChange' => $monthlyChange,
            'incomeByCategory' => $incomeByCategory,
            'monthlyIncomeData' => $monthlyIncomeData,
            'topIncomeSource' => $topIncomeSource,
            'incomeTransactions' => $incomeTransactions,
        ]);
    }

    /**
     * Get monthly income data for the year.
     */
    private function getMonthlyIncomeData($year)
    {
        $months = [];
        $values = [];

        for ($month = 1; $month <= 12; $month++) {
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();
            
            $months[] = $startDate->format('M');
            
            $monthIncome = FinancialTransaction::where('type', 'income')
                ->whereIn('status', ['pending', 'approved', 'completed'])
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->sum('amount');
            
            $values[] = round($monthIncome, 2);
        }

        return [
            'months' => $months,
            'values' => $values,
        ];
    }

    /**
     * Show create income form.
     */
    public function createIncome()
    {
        $projects = Project::where('is_archived', false)->get();
        $invoiceNumber = $this->generateInvoiceNumber('INC');

        return view('admin.financial.create-income', [
            'projects' => $projects,
            'invoiceNumber' => $invoiceNumber,
        ]);
    }

    /**
     * Store a new income transaction.
     */
    public function storeIncome(Request $request)
    {
        $validated = $request->validate([
            'invoice_number' => 'required|string|unique:financial_transactions',
            'category' => 'required|in:salary,equipment,software,travel,utilities,marketing,client_payment,other',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|string|size:3',
            'account' => 'required|in:cash,anz_expense,anz_savings',
            'transaction_date' => 'required|date',
            'project_id' => 'nullable|exists:projects,id',
            'notes' => 'nullable|string',
        ]);

        $validated['type'] = 'income';
        $validated['created_by'] = Auth::id();
        $validated['status'] = 'pending';

        FinancialTransaction::create($validated);

        return redirect()->route('admin.financial.index')
            ->with('success', 'Income transaction created successfully!');
    }

    /**
     * Show create expense form.
     */
    public function createExpense()
    {
        $projects = Project::where('is_archived', false)->get();
        $invoiceNumber = $this->generateInvoiceNumber('EXP');

        return view('admin.financial.create-expense', [
            'projects' => $projects,
            'invoiceNumber' => $invoiceNumber,
        ]);
    }

    /**
     * Store a new expense transaction.
     */
    public function storeExpense(Request $request)
    {
        $validated = $request->validate([
            'invoice_number' => 'required|string|unique:financial_transactions',
            'category' => 'required|in:salary,equipment,software,travel,utilities,marketing,client_payment,other',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|string|size:3',
            'account' => 'required|in:cash,anz_expense,anz_savings',
            'transaction_date' => 'required|date',
            'project_id' => 'nullable|exists:projects,id',
            'notes' => 'nullable|string',
        ]);

        $validated['type'] = 'expense';
        $validated['created_by'] = Auth::id();
        $validated['status'] = 'pending';

        FinancialTransaction::create($validated);

        return redirect()->route('admin.financial.index')
            ->with('success', 'Expense transaction created successfully!');
    }

    /**
     * Show create savings form.
     */
    public function createSavings()
    {
        $invoiceNumber = $this->generateInvoiceNumber('SAV');

        return view('admin.financial.create-savings', [
            'invoiceNumber' => $invoiceNumber,
        ]);
    }

    /**
     * Store a new savings transaction.
     */
    public function storeSavings(Request $request)
    {
        $validated = $request->validate([
            'invoice_number' => 'required|string|unique:financial_transactions',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|string|size:3',
            'transaction_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $validated['type'] = 'income';
        $validated['category'] = 'savings';
        $validated['account'] = 'anz_savings';
        $validated['created_by'] = Auth::id();
        $validated['status'] = 'pending';

        FinancialTransaction::create($validated);

        return redirect()->route('admin.financial.index')
            ->with('success', 'Savings transaction created successfully!');
    }

    /**
     * Show create transaction form.
     */
    public function createTransaction()
    {
        $projects = Project::where('is_archived', false)->get();
        $categories = ['salary', 'equipment', 'software', 'travel', 'utilities', 'marketing', 'client_payment', 'other'];

        return view('admin.financial.create-transaction', [
            'projects' => $projects,
            'categories' => $categories,
        ]);
    }

    /**
     * Store a new transaction.
     */
    public function storeTransaction(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:income,expense',
            'category' => 'required|in:salary,equipment,software,travel,utilities,marketing,client_payment,other',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|string|size:3',
            'account' => 'required|in:cash,anz_expense,anz_savings',
            'transaction_date' => 'required|date',
            'project_id' => 'nullable|exists:projects,id',
            'notes' => 'nullable|string',
        ]);

        $validated['created_by'] = Auth::id();
        $validated['status'] = 'pending';

        FinancialTransaction::create($validated);

        return redirect()->route('admin.financial.transactions')
            ->with('success', 'Transaction created successfully!');
    }

    /**
     * Approve a transaction.
     */
    public function approveTransaction(FinancialTransaction $transaction)
    {
        $transaction->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Transaction approved!');
    }

    /**
     * Reject a transaction.
     */
    public function rejectTransaction(FinancialTransaction $transaction)
    {
        $transaction->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Transaction rejected!');
    }

    /**
     * Show a single transaction.
     */
    public function showTransaction(FinancialTransaction $transaction)
    {
        return view('admin.financial.show-transaction', [
            'transaction' => $transaction->load('project', 'createdBy', 'approvedBy'),
        ]);
    }

    /**
     * Delete a transaction.
     */
    public function destroyTransaction(FinancialTransaction $transaction)
    {
        $transaction->delete();

        return redirect()->route('admin.financial.index')
            ->with('success', 'Transaction deleted successfully!');
    }

    /**
     * Display budgets list.
     */
    public function budgets()
    {
        $budgets = FinancialBudget::with('project')
            ->latest('created_at')
            ->paginate(20);

        return view('admin.financial.budgets', [
            'budgets' => $budgets,
        ]);
    }

    /**
     * Show create budget form.
     */
    public function createBudget()
    {
        $projects = Project::where('is_archived', false)->get();

        return view('admin.financial.create-budget', [
            'projects' => $projects,
        ]);
    }

    /**
     * Store a new budget.
     */
    public function storeBudget(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'allocated_amount' => 'required|numeric|min:0.01',
            'currency' => 'required|string|size:3',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'project_id' => 'nullable|exists:projects,id',
            'description' => 'nullable|string',
        ]);

        FinancialBudget::create($validated);

        return redirect()->route('admin.financial.budgets')
            ->with('success', 'Budget created successfully!');
    }

    /**
     * Display invoices list.
     */
    public function invoices()
    {
        $invoices = FinancialInvoice::with('project', 'client', 'createdBy')
            ->latest('issue_date')
            ->paginate(20);

        return view('admin.financial.invoices', [
            'invoices' => $invoices,
        ]);
    }

    /**
     * Show create invoice form.
     */
    public function createInvoice()
    {
        $projects = Project::where('is_archived', false)->get();

        return view('admin.financial.create-invoice', [
            'projects' => $projects,
        ]);
    }

    /**
     * Store a new invoice.
     */
    public function storeInvoice(Request $request)
    {
        $validated = $request->validate([
            'invoice_number' => 'required|string|unique:financial_invoices',
            'project_id' => 'nullable|exists:projects,id',
            'subtotal' => 'required|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'currency' => 'required|string|size:3',
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after:issue_date',
            'notes' => 'nullable|string',
            'terms' => 'nullable|string',
        ]);

        $validated['total_amount'] = $validated['subtotal'] + ($validated['tax_amount'] ?? 0);
        $validated['created_by'] = Auth::id();
        $validated['status'] = 'draft';

        FinancialInvoice::create($validated);

        return redirect()->route('admin.financial.invoices')
            ->with('success', 'Invoice created successfully!');
    }

    /**
     * Mark invoice as sent.
     */
    public function sendInvoice(FinancialInvoice $invoice)
    {
        $invoice->update([
            'status' => 'sent',
            'sent_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Invoice marked as sent!');
    }

    /**
     * Mark invoice as paid.
     */
    public function markInvoicePaid(FinancialInvoice $invoice)
    {
        $invoice->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Invoice marked as paid!');
    }

    /**
     * Get monthly financial data for charts.
     */
    private function getMonthlyFinancialData($year)
    {
        $income = [];
        $expenses = [];
        $months = [];

        for ($month = 1; $month <= 12; $month++) {
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();

            $monthIncome = FinancialTransaction::where('type', 'income')
                ->where('status', 'completed')
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->sum('amount');

            $monthExpense = FinancialTransaction::where('type', 'expense')
                ->where('status', 'completed')
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->sum('amount');

            $income[] = $monthIncome;
            $expenses[] = $monthExpense;
            $months[] = Carbon::createFromDate($year, $month, 1)->format('M');
        }

        return [
            'months' => $months,
            'income' => $income,
            'expenses' => $expenses,
        ];
    }

    /**
     * Get financial summary statistics.
     */
    public function getFinancialStats()
    {
        $stats = [
            'total_income' => FinancialTransaction::where('type', 'income')
                ->where('status', 'completed')
                ->sum('amount'),
            'total_expenses' => FinancialTransaction::where('type', 'expense')
                ->where('status', 'completed')
                ->sum('amount'),
            'pending_transactions' => FinancialTransaction::where('status', 'pending')->count(),
            'total_budgets' => FinancialBudget::where('status', 'active')->count(),
            'budget_utilization' => $this->calculateBudgetUtilization(),
            'unpaid_invoices' => FinancialInvoice::whereIn('status', ['sent', 'overdue'])->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Calculate overall budget utilization.
     */
    private function calculateBudgetUtilization()
    {
        $totalAllocated = FinancialBudget::where('status', 'active')->sum('allocated_amount');
        $totalSpent = FinancialBudget::where('status', 'active')->sum('spent_amount');

        if ($totalAllocated <= 0) {
            return 0;
        }

        return round(($totalSpent / $totalAllocated) * 100, 2);
    }

    /**
     * Generate unique invoice number.
     */
    private function generateInvoiceNumber($prefix)
    {
        $timestamp = now()->format('YmdHis');
        $random = str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
        return "{$prefix}-{$timestamp}-{$random}";
    }

    /**
     * Store a new donation transaction.
     */
    public function storeDonation(Request $request)
    {
        $validated = $request->validate([
            'invoice_number' => 'required|string|unique:financial_transactions',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|string|size:3',
            'transaction_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $validated['type'] = 'expense';
        $validated['category'] = 'donation';
        $validated['account'] = 'cash';
        $validated['created_by'] = Auth::id();
        $validated['status'] = 'pending';

        FinancialTransaction::create($validated);

        return redirect()->route('admin.financial.index')
            ->with('success', 'Donation recorded successfully!');
    }

    /**
     * Save income source via API.
     */
    public function saveIncomeSource(Request $request)
    {
        try {
            $validated = $request->validate([
                'source_name' => 'required|string|max:255',
                'category' => 'required|in:salary,equipment,software,travel,utilities,marketing,client_payment,savings,other,investment_return,bonus,freelance,rental,gift',
                'type' => 'required|string|in:recurring,variable',
            ]);

            $transaction = FinancialTransaction::create([
                'description' => $validated['source_name'],
                'category' => $validated['category'],
                'type' => 'income',
                'amount' => 0,
                'currency' => 'USD',
                'account' => 'cash',
                'transaction_date' => now(),
                'status' => 'pending',
                'created_by' => Auth::id(),
                'notes' => json_encode(['income_type' => $validated['type']]),
            ]);

            return response()->json(['success' => true, 'data' => $transaction]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Save monthly income budget via API.
     */
    public function saveMonthlyIncomeBudget(Request $request)
    {
        try {
            $validated = $request->validate([
                'month' => 'required|integer|min:0|max:11',
                'budget_amount' => 'required|numeric|min:0',
            ]);

            $year = now()->year;
            $month = $validated['month'] + 1;
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();

            // Create or update budget record
            $budget = FinancialBudget::updateOrCreate(
                [
                    'project_id' => null,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                ],
                [
                    'name' => "Monthly Income Budget - " . $startDate->format('F Y'),
                    'allocated_amount' => $validated['budget_amount'],
                    'currency' => 'USD',
                    'status' => 'active',
                ]
            );

            return response()->json(['success' => true, 'data' => $budget]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Save actual monthly income via API.
     */
    public function saveMonthlyActualIncome(Request $request)
    {
        $validated = $request->validate([
            'month' => 'required|integer|min:0|max:11',
            'actual_amount' => 'required|numeric|min:0',
        ]);

        $year = now()->year;
        $month = $validated['month'] + 1;
        $transactionDate = Carbon::createFromDate($year, $month, 1);

        // Create transaction for actual income
        $transaction = FinancialTransaction::create([
            'description' => "Actual Monthly Income - " . $transactionDate->format('F Y'),
            'category' => 'salary',
            'type' => 'income',
            'amount' => $validated['actual_amount'],
            'currency' => 'USD',
            'account' => 'cash',
            'transaction_date' => $transactionDate,
            'status' => 'completed',
            'created_by' => Auth::id(),
        ]);

        return response()->json(['success' => true, 'data' => $transaction]);
    }

    /**
     * Save expense via API.
     */
    public function saveExpense(Request $request)
    {
        try {
            $validated = $request->validate([
                'expense_name' => 'required|string|max:255',
                'category' => 'required|string',
                'type' => 'required|string|in:fixed,variable',
                'amount' => 'required|numeric|min:0',
                'date' => 'required|date',
                'notes' => 'nullable|string',
            ]);

            // Map expense categories to database enum values
            $categoryMap = [
                'groceries' => 'utilities',
                'shopping' => 'equipment',
                'bills' => 'utilities',
                'rent' => 'utilities',
                'transportation' => 'travel',
                'dining' => 'travel',
                'entertainment' => 'marketing',
                'healthcare' => 'utilities',
                'education' => 'marketing',
                'insurance' => 'utilities',
                'subscriptions' => 'software',
                'phone_internet' => 'utilities',
                'utilities' => 'utilities',
                'maintenance' => 'equipment',
                'office_supplies' => 'equipment',
                'software' => 'software',
                'equipment' => 'equipment',
                'travel' => 'travel',
                'professional_services' => 'marketing',
                'marketing' => 'marketing',
                'personal_care' => 'equipment',
                'gifts' => 'marketing',
                'other' => 'other',
            ];

            $dbCategory = $categoryMap[$validated['category']] ?? 'other';

            $transaction = FinancialTransaction::create([
                'description' => $validated['expense_name'],
                'category' => $dbCategory,
                'type' => 'expense',
                'amount' => $validated['amount'],
                'currency' => 'USD',
                'account' => 'cash',
                'transaction_date' => $validated['date'],
                'status' => 'pending',
                'created_by' => Auth::id(),
                'notes' => json_encode(['expense_type' => $validated['type'], 'original_category' => $validated['category']]),
            ]);

            return response()->json(['success' => true, 'data' => $transaction]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Save monthly expense budget via API.
     */
    public function saveMonthlyExpenseBudget(Request $request)
    {
        try {
            $validated = $request->validate([
                'month' => 'required|integer|min:0|max:11',
                'budget_amount' => 'required|numeric|min:0',
            ]);

            $year = now()->year;
            $month = $validated['month'] + 1;
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();

            // Create or update budget record
            $budget = FinancialBudget::updateOrCreate(
                [
                    'project_id' => null,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                ],
                [
                    'name' => "Monthly Expense Budget - " . $startDate->format('F Y'),
                    'allocated_amount' => $validated['budget_amount'],
                    'currency' => 'USD',
                    'status' => 'active',
                ]
            );

            return response()->json(['success' => true, 'data' => $budget]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete income source via API.
     */
    public function deleteIncomeSource($id)
    {
        try {
            $transaction = FinancialTransaction::where('id', $id)
                ->where('created_by', Auth::id())
                ->firstOrFail();
            
            $transaction->delete();
            
            return response()->json(['success' => true, 'message' => 'Income source deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete expense via API.
     */
    public function deleteExpense($id)
    {
        try {
            $transaction = FinancialTransaction::where('id', $id)
                ->where('created_by', Auth::id())
                ->firstOrFail();
            
            $transaction->delete();
            
            return response()->json(['success' => true, 'message' => 'Expense deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get income sources via API.
     */
    public function getIncomeSources()
    {
        $sources = FinancialTransaction::where('type', 'income')
            ->where('amount', 0)
            ->where('created_by', Auth::id())
            ->get();

        return response()->json(['success' => true, 'data' => $sources]);
    }

    /**
     * Get actual income transactions via API.
     */
    public function getIncomeTransactions()
    {
        $transactions = FinancialTransaction::where('type', 'income')
            ->where('amount', '>', 0)
            ->where('created_by', Auth::id())
            ->latest('transaction_date')
            ->get();

        return response()->json(['success' => true, 'data' => $transactions]);
    }

    /**
     * Get expenses via API.
     */
    public function getExpenses()
    {
        $expenses = FinancialTransaction::where('type', 'expense')
            ->where('created_by', Auth::id())
            ->latest('transaction_date')
            ->get();

        return response()->json(['success' => true, 'data' => $expenses]);
    }

    /**
     * Get budgets and actual income for a specific month via API.
     */
    public function getBudgets(Request $request)
    {
        $month = $request->query('month', now()->month - 1);
        $year = $request->query('year', now()->year);
        
        $monthNum = $month + 1;
        $startDate = Carbon::createFromDate($year, $monthNum, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($year, $monthNum, 1)->endOfMonth();

        // Get income budget
        $incomeBudget = FinancialBudget::where('project_id', null)
            ->where('start_date', $startDate)
            ->where('end_date', $endDate)
            ->where('name', 'like', '%Income Budget%')
            ->first();

        // Get actual income (completed transactions)
        $actualIncome = FinancialTransaction::where('type', 'income')
            ->where('status', 'completed')
            ->where('created_by', Auth::id())
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->where('description', 'like', '%Actual Monthly Income%')
            ->first();

        // Get expense budget
        $expenseBudget = FinancialBudget::where('project_id', null)
            ->where('start_date', $startDate)
            ->where('end_date', $endDate)
            ->where('name', 'like', '%Expense Budget%')
            ->first();

        return response()->json([
            'success' => true,
            'income_budget' => $incomeBudget,
            'actual_income' => $actualIncome,
            'expense_budget' => $expenseBudget,
        ]);
    }

    /**
     * Save savings goal via API.
     */
    public function saveSavingsGoal(Request $request)
    {
        try {
            $validated = $request->validate([
                'goal_name' => 'required|string|max:255',
                'category' => 'required|string',
                'target_amount' => 'required|numeric|min:0.01',
                'current_amount' => 'required|numeric|min:0',
                'target_date' => 'required|date',
                'monthly_contribution' => 'required|numeric|min:0',
                'notes' => 'nullable|string',
            ]);

            $budget = FinancialBudget::create([
                'name' => $validated['goal_name'],
                'allocated_amount' => $validated['target_amount'],
                'spent_amount' => $validated['current_amount'],
                'currency' => 'USD',
                'start_date' => now(),
                'end_date' => $validated['target_date'],
                'status' => 'active',
                'description' => json_encode([
                    'category' => $validated['category'],
                    'monthly_contribution' => $validated['monthly_contribution'],
                    'notes' => $validated['notes'],
                ]),
            ]);

            return response()->json(['success' => true, 'data' => $budget]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get savings goals via API.
     */
    public function getSavingsGoals()
    {
        try {
            // Get all active financial budgets (savings goals)
            $goals = FinancialBudget::where('status', 'active')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($goal) {
                    $description = json_decode($goal->description, true) ?? [];
                    return [
                        'id' => $goal->id,
                        'name' => $goal->name,
                        'category' => $description['category'] ?? 'other',
                        'target_amount' => (float) $goal->allocated_amount,
                        'current_amount' => (float) $goal->spent_amount,
                        'target_date' => $goal->end_date ? $goal->end_date->format('Y-m-d') : null,
                        'monthly_contribution' => (float) ($description['monthly_contribution'] ?? 0),
                        'notes' => $description['notes'] ?? '',
                    ];
                });

            return response()->json(['success' => true, 'data' => $goals]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Update savings goal via API.
     */
    public function updateSavingsGoal(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'goal_name' => 'nullable|string|max:255',
                'current_amount' => 'nullable|numeric|min:0',
                'monthly_contribution' => 'nullable|numeric|min:0',
                'notes' => 'nullable|string',
                'target_date' => 'nullable|date',
            ]);

            $goal = FinancialBudget::findOrFail($id);
            
            // Update basic fields
            if (isset($validated['goal_name'])) {
                $goal->name = $validated['goal_name'];
            }
            if (isset($validated['current_amount'])) {
                $goal->spent_amount = $validated['current_amount'];
            }
            if (isset($validated['target_date'])) {
                $goal->end_date = $validated['target_date'];
            }
            
            // Update description JSON
            $description = json_decode($goal->description, true) ?? [];
            if (isset($validated['monthly_contribution'])) {
                $description['monthly_contribution'] = $validated['monthly_contribution'];
            }
            if (isset($validated['notes'])) {
                $description['notes'] = $validated['notes'];
            }
            $goal->description = json_encode($description);
            
            $goal->save();

            return response()->json(['success' => true, 'data' => $goal]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete savings goal via API.
     */
    public function deleteSavingsGoal($id)
    {
        try {
            $goal = FinancialBudget::findOrFail($id);
            $goal->delete();

            return response()->json(['success' => true, 'message' => 'Savings goal deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Save donation via API.
     */
    public function saveDonation(Request $request)
    {
        try {
            $validated = $request->validate([
                'organization_name' => 'required|string|max:255',
                'category' => 'required|string',
                'type' => 'required|string',
                'amount' => 'required|numeric|min:0.01',
                'date' => 'required|date',
                'is_recurring' => 'required|string',
                'notes' => 'nullable|string',
            ]);

            $transaction = FinancialTransaction::create([
                'description' => $validated['organization_name'],
                'category' => 'donation',
                'type' => 'expense',
                'amount' => $validated['amount'],
                'currency' => 'USD',
                'account' => 'cash',
                'transaction_date' => $validated['date'],
                'status' => 'pending',
                'created_by' => Auth::id(),
                'notes' => json_encode([
                    'donation_category' => $validated['category'],
                    'donation_type' => $validated['type'],
                    'is_recurring' => $validated['is_recurring'],
                    'notes' => $validated['notes'],
                ]),
            ]);

            return response()->json(['success' => true, 'data' => $transaction]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get donations via API.
     */
    public function getDonations()
    {
        try {
            $donations = FinancialTransaction::where('type', 'expense')
                ->where('category', 'donation')
                ->where('created_by', Auth::id())
                ->latest('transaction_date')
                ->get()
                ->map(function ($donation) {
                    $notes = json_decode($donation->notes, true) ?? [];
                    return [
                        'id' => $donation->id,
                        'organizationName' => $donation->description,
                        'category' => $notes['donation_category'] ?? 'other',
                        'type' => $notes['donation_type'] ?? 'monetary',
                        'amount' => $donation->amount,
                        'date' => $donation->transaction_date,
                        'isRecurring' => $notes['is_recurring'] ?? 'no',
                        'notes' => $notes['notes'] ?? '',
                    ];
                });

            return response()->json(['success' => true, 'data' => $donations]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete donation via API.
     */
    public function deleteDonation($id)
    {
        try {
            $donation = FinancialTransaction::where('id', $id)
                ->where('created_by', Auth::id())
                ->firstOrFail();
            
            $donation->delete();
            
            return response()->json(['success' => true, 'message' => 'Donation deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get bills/subscriptions distribution for chart.
     */
    private function getBillsDistribution()
    {
        $subscriptions = FinancialTransaction::where('type', 'expense')
            ->whereIn('category', ['software', 'utilities'])
            ->whereIn('status', ['pending', 'approved', 'completed'])
            ->selectRaw('description, SUM(amount) as total')
            ->groupBy('description')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        if ($subscriptions->isEmpty()) {
            return [
                'labels' => ['No subscriptions'],
                'values' => [1],
            ];
        }

        return [
            'labels' => $subscriptions->pluck('description')->toArray(),
            'values' => $subscriptions->pluck('total')->toArray(),
        ];
    }

    /**
     * Get savings vs expenses ratio.
     */
    private function getSavingsVsExpenses($year)
    {
        $totalIncome = FinancialTransaction::where('type', 'income')
            ->whereYear('transaction_date', $year)
            ->whereIn('status', ['pending', 'approved', 'completed'])
            ->sum('amount');

        $totalExpenses = FinancialTransaction::where('type', 'expense')
            ->whereYear('transaction_date', $year)
            ->whereIn('status', ['pending', 'approved', 'completed'])
            ->sum('amount');

        $savings = max(0, $totalIncome - $totalExpenses);
        $total = $savings + $totalExpenses;
        
        if ($total == 0) {
            return ['savings' => 50, 'expenses' => 50];
        }

        return [
            'savings' => round(($savings / $total) * 100),
            'expenses' => round(($totalExpenses / $total) * 100),
        ];
    }

    /**
     * Get household expenses breakdown (debts, savings, subscriptions).
     */
    private function getHouseholdExpenses($year)
    {
        $months = [];
        $debts = [];
        $savings = [];
        $subscriptions = [];

        for ($month = 1; $month <= 12; $month++) {
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();
            
            $months[] = $startDate->format('M');

            // Get debts (could be loans or specific category)
            $monthDebts = FinancialTransaction::where('type', 'expense')
                ->whereIn('category', ['utilities', 'other'])
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->whereIn('status', ['pending', 'approved', 'completed'])
                ->sum('amount');
            $debts[] = round($monthDebts, 2);

            // Get savings (income - expenses for month)
            $monthIncome = FinancialTransaction::where('type', 'income')
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->whereIn('status', ['pending', 'approved', 'completed'])
                ->sum('amount');
            $monthExpenses = FinancialTransaction::where('type', 'expense')
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->whereIn('status', ['pending', 'approved', 'completed'])
                ->sum('amount');
            $savings[] = round(max(0, $monthIncome - $monthExpenses), 2);

            // Get subscriptions
            $monthSubs = FinancialTransaction::where('type', 'expense')
                ->where('category', 'software')
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->whereIn('status', ['pending', 'approved', 'completed'])
                ->sum('amount');
            $subscriptions[] = round($monthSubs, 2);
        }

        return [
            'months' => $months,
            'debts' => $debts,
            'savings' => $savings,
            'subscriptions' => $subscriptions,
        ];
    }

    /**
     * Get monthly savings vs debts with threshold.
     */
    private function getMonthlySavingsDebts($year)
    {
        $months = [];
        $savings = [];
        $debts = [];
        $threshold = [];

        // Calculate average monthly income for threshold
        $avgMonthlyIncome = FinancialTransaction::where('type', 'income')
            ->whereYear('transaction_date', $year)
            ->whereIn('status', ['pending', 'approved', 'completed'])
            ->sum('amount') / 12;
        $monthlyThreshold = round($avgMonthlyIncome * 0.2, 2); // 20% of income as savings goal

        for ($month = 1; $month <= 12; $month++) {
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();
            
            $months[] = $startDate->format('M');

            $monthIncome = FinancialTransaction::where('type', 'income')
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->whereIn('status', ['pending', 'approved', 'completed'])
                ->sum('amount');
            $monthExpenses = FinancialTransaction::where('type', 'expense')
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->whereIn('status', ['pending', 'approved', 'completed'])
                ->sum('amount');

            $savings[] = round(max(0, $monthIncome - $monthExpenses), 2);
            
            // Get debts/loans
            $monthDebts = FinancialTransaction::where('type', 'expense')
                ->whereIn('category', ['utilities', 'other'])
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->whereIn('status', ['pending', 'approved', 'completed'])
                ->sum('amount');
            $debts[] = round($monthDebts * 0.3, 2); // Estimate debt portion

            $threshold[] = $monthlyThreshold;
        }

        return [
            'months' => $months,
            'savings' => $savings,
            'debts' => $debts,
            'threshold' => $threshold,
        ];
    }

    /**
     * Get savings over the year with cumulative.
     */
    private function getSavingsOverYear($year)
    {
        $months = [];
        $monthlySavings = [];
        $cumulative = [];
        $runningTotal = 0;

        for ($month = 1; $month <= 12; $month++) {
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();
            
            $months[] = $startDate->format('M');

            $monthIncome = FinancialTransaction::where('type', 'income')
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->whereIn('status', ['pending', 'approved', 'completed'])
                ->sum('amount');
            $monthExpenses = FinancialTransaction::where('type', 'expense')
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->whereIn('status', ['pending', 'approved', 'completed'])
                ->sum('amount');

            $savings = max(0, $monthIncome - $monthExpenses);
            $monthlySavings[] = round($savings, 2);
            
            $runningTotal += $savings;
            $cumulative[] = round($runningTotal, 2);
        }

        return [
            'months' => $months,
            'monthly' => $monthlySavings,
            'cumulative' => $cumulative,
        ];
    }

    /**
     * Get monthly needs vs threshold.
     */
    private function getMonthlyNeeds($year)
    {
        $months = [];
        $needs = [];
        $threshold = [];

        // Calculate average monthly expenses as threshold
        $avgMonthlyExpenses = FinancialTransaction::where('type', 'expense')
            ->whereYear('transaction_date', $year)
            ->whereIn('status', ['pending', 'approved', 'completed'])
            ->sum('amount') / 12;

        for ($month = 1; $month <= 12; $month++) {
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();
            
            $months[] = $startDate->format('M');

            // Get essential expenses (needs)
            $monthNeeds = FinancialTransaction::where('type', 'expense')
                ->whereIn('category', ['utilities', 'salary', 'equipment'])
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->whereIn('status', ['pending', 'approved', 'completed'])
                ->sum('amount');
            $needs[] = round($monthNeeds, 2);
            $threshold[] = round($avgMonthlyExpenses * 0.5, 2); // 50% of avg expenses as needs threshold
        }

        return [
            'months' => $months,
            'needs' => $needs,
            'threshold' => $threshold,
        ];
    }

    /**
     * Get savings funds/goals summary.
     */
    private function getSavingsFunds()
    {
        $funds = FinancialBudget::where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get()
            ->map(function ($fund) {
                $percentage = $fund->allocated_amount > 0 
                    ? round(($fund->spent_amount / $fund->allocated_amount) * 100, 2) 
                    : 0;
                $left = max(0, $fund->allocated_amount - $fund->spent_amount);
                $monthsLeft = $fund->end_date ? Carbon::now()->diffInMonths($fund->end_date) + 1 : 12;
                $monthlyNeeded = $monthsLeft > 0 ? round($left / $monthsLeft, 2) : 0;

                return [
                    'name' => $fund->name,
                    'goal' => $fund->allocated_amount,
                    'saved' => $fund->spent_amount,
                    'left' => $left,
                    'percentage' => $percentage,
                    'monthly_left' => $monthlyNeeded,
                    'estimate_date' => $fund->end_date ? $fund->end_date->format('Y-m-d') : null,
                ];
            });

        return $funds;
    }

    /**
     * Get best saving month.
     */
    private function getBestSavingMonth($year)
    {
        $bestMonth = 'N/A';
        $bestSavings = 0;

        for ($month = 1; $month <= 12; $month++) {
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();

            $monthIncome = FinancialTransaction::where('type', 'income')
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->whereIn('status', ['pending', 'approved', 'completed'])
                ->sum('amount');
            $monthExpenses = FinancialTransaction::where('type', 'expense')
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->whereIn('status', ['pending', 'approved', 'completed'])
                ->sum('amount');

            $savings = $monthIncome - $monthExpenses;
            if ($savings > $bestSavings) {
                $bestSavings = $savings;
                $bestMonth = $startDate->format('F');
            }
        }

        return $bestMonth;
    }

    /**
     * Get biggest expense category.
     */
    private function getBiggestExpenseCategory()
    {
        $biggest = FinancialTransaction::where('type', 'expense')
            ->whereIn('status', ['pending', 'approved', 'completed'])
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->orderByDesc('total')
            ->first();

        return $biggest ? ucfirst(str_replace('_', ' ', $biggest->category)) : 'N/A';
    }
}
