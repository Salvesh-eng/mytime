<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\TimeEntryController as AdminTimeEntryController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\UserProfileController;
use App\Http\Controllers\TeamMemberController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\TimeEntryController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\ReportController as UserReportController;
use App\Http\Controllers\Admin\FinancialController;
use App\Http\Controllers\PersonalFinanceController;
use App\Http\Controllers\MotivationController;

// Public routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Public motivation page (English + Hindi videos)
Route::get('/motivation', [MotivationController::class, 'index'])->name('motivation.index');

// User routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');
    
    // Personal Finance
    Route::get('/personal-finance', [PersonalFinanceController::class, 'index'])->name('personal-finance.dashboard');
    Route::get('/personal-finance/transactions', [PersonalFinanceController::class, 'transactions'])->name('personal-finance.transactions');
    Route::get('/personal-finance/budgets', [PersonalFinanceController::class, 'budgets'])->name('personal-finance.budgets');
    Route::get('/personal-finance/loans', [PersonalFinanceController::class, 'loans'])->name('personal-finance.loans');
    Route::get('/personal-finance/reports', [PersonalFinanceController::class, 'reports'])->name('personal-finance.reports');
    Route::post('/personal-finance/income', [PersonalFinanceController::class, 'storeIncome'])->name('personal-finance.store-income');
    Route::post('/personal-finance/expense', [PersonalFinanceController::class, 'storeExpense'])->name('personal-finance.store-expense');
    Route::put('/personal-finance/income/{income}', [PersonalFinanceController::class, 'updateIncome'])->name('personal-finance.update-income');
    Route::put('/personal-finance/expense/{expense}', [PersonalFinanceController::class, 'updateExpense'])->name('personal-finance.update-expense');
    Route::delete('/personal-finance/income/{income}', [PersonalFinanceController::class, 'destroyIncome'])->name('personal-finance.destroy-income');
    Route::delete('/personal-finance/expense/{expense}', [PersonalFinanceController::class, 'destroyExpense'])->name('personal-finance.destroy-expense');
    Route::get('/api/personal-finance/incomes', [PersonalFinanceController::class, 'getIncomes']);
    Route::get('/api/personal-finance/expenses', [PersonalFinanceController::class, 'getExpenses']);
    Route::get('/api/personal-finance/loans', [PersonalFinanceController::class, 'getLoans']);
    Route::get('/api/financial/income-transactions', [FinancialController::class, 'getIncomeTransactions']);
    Route::get('/api/personal-finance/chart-data', [PersonalFinanceController::class, 'getChartData']);
    Route::get('/api/personal-finance/category-data', [PersonalFinanceController::class, 'getCategoryData']);
    Route::get('/api/personal-finance/account-details', [PersonalFinanceController::class, 'getAccountDetails']);
    Route::post('/personal-finance/push-savings', [PersonalFinanceController::class, 'pushSavings'])->name('personal-finance.push-savings');
    Route::post('/personal-finance/toggle-amounts', [PersonalFinanceController::class, 'toggleAmountVisibility'])->name('personal-finance.toggle-amounts');
    
    // Time entries
    Route::get('/add-time', [TimeEntryController::class, 'create'])->name('time-entry.create');
    Route::post('/add-time', [TimeEntryController::class, 'store'])->name('time-entry.store');
    Route::get('/my-logs', [TimeEntryController::class, 'index'])->name('time-entry.index');
    Route::get('/my-logs/{entry}', [TimeEntryController::class, 'show'])->name('time-entry.show');
    Route::get('/my-logs/{entry}/edit', [TimeEntryController::class, 'edit'])->name('time-entry.edit');
    Route::put('/my-logs/{entry}', [TimeEntryController::class, 'update'])->name('time-entry.update');
    Route::delete('/my-logs/{entry}', [TimeEntryController::class, 'destroy'])->name('time-entry.destroy');
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/change-password', [ProfileController::class, 'showChangePassword'])->name('profile.change-password');
    Route::put('/profile/change-password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
    
    // Reports
    Route::get('/reports', [UserReportController::class, 'index'])->name('user.reports.index');
    Route::get('/api/user/report-statistics', [UserReportController::class, 'getStatistics']);
    Route::get('/api/user/export-report', [UserReportController::class, 'exportReport']);
    
    // Motivation & Entertainment
    Route::get('/motivation', function () {
        return view('user.motivation');
    })->name('user.motivation');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    
    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('admin.notifications.index');
    Route::post('/notifications/{id}/mark-read', [NotificationController::class, 'markAsRead'])->name('admin.notifications.mark-read');
    Route::delete('/notifications/{id}', [NotificationController::class, 'deleteNotification'])->name('admin.notifications.delete');
    
    // User management
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::post('/users/{user}/deactivate', [UserController::class, 'deactivate'])->name('admin.users.deactivate');
    Route::post('/users/{user}/activate', [UserController::class, 'activate'])->name('admin.users.activate');
    Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('admin.users.reset-password');
    
    // User profiles and hierarchy
    Route::get('/users/{user}/profile', [UserProfileController::class, 'show'])->name('admin.users.profile');
    Route::post('/users/{user}/upload-photo', [UserProfileController::class, 'uploadPhoto'])->name('admin.users.upload-photo');
    Route::get('/users/{user}/activity-log', [UserProfileController::class, 'activityLog'])->name('admin.users.activity-log');
    Route::get('/team-hierarchy', [UserProfileController::class, 'teamHierarchy'])->name('admin.team-hierarchy');
    
    // Time entries
    Route::get('/time-entries', [AdminTimeEntryController::class, 'index'])->name('admin.time-entries.index');
    Route::get('/time-entries/{entry}', [AdminTimeEntryController::class, 'show'])->name('admin.time-entries.show');
    Route::post('/time-entries/{entry}/approve', [AdminTimeEntryController::class, 'approve'])->name('admin.time-entries.approve');
    Route::post('/time-entries/{entry}/reject', [AdminTimeEntryController::class, 'reject'])->name('admin.time-entries.reject');
    Route::post('/time-entries/{entry}/comment', [AdminTimeEntryController::class, 'addComment'])->name('admin.time-entries.comment');
    
    // Analytics
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('admin.analytics.index');
    Route::get('/analytics/export', [AnalyticsController::class, 'export'])->name('admin.analytics.export');
    
    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('admin.reports.index');
    Route::get('/reports/export-csv', [ReportController::class, 'exportCsv'])->name('admin.reports.export-csv');
    Route::get('/reports/builder', [ReportController::class, 'builder'])->name('admin.reports.builder');
    Route::post('/reports/custom', [ReportController::class, 'storeCustomReport'])->name('admin.reports.store-custom');
    Route::get('/reports/custom/{customReport}', [ReportController::class, 'showCustomReport'])->name('admin.reports.show');
    Route::get('/reports/custom/{customReport}/export-pdf', [ReportController::class, 'exportPdf'])->name('admin.reports.export-pdf');
    Route::get('/reports/custom/{customReport}/export-excel', [ReportController::class, 'exportExcel'])->name('admin.reports.export-excel');
    Route::get('/reports/list', [ReportController::class, 'listCustomReports'])->name('admin.reports.list');
    Route::delete('/reports/custom/{customReport}', [ReportController::class, 'deleteCustomReport'])->name('admin.reports.delete-custom');
    
    // Projects
    Route::get('/projects', [ProjectController::class, 'index'])->name('admin.projects.index');
    Route::get('/projects/archived', [ProjectController::class, 'archived'])->name('admin.projects.archived');
    Route::get('/projects/templates', [ProjectController::class, 'templates'])->name('admin.projects.templates');
    Route::get('/projects/create', [ProjectController::class, 'create'])->name('admin.projects.create');
    Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('admin.projects.show');
    Route::post('/projects', [ProjectController::class, 'store'])->name('admin.projects.store');
    Route::get('/projects/{project}/edit', [ProjectController::class, 'edit'])->name('admin.projects.edit');
    Route::put('/projects/{project}', [ProjectController::class, 'update'])->name('admin.projects.update');
    Route::post('/projects/{project}/archive', [ProjectController::class, 'archive'])->name('admin.projects.archive');
    Route::post('/projects/{project}/restore', [ProjectController::class, 'restore'])->name('admin.projects.restore');
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('admin.projects.destroy');
    Route::post('/projects/{project}/tags', [ProjectController::class, 'updateTags'])->name('admin.projects.update-tags');
    Route::post('/projects/{project}/budget', [ProjectController::class, 'updateBudget'])->name('admin.projects.update-budget');
    Route::post('/projects/{project}/milestone', [ProjectController::class, 'updateMilestone'])->name('admin.projects.update-milestone');
    Route::delete('/projects/milestone/{milestone}', [ProjectController::class, 'destroyMilestone'])->name('admin.projects.destroy-milestone');
    Route::post('/projects/{project}/client', [ProjectController::class, 'updateClient'])->name('admin.projects.update-client');
    Route::post('/projects/{project}/save-template', [ProjectController::class, 'saveAsTemplate'])->name('admin.projects.save-template');
    Route::get('/projects/{project}/analytics', [ProjectController::class, 'getAnalytics']);
    Route::get('/projects/filter/{category}', [ProjectController::class, 'filterByCategory'])->name('admin.projects.filter');
    Route::get('/api/projects/progress-bars', [ProjectController::class, 'getProgressBars']);
    Route::post('/api/projects/{project}/progress', [ProjectController::class, 'updateProgress'])->name('api.projects.update-progress');
    Route::post('/api/projects/{project}/status', [ProjectController::class, 'updateStatus'])->name('api.projects.update-status');
    
    // Team Members
    Route::get('/team-members', [TeamMemberController::class, 'index'])->name('admin.team-members.index');
    Route::get('/team-members/create', [TeamMemberController::class, 'create'])->name('admin.team-members.create');
    Route::post('/team-members', [TeamMemberController::class, 'store'])->name('admin.team-members.store');
    Route::get('/team-members/{teamMember}/edit', [TeamMemberController::class, 'edit'])->name('admin.team-members.edit');
    Route::put('/team-members/{teamMember}', [TeamMemberController::class, 'update'])->name('admin.team-members.update');
    Route::delete('/team-members/{teamMember}', [TeamMemberController::class, 'destroy'])->name('admin.team-members.destroy');
    
    // Financial Management
    Route::get('/financial', [FinancialController::class, 'index'])->name('admin.financial.index');
    
    // Income Management
    Route::get('/financial/income', [FinancialController::class, 'showIncome'])->name('admin.financial.income');
    Route::post('/financial/income', [FinancialController::class, 'storeIncome'])->name('admin.financial.storeIncome');
    
    // Expense Management
    Route::get('/financial/expense', function () {
        return view('admin.financial.expense');
    })->name('admin.financial.expense');
    Route::post('/financial/expense', [FinancialController::class, 'storeExpense'])->name('admin.financial.storeExpense');
    
    // Savings Management
    Route::get('/financial/savings', function () {
        return view('admin.financial.savings');
    })->name('admin.financial.savings');
    Route::post('/financial/savings', [FinancialController::class, 'storeSavings'])->name('admin.financial.storeSavings');
    
    // Donation Management
    Route::get('/financial/donation', function () {
        return view('admin.financial.donation');
    })->name('admin.financial.donation');
    Route::post('/financial/donation', [FinancialController::class, 'storeDonation'])->name('admin.financial.storeDonation');

    // API Routes for Income/Expense Management
    Route::post('/api/financial/income-source', [FinancialController::class, 'saveIncomeSource'])->name('api.financial.save-income-source');
    Route::post('/api/financial/income-budget', [FinancialController::class, 'saveMonthlyIncomeBudget'])->name('api.financial.save-income-budget');
    Route::post('/api/financial/income-actual', [FinancialController::class, 'saveMonthlyActualIncome'])->name('api.financial.save-income-actual');
    Route::post('/api/financial/expense', [FinancialController::class, 'saveExpense'])->name('api.financial.save-expense');
    Route::post('/api/financial/expense-budget', [FinancialController::class, 'saveMonthlyExpenseBudget'])->name('api.financial.save-expense-budget');
    Route::get('/api/financial/income-sources', [FinancialController::class, 'getIncomeSources'])->name('api.financial.get-income-sources');
    Route::get('/api/financial/expenses', [FinancialController::class, 'getExpenses'])->name('api.financial.get-expenses');
    Route::get('/api/financial/budgets', [FinancialController::class, 'getBudgets'])->name('api.financial.get-budgets');
    Route::delete('/api/financial/income-source/{id}', [FinancialController::class, 'deleteIncomeSource'])->name('api.financial.delete-income-source');
    Route::delete('/api/financial/expense/{id}', [FinancialController::class, 'deleteExpense'])->name('api.financial.delete-expense');
    
    // API Routes for Savings Goals
    Route::post('/api/financial/savings-goal', [FinancialController::class, 'saveSavingsGoal'])->name('api.financial.save-savings-goal');
    Route::get('/api/financial/savings-goals', [FinancialController::class, 'getSavingsGoals'])->name('api.financial.get-savings-goals');
    Route::put('/api/financial/savings-goal/{id}', [FinancialController::class, 'updateSavingsGoal'])->name('api.financial.update-savings-goal');
    Route::delete('/api/financial/savings-goal/{id}', [FinancialController::class, 'deleteSavingsGoal'])->name('api.financial.delete-savings-goal');
    
    // API Routes for Donations
    Route::post('/api/financial/donation', [FinancialController::class, 'saveDonation'])->name('api.financial.save-donation');
    Route::get('/api/financial/donations', [FinancialController::class, 'getDonations'])->name('api.financial.get-donations');
    Route::delete('/api/financial/donation/{id}', [FinancialController::class, 'deleteDonation'])->name('api.financial.delete-donation');
    
    // Income Transactions (Legacy)
    Route::get('/financial/income/create', [FinancialController::class, 'createIncome'])->name('admin.financial.createIncome');
    Route::post('/financial/income/store', [FinancialController::class, 'storeIncome'])->name('admin.financial.storeIncome');
    
    // Expense Transactions (Legacy)
    Route::get('/financial/expense/create', [FinancialController::class, 'createExpense'])->name('admin.financial.createExpense');
    Route::post('/financial/expense/store', [FinancialController::class, 'storeExpense'])->name('admin.financial.storeExpense');
    
    // Savings Transactions (Legacy)
    Route::get('/financial/savings/create', [FinancialController::class, 'createSavings'])->name('admin.financial.createSavings');
    Route::post('/financial/savings/store', [FinancialController::class, 'storeSavings'])->name('admin.financial.storeSavings');
    
    // Transactions
    Route::get('/financial/transactions', [FinancialController::class, 'transactions'])->name('admin.financial.transactions');
    Route::get('/financial/transactions/create', [FinancialController::class, 'createTransaction'])->name('admin.financial.createTransaction');
    Route::post('/financial/transactions', [FinancialController::class, 'storeTransaction'])->name('admin.financial.storeTransaction');
    Route::get('/financial/transactions/{transaction}', [FinancialController::class, 'showTransaction'])->name('admin.financial.showTransaction');
    Route::post('/financial/transactions/{transaction}/approve', [FinancialController::class, 'approveTransaction'])->name('admin.financial.approveTransaction');
    Route::post('/financial/transactions/{transaction}/reject', [FinancialController::class, 'rejectTransaction'])->name('admin.financial.rejectTransaction');
    Route::delete('/financial/transactions/{transaction}', [FinancialController::class, 'destroyTransaction'])->name('admin.financial.destroyTransaction');
    
    // Budgets
    Route::get('/financial/budgets', [FinancialController::class, 'budgets'])->name('admin.financial.budgets');
    Route::get('/financial/budgets/create', [FinancialController::class, 'createBudget'])->name('admin.financial.createBudget');
    Route::post('/financial/budgets', [FinancialController::class, 'storeBudget'])->name('admin.financial.storeBudget');
    
    // Invoices
    Route::get('/financial/invoices', [FinancialController::class, 'invoices'])->name('admin.financial.invoices');
    Route::get('/financial/invoices/create', [FinancialController::class, 'createInvoice'])->name('admin.financial.createInvoice');
    Route::post('/financial/invoices', [FinancialController::class, 'storeInvoice'])->name('admin.financial.storeInvoice');
    Route::post('/financial/invoices/{invoice}/send', [FinancialController::class, 'sendInvoice'])->name('admin.financial.sendInvoice');
    Route::post('/financial/invoices/{invoice}/mark-paid', [FinancialController::class, 'markInvoicePaid'])->name('admin.financial.markInvoicePaid');
});

Route::get('/', function () {
    return redirect('/login');
});

// API Routes
Route::middleware('auth')->group(function () {
    Route::get('/api/notifications', [NotificationController::class, 'getNotifications']);
    Route::get('/api/user/dashboard-metrics', [UserDashboardController::class, 'getMetrics']);
    Route::get('/api/search', [AdminDashboardController::class, 'search']);
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/api/admin/dashboard-metrics', [AdminDashboardController::class, 'getMetrics']);
    Route::get('/api/admin/recent-activities', [AdminDashboardController::class, 'getRecentActivities']);
    Route::get('/api/admin/analytics-metrics', [AnalyticsController::class, 'getMetrics'])->name('admin.analytics.getMetrics');
});
