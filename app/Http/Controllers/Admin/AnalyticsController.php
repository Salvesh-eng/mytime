<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TimeEntry;
use App\Models\Project;
use App\Models\User;
use App\Models\TeamMember;
use App\Models\PersonalIncome;
use App\Models\PersonalExpense;
use App\Models\FinancialTransaction;
use App\Models\FinancialBudget;
use App\Models\FinancialInvoice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AnalyticsController extends Controller
{
    /**
     * Display analytics dashboard.
     */
    public function index(Request $request)
    {
        $dateRange = $request->query('range', 'month');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        // Determine date range
        [$start, $end] = $this->getDateRange($dateRange, $startDate, $endDate);
        [$prevStart, $prevEnd] = $this->getPreviousDateRange($start, $end);

        // Get current period data
        $currentData = $this->getAnalyticsData($start, $end);
        $previousData = $this->getAnalyticsData($prevStart, $prevEnd);

        // Calculate comparisons
        $comparisons = $this->calculateComparisons($currentData, $previousData);

        // Get detailed data for charts
        $dailyData = $this->getDailyData($start, $end);
        $projectStatus = $this->getProjectStatusData();
        $teamUtilization = $this->getTeamUtilizationData($start, $end);
        $hoursByProject = $this->getHoursByProject($start, $end);
        
        // Get financial data
        $financialData = $this->getFinancialData($start, $end);
        $monthlyFinancialData = $this->getMonthlyFinancialData();
        $transactionsByCategory = $this->getTransactionsByCategory($start, $end);
        $invoiceData = $this->getInvoiceData($start, $end);
        
        // Get additional chart data
        $weeklyProductivity = $this->getWeeklyProductivity();
        $projectProgress = $this->getProjectProgress();
        $teamPerformance = $this->getTeamPerformance();
        $taskCompletion = $this->getTaskCompletionData();
        $monthlyActivity = $this->getMonthlyActivity();
        $incomeSources = $this->getIncomeSources($start, $end);
        $savingsData = $this->getSavingsData();
        $netProfitTrend = $this->getNetProfitTrend();

        return view('admin.analytics.index', [
            'currentData' => $currentData,
            'comparisons' => $comparisons,
            'dateRange' => $dateRange,
            'startDate' => $start,
            'endDate' => $end,
            'dailyData' => $dailyData,
            'projectStatus' => $projectStatus,
            'teamUtilization' => $teamUtilization,
            'hoursByProject' => $hoursByProject,
            'financialData' => $financialData,
            'monthlyFinancialData' => $monthlyFinancialData,
            'transactionsByCategory' => $transactionsByCategory,
            'invoiceData' => $invoiceData,
            'weeklyProductivity' => $weeklyProductivity,
            'projectProgress' => $projectProgress,
            'teamPerformance' => $teamPerformance,
            'taskCompletion' => $taskCompletion,
            'monthlyActivity' => $monthlyActivity,
            'incomeSources' => $incomeSources,
            'savingsData' => $savingsData,
            'netProfitTrend' => $netProfitTrend,
        ]);
    }

    /**
     * Get analytics data for a date range.
     */
    private function getAnalyticsData(Carbon $start, Carbon $end)
    {
        $timeEntries = TimeEntry::whereBetween('entry_date', [$start, $end])
            ->with('user', 'project')
            ->get();

        $totalHours = $timeEntries->sum('duration_minutes') / 60;
        $billableHours = $timeEntries->where('is_billable', true)->sum('duration_minutes') / 60;
        $billablePercentage = $totalHours > 0 ? ($billableHours / $totalHours) * 100 : 0;

        $activeProjects = Project::where('is_archived', false)
            ->whereBetween('start_date', [$start->copy()->subMonths(3), $end])
            ->count();

        $daysWorked = $timeEntries->pluck('entry_date')->unique()->count();
        $averageHoursPerDay = $daysWorked > 0 ? $totalHours / $daysWorked : 0;

        // Calculate revenue (assuming $50/hour billable rate - adjust as needed)
        $revenue = $billableHours * 50;

        // Team utilization
        $totalEmployees = User::where('role', 'user')->count();
        $utilizationRate = ($totalEmployees > 0 && $daysWorked > 0) ? ($totalHours / ($totalEmployees * $daysWorked * 8)) * 100 : 0;

        return [
            'total_hours' => round($totalHours, 1),
            'billable_hours' => round($billableHours, 1),
            'billable_percentage' => round($billablePercentage, 1),
            'active_projects' => $activeProjects,
            'average_hours_per_day' => round($averageHoursPerDay, 1),
            'revenue' => round($revenue, 2),
            'utilization_rate' => round(min(100, $utilizationRate), 1),
            'days_worked' => $daysWorked,
            'total_entries' => $timeEntries->count(),
        ];
    }

    /**
     * Get project status breakdown.
     */
    private function getProjectStatusData()
    {
        $projects = Project::where('is_archived', false)->get();

        $onTrack = $projects->filter(fn($p) => $p->progress <= 100 && $p->progress > 50)->count();
        $atRisk = $projects->filter(fn($p) => $p->progress <= 50 && $p->progress > 0)->count();
        $ahead = $projects->filter(fn($p) => $p->progress > 100)->count();

        return [
            'total' => $projects->count(),
            'on_track' => $onTrack,
            'at_risk' => $atRisk,
            'ahead' => $ahead,
        ];
    }

    /**
     * Get team utilization breakdown.
     */
    private function getTeamUtilizationData($start, $end)
    {
        $employees = User::where('role', 'user')
            ->with('timeEntries')
            ->get()
            ->map(function ($user) use ($start, $end) {
                $hours = $user->timeEntries()
                    ->whereBetween('entry_date', [$start, $end])
                    ->sum('duration_minutes') / 60;

                return [
                    'name' => $user->name,
                    'hours' => round($hours, 1),
                ];
            })
            ->sortByDesc('hours');

        return $employees;
    }

    /**
     * Get daily data for sparklines.
     */
    private function getDailyData($start, $end)
    {
        $days = [];
        $values = [];

        for ($date = $start->copy(); $date <= $end; $date->addDay()) {
            $days[] = $date->format('M d');

            $dayHours = TimeEntry::whereDate('entry_date', $date)
                ->sum('duration_minutes') / 60;

            $values[] = round($dayHours, 1);
        }

        return [
            'labels' => $days,
            'values' => $values,
        ];
    }

    /**
     * Get hours by project.
     */
    private function getHoursByProject($start, $end)
    {
        return TimeEntry::whereBetween('entry_date', [$start, $end])
            ->with('project')
            ->get()
            ->groupBy('project_id')
            ->map(function ($entries, $projectId) {
                $project = $entries->first()->project;
                return [
                    'project' => $project->name ?? 'Unassigned',
                    'hours' => round($entries->sum('duration_minutes') / 60, 1),
                    'entries' => $entries->count(),
                ];
            })
            ->sortByDesc('hours')
            ->take(10);
    }

    /**
     * Get date range based on filter.
     */
    private function getDateRange($range, $customStart = null, $customEnd = null)
    {
        $today = Carbon::today();

        return match ($range) {
            'today' => [$today, $today],
            'yesterday' => [$today->copy()->subDay(), $today->copy()->subDay()],
            '7days' => [$today->copy()->subDays(6), $today],
            '30days' => [$today->copy()->subDays(29), $today],
            'month' => [$today->copy()->startOfMonth(), $today->copy()->endOfMonth()],
            'last_month' => [$today->copy()->subMonth()->startOfMonth(), $today->copy()->subMonth()->endOfMonth()],
            'custom' => [Carbon::parse($customStart), Carbon::parse($customEnd)],
            default => [$today->copy()->startOfMonth(), $today],
        };
    }

    /**
     * Get previous period for comparison.
     */
    private function getPreviousDateRange(Carbon $start, Carbon $end)
    {
        $interval = $start->diffInDays($end);
        $prevEnd = $start->copy()->subDay();
        $prevStart = $prevEnd->copy()->subDays($interval);

        return [$prevStart, $prevEnd];
    }

    /**
     * Calculate comparisons between periods.
     */
    private function calculateComparisons($current, $previous)
    {
        return [
            'hours_change' => $previous['total_hours'] > 0
                ? round((($current['total_hours'] - $previous['total_hours']) / $previous['total_hours']) * 100, 1)
                : 0,
            'billable_change' => $previous['billable_hours'] > 0
                ? round((($current['billable_hours'] - $previous['billable_hours']) / $previous['billable_hours']) * 100, 1)
                : 0,
            'utilization_change' => round($current['utilization_rate'] - $previous['utilization_rate'], 1),
            'revenue_change' => $previous['revenue'] > 0
                ? round((($current['revenue'] - $previous['revenue']) / $previous['revenue']) * 100, 1)
                : 0,
        ];
    }

    /**
     * Get analytics data via API (for auto-refresh).
     */
    public function getMetrics(Request $request)
    {
        $dateRange = $request->query('range', 'month');
        [$start, $end] = $this->getDateRange($dateRange);

        $data = $this->getAnalyticsData($start, $end);
        $dailyData = $this->getDailyData($start, $end);

        return response()->json([
            'metrics' => $data,
            'daily_data' => $dailyData,
            'timestamp' => now()->format('H:i:s'),
        ]);
    }

    /**
     * Export analytics data.
     */
    public function export(Request $request)
    {
        $format = $request->query('format', 'csv');
        $dateRange = $request->query('range', 'month');
        [$start, $end] = $this->getDateRange($dateRange);

        $data = $this->getAnalyticsData($start, $end);
        $entries = TimeEntry::whereBetween('entry_date', [$start, $end])
            ->with('user', 'project')
            ->get();

        return match ($format) {
            'csv' => $this->exportCsv($entries, $data),
            'xlsx' => $this->exportExcel($entries, $data),
            'pdf' => $this->exportPdf($entries, $data),
            default => response()->json(['error' => 'Invalid format'], 400),
        };
    }

    /**
     * Export as CSV.
     */
    private function exportCsv($entries, $summary)
    {
        $filename = 'analytics_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $callback = function () use ($entries, $summary) {
            $file = fopen('php://output', 'w');

            // Summary
            fputcsv($file, ['Analytics Summary']);
            fputcsv($file, ['Total Hours', $summary['total_hours']]);
            fputcsv($file, ['Billable Hours', $summary['billable_hours']]);
            fputcsv($file, ['Average Hours/Day', $summary['average_hours_per_day']]);
            fputcsv($file, ['Revenue', '$' . number_format($summary['revenue'], 2)]);

            fputcsv($file, []);
            fputcsv($file, ['Time Entries']);
            fputcsv($file, ['Date', 'User', 'Project', 'Hours', 'Type', 'Description']);

            foreach ($entries as $entry) {
                fputcsv($file, [
                    $entry->entry_date->format('Y-m-d'),
                    $entry->user->name,
                    $entry->project->name ?? 'N/A',
                    round($entry->duration_minutes / 60, 2),
                    $entry->is_billable ? 'Billable' : 'Internal',
                    $entry->description ?? '',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
    }

    /**
     * Export as Excel (requires maatwebsite/excel).
     */
    private function exportExcel($entries, $summary)
    {
        return response()->json([
            'message' => 'Excel export requires Laravel Excel package installation',
            'hint' => 'composer require maatwebsite/excel',
        ], 501);
    }

    /**
     * Export as PDF (requires TCPDF or similar).
     */
    private function exportPdf($entries, $summary)
    {
        return response()->json([
            'message' => 'PDF export requires PDF generation package installation',
            'hint' => 'composer require barryvdh/laravel-dompdf',
        ], 501);
    }

    /**
     * Get financial data for date range.
     */
    private function getFinancialData(Carbon $start, Carbon $end)
    {
        $income = FinancialTransaction::where('type', 'income')
            ->where('status', 'completed')
            ->whereBetween('transaction_date', [$start, $end])
            ->sum('amount');

        $expenses = FinancialTransaction::where('type', 'expense')
            ->where('status', 'completed')
            ->whereBetween('transaction_date', [$start, $end])
            ->sum('amount');

        $pendingTransactions = FinancialTransaction::where('status', 'pending')
            ->whereBetween('transaction_date', [$start, $end])
            ->sum('amount');

        $totalBudget = FinancialBudget::where('status', 'active')
            ->whereBetween('start_date', [$start, $end])
            ->sum('allocated_amount');

        $budgetSpent = FinancialBudget::where('status', 'active')
            ->whereBetween('start_date', [$start, $end])
            ->sum('spent_amount');

        $paidInvoices = FinancialInvoice::where('status', 'paid')
            ->whereBetween('issue_date', [$start, $end])
            ->sum('total_amount');

        return [
            'income' => round($income, 2),
            'expenses' => round($expenses, 2),
            'net_profit' => round($income - $expenses, 2),
            'pending_transactions' => round($pendingTransactions, 2),
            'total_budget' => round($totalBudget, 2),
            'budget_spent' => round($budgetSpent, 2),
            'budget_remaining' => round($totalBudget - $budgetSpent, 2),
            'paid_invoices' => round($paidInvoices, 2),
        ];
    }

    /**
     * Get monthly financial data for the year.
     */
    private function getMonthlyFinancialData()
    {
        $months = [];
        $income = [];
        $expenses = [];

        for ($month = 1; $month <= 12; $month++) {
            $startDate = Carbon::createFromDate(now()->year, $month, 1)->startOfMonth();
            $endDate = Carbon::createFromDate(now()->year, $month, 1)->endOfMonth();

            $months[] = $startDate->format('M');

            $monthIncome = FinancialTransaction::where('type', 'income')
                ->where('status', 'completed')
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->sum('amount');

            $monthExpense = FinancialTransaction::where('type', 'expense')
                ->where('status', 'completed')
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->sum('amount');

            $income[] = round($monthIncome, 2);
            $expenses[] = round($monthExpense, 2);
        }

        return [
            'months' => $months,
            'income' => $income,
            'expenses' => $expenses,
        ];
    }

    /**
     * Get transactions by category.
     */
    private function getTransactionsByCategory(Carbon $start, Carbon $end)
    {
        $categories = ['salary', 'equipment', 'software', 'travel', 'utilities', 'marketing', 'client_payment', 'other'];
        $data = [];

        foreach ($categories as $category) {
            $amount = FinancialTransaction::where('category', $category)
                ->where('status', 'completed')
                ->whereBetween('transaction_date', [$start, $end])
                ->sum('amount');

            if ($amount > 0) {
                $data[] = [
                    'category' => ucfirst(str_replace('_', ' ', $category)),
                    'amount' => round($amount, 2),
                ];
            }
        }

        return collect($data)->sortByDesc('amount')->values();
    }

    /**
     * Get invoice data.
     */
    private function getInvoiceData(Carbon $start, Carbon $end)
    {
        $paid = FinancialInvoice::where('status', 'paid')
            ->whereBetween('issue_date', [$start, $end])
            ->count();

        $sent = FinancialInvoice::where('status', 'sent')
            ->whereBetween('issue_date', [$start, $end])
            ->count();

        $draft = FinancialInvoice::where('status', 'draft')
            ->whereBetween('issue_date', [$start, $end])
            ->count();

        $overdue = FinancialInvoice::where('status', 'overdue')
            ->whereBetween('issue_date', [$start, $end])
            ->count();

        return [
            'paid' => $paid,
            'sent' => $sent,
            'draft' => $draft,
            'overdue' => $overdue,
            'total' => $paid + $sent + $draft + $overdue,
        ];
    }

    /**
     * Get weekly productivity data (tasks/projects completed per day of week).
     */
    private function getWeeklyProductivity()
    {
        $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        $values = [];

        // Get projects completed in last 4 weeks grouped by day of week
        $startDate = Carbon::now()->subWeeks(4);
        
        // SQLite uses strftime('%w', date) where 0=Sunday, 1=Monday, etc.
        // MySQL uses DAYOFWEEK where 1=Sunday, 2=Monday, etc.
        $driver = DB::connection()->getDriverName();
        
        for ($i = 0; $i < 7; $i++) {
            // For our array: 0=Mon, 1=Tue, ..., 6=Sun
            // SQLite %w: 0=Sun, 1=Mon, ..., 6=Sat
            $sqliteDay = ($i + 1) % 7; // Convert our index to SQLite day (Mon=1, Tue=2, ..., Sun=0)
            $mysqlDay = $i + 2; // MySQL: Mon=2, Tue=3, ..., Sun=1
            if ($mysqlDay > 7) $mysqlDay = 1;
            
            if ($driver === 'sqlite') {
                $count = Project::where('status', 'completed')
                    ->where('updated_at', '>=', $startDate)
                    ->whereRaw("cast(strftime('%w', updated_at) as integer) = ?", [$sqliteDay])
                    ->count();
                
                $timeEntryCount = TimeEntry::where('entry_date', '>=', $startDate)
                    ->whereRaw("cast(strftime('%w', entry_date) as integer) = ?", [$sqliteDay])
                    ->count();
            } else {
                $count = Project::where('status', 'completed')
                    ->where('updated_at', '>=', $startDate)
                    ->whereRaw('DAYOFWEEK(updated_at) = ?', [$mysqlDay])
                    ->count();
                
                $timeEntryCount = TimeEntry::where('entry_date', '>=', $startDate)
                    ->whereRaw('DAYOFWEEK(entry_date) = ?', [$mysqlDay])
                    ->count();
            }
            
            $values[] = $count + round($timeEntryCount / 4); // Average over 4 weeks
        }

        return [
            'labels' => $days,
            'values' => $values,
        ];
    }

    /**
     * Get project progress data (top projects by progress).
     */
    private function getProjectProgress()
    {
        $projects = Project::where('is_archived', false)
            ->whereNotNull('progress')
            ->orderByDesc('updated_at')
            ->take(5)
            ->get(['name', 'progress', 'status']);

        return [
            'labels' => $projects->pluck('name')->toArray(),
            'values' => $projects->pluck('progress')->toArray(),
            'statuses' => $projects->pluck('status')->toArray(),
        ];
    }

    /**
     * Get team performance data (team members with task counts).
     */
    private function getTeamPerformance()
    {
        // Get team members with their project assignments
        $teamMembers = TeamMember::with('projects')
            ->where('status', 'active')
            ->take(5)
            ->get();

        if ($teamMembers->isEmpty()) {
            // Fallback to users if no team members
            $users = User::where('status', 'active')
                ->take(5)
                ->get();

            return [
                'labels' => $users->pluck('name')->toArray(),
                'values' => $users->map(function ($user) {
                    return $user->timeEntries()->count();
                })->toArray(),
            ];
        }

        return [
            'labels' => $teamMembers->pluck('name')->toArray(),
            'values' => $teamMembers->map(function ($member) {
                return $member->projects()->count();
            })->toArray(),
        ];
    }

    /**
     * Get task completion data (completed, in progress, pending).
     */
    private function getTaskCompletionData()
    {
        $total = Project::where('is_archived', false)->count();
        
        $completed = Project::where('is_archived', false)
            ->where('status', 'completed')
            ->count();
        
        $inProgress = Project::where('is_archived', false)
            ->where('status', 'in-progress')
            ->count();
        
        $pending = Project::where('is_archived', false)
            ->whereIn('status', ['planning', 'on-hold'])
            ->count();

        // Calculate percentages
        $completedPct = $total > 0 ? round(($completed / $total) * 100) : 0;
        $inProgressPct = $total > 0 ? round(($inProgress / $total) * 100) : 0;
        $pendingPct = $total > 0 ? round(($pending / $total) * 100) : 0;

        return [
            'labels' => ['Completed', 'In Progress', 'Pending'],
            'values' => [$completedPct, $inProgressPct, $pendingPct],
            'counts' => [$completed, $inProgress, $pending],
        ];
    }

    /**
     * Get monthly activity data (projects completed per month).
     */
    private function getMonthlyActivity()
    {
        $months = [];
        $values = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M');

            $count = Project::where('status', 'completed')
                ->whereYear('updated_at', $date->year)
                ->whereMonth('updated_at', $date->month)
                ->count();

            $values[] = $count;
        }

        return [
            'labels' => $months,
            'values' => $values,
        ];
    }

    /**
     * Get income sources breakdown.
     */
    private function getIncomeSources(Carbon $start, Carbon $end)
    {
        $user = Auth::user();
        
        // Get personal income by source
        $incomeBySource = PersonalIncome::where('user_id', $user->id ?? 1)
            ->whereBetween('date', [$start, $end])
            ->select('source', DB::raw('SUM(amount) as total'))
            ->groupBy('source')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        if ($incomeBySource->isEmpty()) {
            // Fallback to financial transactions
            $incomeBySource = FinancialTransaction::where('type', 'income')
                ->where('status', 'completed')
                ->whereBetween('transaction_date', [$start, $end])
                ->select('category as source', DB::raw('SUM(amount) as total'))
                ->groupBy('category')
                ->orderByDesc('total')
                ->take(5)
                ->get();
        }

        return [
            'labels' => $incomeBySource->pluck('source')->map(function ($source) {
                return ucfirst(str_replace('_', ' ', $source ?? 'Other'));
            })->toArray(),
            'values' => $incomeBySource->pluck('total')->toArray(),
        ];
    }

    /**
     * Get savings data for the last 6 months.
     */
    private function getSavingsData()
    {
        $user = Auth::user();
        $months = [];
        $savings = [];
        $investments = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M');
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();

            // Calculate savings (income - expenses for the month)
            $income = PersonalIncome::where('user_id', $user->id ?? 1)
                ->whereBetween('date', [$startOfMonth, $endOfMonth])
                ->sum('amount');

            $expenses = PersonalExpense::where('user_id', $user->id ?? 1)
                ->whereBetween('date', [$startOfMonth, $endOfMonth])
                ->sum('amount');

            $monthlySavings = max(0, $income - $expenses);
            $savings[] = round($monthlySavings, 2);

            // Get investments (expense category = investment)
            $investment = PersonalExpense::where('user_id', $user->id ?? 1)
                ->where('category', 'investment')
                ->whereBetween('date', [$startOfMonth, $endOfMonth])
                ->sum('amount');

            $investments[] = round($investment, 2);
        }

        return [
            'labels' => $months,
            'savings' => $savings,
            'investments' => $investments,
        ];
    }

    /**
     * Get net profit trend for the last 6 months.
     */
    private function getNetProfitTrend()
    {
        $user = Auth::user();
        $months = [];
        $profits = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M');
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();

            // Calculate from personal finance
            $income = PersonalIncome::where('user_id', $user->id ?? 1)
                ->whereBetween('date', [$startOfMonth, $endOfMonth])
                ->sum('amount');

            $expenses = PersonalExpense::where('user_id', $user->id ?? 1)
                ->whereBetween('date', [$startOfMonth, $endOfMonth])
                ->sum('amount');

            // Fallback to financial transactions if no personal data
            if ($income == 0 && $expenses == 0) {
                $income = FinancialTransaction::where('type', 'income')
                    ->where('status', 'completed')
                    ->whereBetween('transaction_date', [$startOfMonth, $endOfMonth])
                    ->sum('amount');

                $expenses = FinancialTransaction::where('type', 'expense')
                    ->where('status', 'completed')
                    ->whereBetween('transaction_date', [$startOfMonth, $endOfMonth])
                    ->sum('amount');
            }

            $profits[] = round($income - $expenses, 2);
        }

        return [
            'labels' => $months,
            'values' => $profits,
        ];
    }
}
